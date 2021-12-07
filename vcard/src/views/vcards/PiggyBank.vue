<template>
  <Navbar></Navbar>
  <Sidebar></Sidebar>
      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

  <div class="container">
    <h1>Piggy Bank</h1>
    <div class="d-flex justify-content-center">
      <img class="center w-25 p-3" src="../../assets/img/piggyBank.png" />
    </div>
    <br />
    <div v-if="this.loaded" class="space">
      <div v-if="this.isCreated == true && this.piggyBalance != null">
        <div v-if="this.balance > 0">
          <br />
          <h2>{{ this.piggyBalance + " €" }}</h2>
          <br />
          <label><b>Value:</b></label>
          <input
            type="number"
            class="form-control"
            placeholder="Enter value"
            name="uname"
            required
          />
          <br />
          <div></div>
          <button class="btn btn-success" type="submit">Insert Money</button>
          <div v-if="this.piggyBalance > 0" class="space">
            <button v-show="this.piggyBalance > 0" class="btn btn-danger">
              Take Money
            </button>
          </div>
          <div v-else>
            <i class="bi bi-x-circle icon_error" style="font-size: 6rem"></i>
            <h5>No money in the piggy bank to take</h5>
          </div>
        </div>

        <div v-else>
          <div v-show="this.loaded">
            <i class="bi bi-x-circle icon_error" style="font-size: 6rem"></i>
            <h5>No balance to save in the piggy bank</h5>
          </div>
        </div>
      </div>
      <div v-else>
        <button
          class="btn btn-warning"
          type="submit"
          @click.prevent="createPiggyBank"
        >
          Create a piggy bank
        </button>
      </div>
      <br>
    </div>
    <div v-else>
      <div
        class="spinner-border text-primary loading_vcard"
        role="status"
        v-show="!this.loaded"
      ></div>
    </div>
  </div>
      </main>
</template>

<script>
import Navbar from "../../components/Navbar.vue";
import Sidebar from "../../components/Sidebar.vue";

export default {
  name: "Piggy Bank",
  components: {
    Navbar,
    Sidebar,
  },
  data() {
    return {
      isCreated: null,
      piggyBalance: null,
      loaded: false,
      balance: null,
    };
  },
  async created() {
    await this.$axios
      .get(`/vcards/${this.$store.getters.username}/piggyBankState`)
      .then((response) => {
        this.isCreated = response.data.response;
        if (this.isCreated) {
          this.$axios
            .get(
              `/vcards/${this.$store.getters.username}/getPiggyBankBalance`
            )
            .then((response) => {
              this.piggyBalance = response.data.balance;
            });
          this.$axios
            .get(`/vcards/${this.$store.getters.username}`)
            .then((response) => {
              this.balance = response.data.data.balance;
              this.loaded = true;
            });
        } else {
          this.loaded = true;
        }
      });
  },
  methods: {
    async createPiggyBank() {
      await this.$axios
        .get(`/vcards/${this.$store.getters.username}/createPiggyBank`)
        .then((response) => {
          this.piggyBalance = response.data;
        });
      await this.$axios
        .get(`/vcards/${this.$store.getters.username}/piggyBankState`)
        .then((response) => {
          this.isCreated = response.data.response;
        });
      await this.$axios
        .get(`/vcards/${this.$store.getters.username}`)
        .then((response) => {
          this.balance = response.data.data.balance;
        });
    },
  },
};
</script>

<style>
.loading_vcard {
  margin: 50% auto auto auto;
  width: 150px;
  height: 150px;
}

.space {
  padding-bottom: 25%;
}
</style>