<template>
  <Sidebar></Sidebar>
  <Navbar></Navbar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2>Transactions</h2>
    <div class="container d-flex flex-column">
      <h6 class="w-25">Filter by:</h6>
      <div class="container d-flex justify-content-between align-items-center">
        <div class="w-50 m-2">
          <label>Begin Date</label>
          <input type="date" class="form-control" v-model="beginDate" />
        </div>
        <div class="w-50 m-2">
          <label>End Date</label>
          <input type="date" class="form-control" v-model="endDate" />
        </div>
      </div>
      <div class="container d-flex justify-content-between align-items-center">
        <div class="w-100 m-2">
          <label>Credit Or Debit</label>
          <select v-model="typeFilter" class="form-select">
            <option value="C">Credit</option>
            <option value="D">Debit</option>
          </select>
          <!--errors-->
        </div>
      </div>
      <h6 class="w-25">Order by:</h6>
      <div class="container d-flex justify-content-between align-items-center">
        <div class="d-flex justify-content-center align-items-center">
          <label class="m-1">Amount (ASC)</label>
          <input class="m-1" type="checkbox" v-model="amount" />
        </div>
        <div class="d-flex justify-content-center align-items-center">
          <label class="m-1">Most Recent</label>
          <input class="m-1" type="checkbox" v-model="mostRecent" />
        </div>
        
        <div class="text-end m-1">
          <a type="submit" class="btn btn-info" @click="submitFilterOrderBy">
            <i
              class="bi bi-search"
              style="color: white; margin-right: 25%"
            ></i>
          </a>
        </div>
        <div class="text-end m-1">
          <a type="submit" class="btn btn-secondary" @click="clearFilters">
            Clear
          </a>
        </div>
      </div>
      <div v-show="errors.filterOrderBy != undefined" class="text-danger">
        {{ errors.filterOrderBy }}
      </div>
    </div>
    <nav aria-label="Page navigation example">
      <ul class="pagination">
        <li class="page-item"><a class="page-link" href="#" @click.prevent="getPreviousPage()">Previous</a></li>
        <li class="page-item"><a class="page-link" href="#">{{ this.pageActual }}</a></li>
        <li class="page-item"><a class="page-link" href="#" @click.prevent="getNextPage()">Next</a></li>
      </ul>
    </nav>

    <table class="table color">
      <thead>
        <tr>
          <th>Date/Time</th>
          <th>Type</th>
          <th>Value</th>
          <th>Payment type</th>
          <th>Payment reference</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="transaction in transactions" :key="transaction.id">
          <td>{{ transaction.datetime }}</td>
          <td>{{ transaction.type }}</td>
          <td>{{ transaction.value }}</td>
          <td>{{ transaction.payment_type }}</td>
          <td>{{ transaction.payment_reference }}</td>
          <td>
            <a
              class="btn btn-info m-1"
              role="button"
              aria-pressed="true"
              @click="
                $router.push({
                  name: 'transactionDetails',
                  params: { id: parseInt(transaction.id) },
                })
              "
            >
              <i
                class="bi bi-arrows-fullscreen"
                style="color: white; margin-right: 25%"
              ></i>
            </a>
            <a
              class="btn btn-primary m-1"
              role="button"
              aria-pressed="true"
              @click="
                $router.push({
                  name: 'transactionEdit',
                  params: { transactionId: transaction.id },
                })
              "
            >
              <i
                class="bi bi-pencil-square"
                style="color: white; margin-right: 25%"
              ></i>
            </a>
          </td>
        </tr>
      </tbody>
    </table>
  </main>
</template>

<script>
import Sidebar from "../../components/Sidebar.vue";
import Navbar from "../../components/Navbar.vue";
export default {
  name: "Transactions",
  components: {
    Sidebar,
    Navbar,
  },
  data() {
    return {
      transactions: null,
      phoneNumber: localStorage.getItem("username"),
      pageActual: 1,
      lastPage: null,
      typeFilter: null,
      amount: false,
      mostRecent: false,
      $queryString: null,
      errors: {},
    };
  },
  methods: {
    submitFilterOrderBy() {
      this.pageActual = 1;
      this.getTransactionsWithFilter();
    },
    getPreviousPage(){
      if (this.pageActual > 1){
        this.pageActual--;
        if (this.queryString != null){
          this.getTransactionsWithFilter()
        }
        else{
          this.getTransactions()
        }

      }
    },
    getNextPage(){
      if (this.pageActual < this.lastPage){
        this.pageActual++;
         if (this.queryString != null){
          this.getTransactionsWithFilter()
        }
        else{
          this.getTransactions()
        }
      }
    },
    getTransactions(){
      this.$axios
      .get(`/vcards/${this.phoneNumber}/transactions?page=${this.pageActual}`)
      .then((response) => {
        this.transactions = response.data.data;
        this.lastPage = response.data.meta.last_page;      
        console.log(this.transactions)
      });
    },
    getTransactionsWithFilter(){
      this.errors = {};
      this.queryString = "?";

      let map = new Map();
      if (this.beginDate != null) {
        map.set("beginDate", this.beginDate);
      }
      if (this.endDate != null) {
        map.set("endDate", this.endDate);
      }
      if (this.typeFilter != null) {
        map.set("transactionType", this.typeFilter);
      }
      if (this.amount) {
        map.set("orderByAmount", this.amount);
      }
      if (this.mostRecent) {
        map.set("orderByMostRecent", this.mostRecent);
      }
      
      if (this.beginDate == null && this.endDate == null && this.typeFilter == null && !this.amount && !this.mostRecent){
        return this.getTransactions();
      }
      console.log(this.queryString)
      map.forEach((value, key) => {
        this.queryString += key + "=" + value + "&";
      });
      this.queryString = this.queryString.substring(0, this.queryString.length - 1);

      this.transactions = this.$axios
        .get(`/vcards/${this.phoneNumber}/transactions${this.queryString}&page=${this.pageActual}`)
        .then((response) => {
          this.transactions = response.data.data;
          this.loaded = true;
          this.errors = {};
          this.lastPage = response.data.meta.last_page;
        })
        .catch((error) => {
          this.errors = {};
          Object.entries(error.response.data.errors).forEach(([key, val]) => {
            this.errors[key] = val[0];
            this.queryString = null;
          });
        });
    },
    clearFilters(){
      this.typeFilter = null
      this.beginDate = null
      this.endDate = null
      this.amount = null
      this.mostRecent = null
    }
  },
  mounted() {
    this.getTransactions()
  },
  computed: {
    newTransacion() {
      return this.$store.getters.newTransacion;
    },
  },
  watch: {
    newTransacion:{
      handler() {
        if(this.$store.getters.newTransacion){
          this.$axios.get(`/vcards/${this.phoneNumber}/transactions`)
          .then(response =>{
            this.vcard = response.data.data
          });
        }
      },
      deep:true
    }
  },
};
</script>

<style>
</style>
