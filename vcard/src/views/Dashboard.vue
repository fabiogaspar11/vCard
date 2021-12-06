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
        <h1 class="h2">Dashboard</h1>
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
      <div id="page" class="row">
        <div id="dashboard">
          <img class="center" src="../assets/img/logo.png" />
          <h1 style="margin-bottom: 3%">{{ moneyUser }} €</h1>
        </div>

        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-6">
              <div
                class="small-box buttonUserDetails"
                @click="$router.push({ name: 'userdetails' })"
              >
                <i class="bi bi-person-bounding-box icon"></i>
                <h4><i class="iconTitle"> User Details </i></h4>
                <br />
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div
                class="small-box buttonSendReceive"
                @click="$router.push({ name: 'transaction' })"
              >
                <i class="bi bi-cash-coin icon"></i>
                <h4><i class="iconTitle"> Create a Transaction </i></h4>
                <br />
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div
                class="small-box buttonTransactions"
                @click="$router.push({ name: 'transactions' })"
              >
                <i class="bi bi-list-columns icon" style="color: white"></i>
                <h4><i class="iconTitle"> Transactions </i></h4>
                <br />
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box buttonPiggyBank">
                <i class="bi bi-piggy-bank icon"> </i>
                <h4 class="title"><i class="iconTitle"> Piggy Bank</i></h4>
                <br />
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script>
import Sidebar from "../components/Sidebar.vue";
import Navbar from "../components/Navbar.vue";

export default {
  name: "Dashboard",
  components: {
    Sidebar,
    Navbar,
  },

  props: {
    successMessage: null,
  },

  data() {
    return {
      //money: 0,
      showMessage: this.successMessage != null ? true : false,
      vcard: '',
      phoneNumber: localStorage.getItem("username"),
    };
  },
  computed: {
    moneyUser: function () {
      return this.vcard == null ? "0.00" : this.vcard.balance;
    },
    newTransacion(){
      return this.$store.getters.newTransacion;
    }
  },
  methods: {
    closeSuccessMesage: function () {
      this.showMessage = false;
    },
  },
  watch: {
  newTransacion() {
    if(this.$store.getters.newTransacion){
      this.$axios.get(`/vcards/${this.phoneNumber}`)
      .then(response =>{
        this.vcard = response.data.data
      });
    }
  }
},
  created(){
   this.$axios.get(`/vcards/${this.phoneNumber}`)
    .then(response =>{
      this.vcard = response.data.data
    })
    .catch(() => {
      this.$router.push({
        name: "dashboardAdmin",
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

#dashboard {
  background: #e6e6e6;
  padding-top: 3%;
}

.icon {
  color: white;
  font-size: 3rem;
}

.iconTitle {
  color: white;
  margin-bottom: 10%;
}

.buttonUserDetails {
  background: #17a2b8;
}
.buttonUserDetails:hover {
  background: #0e6471;
  transition-duration: 0.5s;
}

.buttonSendReceive {
  background: #198754;
}
.buttonSendReceive:hover {
  background: #105635;
  transition-duration: 0.5s;
}

.buttonTransactions {
  background: #ffc107;
}
.buttonTransactions:hover {
  background: #b38600;
  transition-duration: 0.5s;
}

.buttonPiggyBank {
  background: #dc3545;
}
.buttonPiggyBank:hover {
  background: #981b27;
  transition-duration: 0.5s;
}

.small-box {
  border-radius: 10px;
  cursor: pointer;
  margin-bottom: 10%;
  margin-top: 10%;
}
</style>
