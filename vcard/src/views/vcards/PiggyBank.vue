<template>
  <Navbar></Navbar>
  <Sidebar></Sidebar>

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
        <h1 class="h2">Piggy Bank</h1>
        <div class="align">
          <div
            @click="closeSuccessMesage"
            v-if="showMessage"
            class="alert alert-success alert-dismissible"
          >
            {{ successMessage }}
          </div>
        </div>
      </div>


    <br />
    <div v-if="this.loaded">
      <div id="page">
        <div>
          <img class="center" src="../../assets/img/piggyBank.png" />       
          <h3 v-if="this.isCreated">{{ this.piggyBalance }} €</h3>
        </div>
      </div>     
      <div class="details" v-if="this.isCreated" style="width: 50%; text-align:center; margin: auto"> 
        <label style="padding-top: 2%;"><b>Value:</b></label>
        <br />
        <div v-if="this.piggyBalance > 0 || this.balance > 0">
          <div class="container-fluid">
            <div class="row">
              <div class="col-2 chartDesign">
                <i class="bi bi-piggy-bank-fill" style="font-size: 300%;"></i>
              </div>
              <div class="col-10 chartDesign">
                  <input type="text" class="form-control" placeholder="Enter value" name="uname" v-model="this.amount" pattern="[0-9]+" required />
              </div>
            </div>
          </div>
        </div>
        <br />
      <div id="value" v-show="errors.amount != undefined" class="text-danger">
        {{ errors.amount }}
      </div>
        <div class="container-fluid">
          <div class="row">
            <div class="col-6 ">
              <div v-if="this.balance > 0">
                <button class="btn btn-success" type="submit" @click.prevent="send_takeMoney('C')">
                  Insert Money
                </button>
              </div>
              <div id="noBalance" v-else>
                <i class="bi bi-x-circle icon_error"></i>
                <h5>No balance to save in the piggy bank</h5>
              </div>
            </div>
            <div class="col-6">
              <div v-if="this.piggyBalance > 0" class="space">
                <button class="btn btn-danger" type="submit" @click.prevent="send_takeMoney('D')">
                  Take Money
                </button>
              </div>
              <div id="noPiggyBankBalance" v-else>
                <i class="bi bi-x-circle icon_error"></i>
                <h5>No money in the piggy bank to take</h5>
              </div>
            </div>
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
      <br />
    </div>
    <div v-else style="text-align:center">
      <div
        class="spinner-border text-primary loading_vcard"
        role="status"
        v-show="!this.loaded"
      ></div>
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
    Sidebar
  },
  data() {
    return {
      isCreated: null,
      piggyBalance: null,
      loaded: false,
      balance: null,
      amount: null,
      successMessage: null,
      showMessage: this.successMessage != null ? true : false,
      errors: [],
      header: {
        "Content-Type": "multipart/form-data",
        Accept: "application/json",
        Authorization: "Bearer " + localStorage.getItem("access_token"),
      },
      phoneNumber: this.$store.getters.username
    };
  },
  async created() {
    await this.$axios
      .get(`/vcards/${this.phoneNumber}/piggyBankState`)
      .then((response) => {
        this.isCreated = response.data.response;
        if (this.isCreated) {
          this.$axios
            .get(
              `/vcards/${this.phoneNumber}/getPiggyBankBalance`
            )
            .then((response) => {
              this.piggyBalance = response.data.balance;
            });
          this.$axios
            .get(`/vcards/${this.phoneNumber}`)
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
    closeSuccessMesage: function () {
      this.showMessage = false;
    },
    async createPiggyBank() {
      await this.$axios
        .get(`/vcards/${this.phoneNumber}/createPiggyBank`)
        .then((response) => {
          this.piggyBalance = response.data;
        });
      await this.$axios
        .get(`/vcards/${this.phoneNumber}/piggyBankState`)
        .then((response) => {
          this.isCreated = response.data.response;
        });
      await this.$axios
        .get(`/vcards/${this.phoneNumber}`)
        .then((response) => {
          this.balance = response.data.data.balance;
        });
    },

    async send_takeMoney(type) {
      this.errors = [];
      let moneyToSend = {};
      moneyToSend.amount = this.amount;
      moneyToSend.type = type;
      moneyToSend.balance = this.balance;

      this.$axios
        .post(
          `/vcards/${this.phoneNumber}/piggyBankOperation`,
          moneyToSend,
          this.config
        )
        .then((response) => {
          this.piggyBalance = response.data;
          this.showMessage = true;
          if (type === "C")
            this.successMessage = "Money was saved in the piggy bank";
          if (type === "D")
            this.successMessage = "Money was withdrawn from the piggy bank";
          this.$toast.success(this.successMessage);
          this.$axios
          .get(`/vcards/${this.phoneNumber}`)
          .then((response) => {
            this.balance = response.data.data.balance;
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

<style>
.loading_vcard { 
  margin-top: 20%;
  width: 250px;
  height: 250px;
}

.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 25%;
}

#page {
  text-align: center;
  margin: 0 auto;
}

.details{
  background-color: #e6e6e6;
  border-radius: 10px;
  box-shadow: 0 7px 2px -2px gray;
  padding-left: 2em;
  padding-right: 2em;
}
</style>