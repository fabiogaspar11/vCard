<template>
  <div>
    <SideBardAdmin></SideBardAdmin>

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
              <h2> Transactions </h2>
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
              <h2> Categories </h2>
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
import SideBardAdmin from "../../components/SideBarAdmin.vue";

export default {
  name: "Dashboard",
  components: {
    SideBardAdmin,
  },
  data() {
    return {
      transactions: null,
      categories: null,
      administrator : 5, //localStorage.getItem('username'),
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
      this.$axios.get(`/statistics/${rowTransaction}`)
      .then(response =>{
        this.transactions = response.data;
        this.transactions.filter((row) => {
          if (rowTransaction == 'transactionsType') this.data.push([`${row.type}`, parseInt(row.count)])
          else if (rowTransaction == 'transactionsPaymentType') this.data.push([`${row.payment_type}`, parseInt(row.count)])
          else this.data.push([`${row.payment_type}`, parseInt(row.Value)])         
        })
        this.showData = this.data
        console.log(this.showData)
      })      
    },
  },
  mounted() {   
    console.log(this.administrator)
    this.$axios.get(`/statistics/transactionsType`)
    .then(response =>{
      this.transactions = response.data;
      this.transactions.filter((row) => {
        this.data.push([`${row.type}`, parseInt(row.count)])
        return [`${row.id}`, parseInt(row.count)]
      })

      this.showData = this.data
      console.log(this.showDataType)
    })
    this.$axios.get(`/statistics/categoriesType`)
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


<style scoped>
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

</style>
