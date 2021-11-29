<template>
  <div>
    <Sidebar></Sidebar>
    <Navbar></Navbar>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div
        class="
          d-flex
          justify-content-between
          flex-wrap flex-md-nowrap
          align-items-center
          pt-3
          pb-2
          mb-3
          border-bottom
        "
      >
        <h1 class="h2">User Details</h1>
        <div class="align">
        </div>
      </div>

      <div id="userdetails">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-4 profileDiv">
              <!--<img class="profile" :src="`${this.vcard.photo_url}`" /> -->
              <img class="profile" src="../../assets/img/avatar-none.png" />
              <button type="button" class="btn btn-secondary">
                Change Photo
              </button>
            </div>

            <div class="col-lg-8">
              <div class="row">
                <div class="col-4 details">
                  <h5>Phone number:</h5>
                </div>
                <div class="col-8">
                  <input
                    type="text"
                    class="form-control inputdetails"
                    placeholder="Phone number"
                    v-model="this.vcard.phone_number"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-4 details">
                  <h5>Name:</h5>
                </div>
                <div class="col-8">
                  <input
                    type="text"
                    class="form-control inputdetails"
                    placeholder="Name"
                    v-model="this.vcard.name"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-4 details">
                  <h5>Email:</h5>
                </div>
                <div class="col-8">
                  <input
                    type="text"
                    class="form-control inputdetails"
                    placeholder="Email"
                    v-model="this.vcard.email"
                  />
                </div>
              </div>
              <hr />
              <div class="row">
                <div class="col-4 details">
                  <h5>Confirmation Code:</h5>
                </div>
                <div class="col-4">
                  <input
                    type="text"
                    class="form-control inputdetails"
                    placeholder="Current Password"
                  />
                </div>
                <div class="col-4">
                  <input
                    type="text"
                    class="form-control inputdetails"
                    placeholder="Confirmation Code"
                  />
                </div>
              </div>
              <div class="row">
                <div class="col-4 details">
                  <h5>Password:</h5>
                </div>
                <div class="col-4">
                  <input
                    type="text"
                    class="form-control inputdetails"
                    placeholder="Current Password"
                  />
                </div>
                <div class="col-4">
                  <input
                    type="text"
                    class="form-control inputdetails"
                    placeholder="New Password"
                  />
                </div>
              </div>
              <button type="button" class="btn btn-primary saveDetails">
                Save
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script>
import Sidebar from "../../components/Sidebar.vue";
import Navbar from "../../components/Navbar.vue";

export default {
  name: "Userdetails",
  components: {
    Sidebar,
    Navbar,
  },
  data() {
    return {
      vcard: '',
      phoneNumber: localStorage.getItem("phone_number"),
    };
  },
  computed: {
    card: function () {
      return this.vcard == null ? "0.00" : this.vcard.balance;
    },
  },
  methods: {
  },
  created() {
    this.$axios
      .get(`/vcards/${this.phoneNumber}`)
      .then((response) => {
        this.vcard = response.data.data;
      })
      .catch(() => {
        this.$router.push({
          name: "dashboard",
        });
      });
  },
};
</script>
<style scoped lang="scss">
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}

.align {
  display: flex;
  justify-content: right;
}

#page {
  text-align: center;
  margin: 0 auto;
}

#userdetails {
  background: #e6e6e6;
}

.icon {
  color: white;
  font-size: 3rem;
}

.iconTitle {
  color: white;
  margin-bottom: 10%;
}

.small-box {
  border-radius: 10px;
  cursor: pointer;
  margin-bottom: 10%;
  margin-top: 10%;
}

.profile {
  border-radius: 30px;
  width: 100%;
  height: 85%;
}

.profileDiv {
  padding-top: 3%;
  padding-left: 3%;
  margin-bottom: 5%;
}

.btn-secondary {
  margin-top: 10%;
}

.details {
  margin-top: 1.5%;
}

.inputdetails {
  height: 60%;
}

.profileMoney {
  text-align: center;
}

.saveDetails {
  width: 20%;
  height: 10%;
  float: right;
  margin-bottom: 3%;
}
</style>
