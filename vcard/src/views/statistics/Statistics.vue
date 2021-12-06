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
        <div class="container-fluid statistics">
          <div class="row">
            <div class="col-5 chartDesign">
              <h1> Transactions </h1>
              <div class="row" style="padding-top: 5%;">
                <div class="col-3">
                  <button type="button" class="btn btn-danger" @click.prevent="showChartTransactions = 0"><i class="bi bi-pie-chart"></i></button>
                </div>
                <div class="col-3">
                  <button type="button" class="btn btn-warning" @click.prevent="showChartTransactions = 1"><i class="bi bi-bar-chart"></i></button>
                </div>     
                <div class="col-3">
                  <button type="button" class="btn btn-info" @click.prevent="showChartTransactions = 2"><i class="bi bi-bar-chart-steps"></i></button>
                </div>
                <div class="col-3">
                  <button type="button" class="btn btn-success" @click.prevent="showChartTransactions = 3"><i class="bi bi-graph-up"></i></button>
                </div>         
              </div>
              <pie-chart v-if="showChartTransactions == 0" class="chart" :data="showData" ></pie-chart>
              <column-chart v-else-if="showChartTransactions == 1" class="chart" :data="showData" ></column-chart>
              <bar-chart v-else-if="showChartTransactions == 2" class="chart" :data="showData" ></bar-chart>
              <area-chart v-else class="chart" :data="showData" ></area-chart>

              <div class="row" style="padding-top: 5%;">
                <div class="col-4">
                  <button type="button" class="btn btn-secondary" @click.prevent="changeTransactionChartType('transactionsType')">Type</button>
                </div>
                <div class="col-4">
                  <button type="button" class="btn btn-secondary" @click.prevent="changeTransactionChartType('transactionsPaymentType')">Payment Type</button>
                </div>        
                <div class="col-4">
                  <button type="button" style="font-size: 100%;" class="btn btn-secondary" @click.prevent="changeTransactionChartType('transactionsPaymentTypeValue')">Payment Type Value</button>
                </div>   
              </div>
            </div>
            <div class="col-2">
           
            </div>
            <div class="col-5 chartDesign">
              <h1> Categories </h1>
              <div class="row" style="padding-top: 5%;">
                <div class="col-3">
                  <button type="button" class="btn btn-danger" @click.prevent="showChartCategories = 0"><i class="bi bi-pie-chart"></i></button>
                </div>
                <div class="col-3">
                  <button type="button" class="btn btn-warning" @click.prevent="showChartCategories = 1"><i class="bi bi-bar-chart"></i></button>
                </div>     
                <div class="col-3">
                  <button type="button" class="btn btn-info" @click.prevent="showChartCategories = 2"><i class="bi bi-bar-chart-steps"></i></button>
                </div>
                <div class="col-3">
                  <button type="button" class="btn btn-success" @click.prevent="showChartCategories = 3"><i class="bi bi-graph-up"></i></button>
                </div>         
              </div>
              <pie-chart v-if="showChartCategories == 0" class="chart" :data="showDataCategory" ></pie-chart>
              <column-chart v-else-if="showChartCategories == 1" class="chart" :data="showDataCategory" ></column-chart>
              <bar-chart v-else-if="showChartCategories == 2" class="chart" :data="showDataCategory" ></bar-chart>
              <area-chart v-else class="chart" :data="showDataCategory" ></area-chart>
              
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
  name: "Dashboard",
  components: {
    Sidebar,
    Navbar,
  },
  data() {
    return {
      transactions: null,
      categories: null,
      phoneNumber : localStorage.getItem('username'),
      data: [],
      dataCategory: [],
      showData: null,
      showDataCategory: null,
      showChartTransactions: 0,
      showChartCategories: 0,
    };
  },
  methods: {
    changeTransactionChartType: function (rowTransaction) {
      this.data.splice(0);
      this.showData = null
      this.$axios.get(`/vcards/${this.phoneNumber}/${rowTransaction}`)
      .then(response =>{
        this.transactions = response.data;
        this.transactions.filter((row) => {
          if (rowTransaction == 'transactionsType') this.data.push([`${row.type}`, parseInt(row.count)])
          else if (rowTransaction == 'transactionsPaymentType') this.data.push([`${row.type}`, parseInt(row.count)])
          else this.data.push([`${row.payment_type}`, parseInt(row.Value)])         
        })
        this.showData = this.data
        console.log(this.showData)
      })      
    },
  },
  mounted() {   
    this.$axios.get(`/vcards/${this.phoneNumber}/transactionsType`)
    .then(response =>{
      this.transactions = response.data;
      this.transactions.filter((row) => {
        this.data.push([`${row.type}`, parseInt(row.count)])
        return [`${row.id}`, parseInt(row.count)]
      })

      this.showData = this.data
      console.log(this.showDataType)
    })
    this.$axios.get(`/vcards/${this.phoneNumber}/categoriesType`)
    .then(response =>{
      this.categories = response.data;
      this.categories.filter((row) => {
        this.dataCategory.push([`${row.type}`, parseInt(row.count)])
        return [`${row.id}`, parseInt(row.count)]
      })

      this.showDataCategory = this.dataCategory
      console.log(this.showDataCategory)
    })


  },
};
</script>


<style>
.statistics{
  text-align: center;
}
.chart{
  text-align: center;
}
.chartLine{
  margin: auto;
  height: auto;
  width: 80%;
  
}
.chartDesign{
  padding-top: 2%;
  background-image: linear-gradient(to bottom right, white, #94b0b7);
  border-radius: 20px;
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
