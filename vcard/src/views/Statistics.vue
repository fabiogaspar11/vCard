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
        <h1 class="h2">Statistics</h1>
        
        
      </div>
      <div class="chartLine">     
        <!-- <line-chart :chartdata="chartData" :options="chartOptions"/>  -->
        <pie-chart :data="showData"></pie-chart>
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
  data() {
    return {
      transactions: null,
      phoneNumber : localStorage.getItem('username'),
      data: [
        
      ],
      showData: null,
    };
  },
  mounted() {
    /*this.$axios
    .get(`/vcards/${this.phoneNumber}/transactions`)
    .then(response =>{
      this.transactions = response.data.data;
      this.transactions.filter((row) => {
        //console.log([`${row.id}`, parseInt(row.value)])
        this.data.push([`${row.payment_type}`, parseInt(row.value)])
        return [`${row.id}`, parseInt(row.value)]
      })

      this.showData = this.data
      console.log(this.showData)
    });*/

    this.$axios.get(`/vcards/${this.phoneNumber}/transactionsPaymentType`)
    .then(response =>{
      this.transactions = response.data;
      this.transactions.filter((row) => {
        this.data.push([`${row.payment_type}`, parseInt(row.count)])
        return [`${row.id}`, parseInt(row.count)]
      })

      this.showData = this.data
      console.log(this.showData)
    })


  },
};
</script>


<style>
.chartLine{
  margin: auto;
  height: auto;
  width: 50%;
}

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
