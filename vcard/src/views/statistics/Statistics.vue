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
              <h1>Transactions</h1>
              <div v-if="graph1" class="row" style="padding-top: 5%">
                <div class="col-3">
                  <button
                    type="button"
                    class="btn btn-danger"
                    @click.prevent="showChartTransactions = 0"
                  >
                    <i class="bi bi-pie-chart"></i>
                  </button>
                </div>
                <div class="col-3">
                  <button
                    type="button"
                    class="btn btn-warning"
                    @click.prevent="showChartTransactions = 1"
                  >
                    <i class="bi bi-bar-chart"></i>
                  </button>
                </div>
                <div class="col-3">
                  <button
                    type="button"
                    class="btn btn-info"
                    @click.prevent="showChartTransactions = 2"
                  >
                    <i class="bi bi-bar-chart-steps"></i>
                  </button>
                </div>
                <div class="col-3">
                  <button
                    type="button"
                    class="btn btn-success"
                    @click.prevent="showChartTransactions = 3"
                  >
                    <i class="bi bi-graph-up"></i>
                  </button>
                </div>

                <pie-chart
                  v-if="showChartTransactions == 0"
                  class="chart"
                  :data="showData"
                ></pie-chart>
                <column-chart
                  v-else-if="showChartTransactions == 1"
                  class="chart"
                  :data="showData"
                ></column-chart>
                <bar-chart
                  v-else-if="showChartTransactions == 2"
                  class="chart"
                  :data="showData"
                ></bar-chart>
                <area-chart v-else class="chart" :data="showData"></area-chart>
                <div class="row" style="padding-top: 5%">
                  <div class="col-4">
                    <button
                      type="button"
                      class="btn btn-secondary selectedButton"
                      @click.prevent="
                        changeTransactionChartType('transactionsType')
                      "
                    >
                      Type
                    </button>
                  </div>
                  <div class="col-4">
                    <button
                      type="button"
                      class="btn btn-secondary selectedButton"
                      @click.prevent="
                        changeTransactionChartType('transactionsPaymentType')
                      "
                    >
                      Payment Type
                    </button>
                  </div>
                  <div class="col-4">
                    <button
                      type="button"
                      style="font-size: 100%"
                      class="btn btn-secondary selectedButton"
                      @click.prevent="
                        changeTransactionChartType(
                          'transactionsPaymentTypeValue'
                        )
                      "
                    >
                      Payment Type Value
                    </button>
                  </div>
                </div>
              </div>
              <div v-else>
                There's no data available to create statistics about
                transactions
              </div>
            </div>
            <div class="col-2"></div>
            <div v-if="graph2" class="col-5 chartDesign">
              <h2>Categories</h2>
              <div class="row" style="padding-top: 5%">
                <div class="col-3">
                  <button
                    type="button"
                    class="btn btn-danger"
                    @click.prevent="showChartCategories = 0"
                  >
                    <i class="bi bi-pie-chart"></i>
                  </button>
                </div>
                <div class="col-3">
                  <button
                    type="button"
                    class="btn btn-warning"
                    @click.prevent="showChartCategories = 1"
                  >
                    <i class="bi bi-bar-chart"></i>
                  </button>
                </div>
                <div class="col-3">
                  <button
                    type="button"
                    class="btn btn-info"
                    @click.prevent="showChartCategories = 2"
                  >
                    <i class="bi bi-bar-chart-steps"></i>
                  </button>
                </div>
                <div class="col-3">
                  <button
                    type="button"
                    class="btn btn-success"
                    @click.prevent="showChartCategories = 3"
                  >
                    <i class="bi bi-graph-up"></i>
                  </button>
                </div>
              </div>
              <pie-chart
                v-if="showChartCategories == 0"
                class="chart"
                :data="showDataCategory"
              ></pie-chart>
              <column-chart
                v-else-if="showChartCategories == 1"
                class="chart"
                :data="showDataCategory"
              ></column-chart>
              <bar-chart
                v-else-if="showChartCategories == 2"
                class="chart"
                :data="showDataCategory"
              ></bar-chart>
              <area-chart
                v-else
                class="chart"
                :data="showDataCategory"
              ></area-chart>

              <div class="row" style="padding-top: 5%">
                <div class="col-6">
                  <button
                    type="button"
                    class="btn btn-secondary selectedButton"
                    @click.prevent="changeCategoryChartType('categoriesType')"
                  >
                    Type
                  </button>
                </div>
                <div class="col-6">
                  <button
                    type="button"
                    class="btn btn-secondary selectedButton"
                    @click.prevent="
                      changeCategoryChartType('categoriesTransactions')
                    "
                  >
                    Transactions
                  </button>
                </div>
              </div>
            </div>
            <div v-else>
              There's no data available to create statistics about categories
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
      phoneNumber: localStorage.getItem("username"),
      data: [],
      dataCategory: [],
      showData: [],
      showDataCategory: null,
      showChartTransactions: 0,
      showChartCategories: 0,
      graph1: false,
      graph2: false,
    };
  },
  methods: {
    changeTransactionChartType: function (rowTransaction) {
      this.graph1 = false;

      this.data.splice(0);
      this.showData = null;
      this.$axios.get(`/statistics/${rowTransaction}`).then((response) => {
        if (response.data.length != 0) {
          this.transactions = response.data;
          this.transactions.filter((row) => {
            if (rowTransaction == "transactionsType")
              this.data.push([`${row.type}`, parseInt(row.count)]);
            else if (rowTransaction == "transactionsPaymentType")
              this.data.push([`${row.payment_type}`, parseInt(row.count)]);
            else this.data.push([`${row.payment_type}`, parseInt(row.Value)]);
          });
          this.showData = this.data;
          this.graph1 = true;
        }
      });
    },
    changeCategoryChartType: function (rowCategory) {
      this.graph2 = false;

      this.data.splice(0);
      this.showDataCategory = null;
      this.$axios.get(`/statistics/${rowCategory}`).then((response) => {
        if (response.data.length != 0) {
          this.categories = response.data;
          this.categories.filter((row) => {
            if (rowCategory == "categoriesType")
              this.data.push([`${row.type}`, parseInt(row.count)]);
            else this.data.push([`${row.name}`, parseInt(row.Value)]);
          });
          this.showDataCategory = this.data;
          this.graph2 = true;
        }
      });
    },
  },
  mounted() {
    this.$axios.get(`/statistics/transactionsType`).then((response) => {
      if (response.data.length != 0) {
        this.transactions = response.data;
        this.transactions.filter((row) => {
          this.data.push([`${row.type}`, parseInt(row.count)]);
        });
        this.showData = this.data;
        this.graph1 = true;
      }
    });

    this.$axios.get(`/statistics/categoriesType`).then((response) => {
      if (response.data.length != 0) {
        this.categories = response.data;
        this.categories.filter((row) => {
          this.dataCategory.push([`${row.type}`, parseInt(row.count)]);
        });
        this.showDataCategory = this.dataCategory;
        this.graph2 = true;
      }
    });
  },
};
</script>


<style scoped>
.selectedButton:focus {
  border: 1px black;
  background-color: black;
}
.statistics {
  text-align: center;
}
.chart {
  text-align: center;
}
.chartLine {
  margin: auto;
  height: auto;
  width: 80%;
}
.chartDesign {
  padding-top: 2%;
  background-image: #94b0b7;
  border-radius: 20px;
}
</style>
