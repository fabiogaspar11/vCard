<template>
  <Sidebar></Sidebar>
  <Navbar></Navbar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2>Delete Vcard</h2>

    <div id="page">
      <div id="deletevcard" class="color" v-if="this.loaded">
        <img class="center" src="../../assets/img/logo.png" />
        <h1 class="vcard">{{ this.balance }} €</h1>
        <div v-show="this.piggyBalance != null">
          <img class="piggy" src="../../assets/img/piggyBank.png" />
          <h3 class="vcard">{{ this.piggyBalance }} €</h3>
        </div>
      </div>
      <br />
      <div
        id="deletevcard"
        class="color"
        v-show="this.loaded"
        v-if="this.vcard != null"
      >
        <p>
          <b>Want to delete this vcard? ({{ this.vcard.phone_number }})</b>
        </p>
        <div class="container-fluid">
          <div class="form-group mx-sm-3 mb-2">
            <h5>PIN:</h5>
            <input
              maxlength="4"
              type="password"
              class="form-control"
              placeholder="Insert PIN to confirm this action"
              v-model="this.confirmation_code"
            />
            <div
              v-show="errors.confirmation_code != undefined"
              class="text-danger"
            >
              {{ errors.confirmation_code }}
            </div>
            <h5>Password:</h5>
            <input
              type="password"
              class="form-control"
              placeholder="Insert Password to confirm this action"
              v-model="this.password"
            />
            <div v-show="errors.password != undefined" class="text-danger">
              {{ errors.password }}
            </div>
          </div>
          <div v-show="errors.balance != undefined" class="text-danger">
            {{ errors.balance }}
          </div>
          <br />
          <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
              <button
                type="button"
                class="btn btn-light"
                @click="$router.push({ name: 'dashboard' })"
              >
                Cancel
              </button>
            </div>
            <div class="col-4">
              <button
                type="button"
                class="btn btn-danger"
                @click.prevent="deleteVcard"
              >
                Delete
              </button>
            </div>
          </div>
        </div>

        <div
          class="spinner-border text-primary loading_vcard"
          role="status"
          v-show="!this.loaded"
        >
          <span class="sr-only"></span>
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import Navbar from "../../components/Navbar.vue";
import Sidebar from "../../components/Sidebar.vue";

export default {
  name: "Delete Vcard",
  components: {
    Navbar,
    Sidebar,
  },
  data() {
    return {
      vcard: null,
      balance: 0,
      password: null,
      confirmation_code: null,
      piggyBalance: null,
      loaded: false,
      errors: [],
      config: {
        header: {
          "Content-Type": "multipart/form-data",
          Accept: "application/json",
          Authorization: "Bearer " + localStorage.getItem("access_token"),
        },
      },
    };
  },
  computed: {
    newTransacion() {
      return this.$store.getters.newTransacion;
    },
  },
  watch: {
    newTransacion: {
      handler() {
        if (this.$store.getters.newTransacion) {
          this.$axios.get(`/vcards/${this.$store.getters.username}`).then((response) => {
            this.vcard = response.data.data;
            this.balance = this.vcard.balance;
          });
        }
      },
      deep: true,
    },
  },
  async created() {
    await this.$axios
      .get(`/vcards/${this.$store.getters.username}`)
      .then((response) => {
        this.vcard = response.data.data;
        this.balance = this.vcard.balance;
        this.loaded = true;
      });
    await this.$axios
      .get(`/vcards/${this.$store.getters.username}/getPiggyBankBalance`)
      .then((response) => {
        if (response.data.balance != null) {
          this.piggyBalance = response.data.balance;
        }
      });
  },
  methods: {
    deleteVcard() {
      let formData = new FormData();
      if (this.confirmation_code != null) {
        formData.append("confirmation_code", this.confirmation_code);
      }
      if (this.password != null) {
        formData.append("password", this.password);
      }

      formData.append("balance", this.balance);

      formData.append("_method", "DELETE");

      this.errors = [];

      this.$axios
        .post(`/vcards/${this.$store.getters.username}`, formData, this.config)
        .then(() => {
          this.$router.push({
            name: "login",
          });
        })
        .catch((error) => {
          this.errors = [];
          Object.entries(error.response.data.errors).forEach(([key, val]) => {
            this.errors[key] = val[0];
          });
        });
    },
  },
};
</script>

<style scoped lang="css">
.piggy {
  width: 50px;
}

#deletevcard {
  width: 90%;
  margin: auto;
  border-radius: 10px;
}

.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 30%;
}

#page {
  text-align: center;
  margin: 0 auto;
  padding-bottom: 20%;
}

.loading_vcard {
  margin: 50% auto auto auto;
  width: 150px;
  height: 150px;
}

.buttonDeleteVcard {
  background: black;
}
.buttonDeleteVcard:hover {
  background: #333333;
  transition-duration: 0.5s;
}
</style>
