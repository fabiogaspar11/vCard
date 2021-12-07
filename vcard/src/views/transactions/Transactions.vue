<template>
  <Sidebar></Sidebar>
  <Navbar></Navbar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
 
      <h1>Transactions</h1>

      <div style="text-align:center;">
        <nav aria-label="Page navigation example" >
          <ul class="pagination">
            <li class="page-item "   @click.prevent="getPreviousPage()"><a class="page-link" href="#">Previous</a></li>
            <li v-for="page in totalPages()" :key="page" @click.prevent="getDataPage(page)" class="page-item" v-bind:class="isActive(page)"><a class="page-link" href="#">{{page}}</a></li>
            <li class="page-item" @click.prevent="getNextPage()"><a class="page-link" href="#">Next</a></li>
          </ul>
        </nav>
      </div>  
      <table class="table">
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
          <tr v-for="transaction in dataPage" :key="transaction.id">
            <td>{{ transaction.datetime }}</td>
            <td>{{ transaction.type }}</td>
            <td>{{ transaction.value }}</td>
            <td>{{ transaction.payment_type }}</td>
            <td>{{ transaction.payment_reference }}</td>
            <td>
              <div class="container">
                <a class="btn btn-info m-1" role="button" aria-pressed="true" @click="$router.push({name:'transactionDetails', params: {id: parseInt(transaction.id)}})">
                    <i class="bi bi-arrows-fullscreen" style="color:white;margin-right:25%"></i>
                </a>
                <a class="btn btn-primary m-1" role="button" aria-pressed="true" @click="$router.push({name:'transactionEdit', params: {transactionId: transaction.id}})">
                  <i class="bi bi-pencil-square" style="color:white;margin-right:25%"></i>
                </a>
              </div>
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
      phoneNumber : localStorage.getItem('username'),
      elementsPerPage: 10,
      dataPage: [],
      pageActual: 1
    };
  },
  methods: {
    totalPages(){
      if (!this.transactions) return
      return Math.ceil(this.transactions.length / this.elementsPerPage);
    },
    getDataPage(page){
      this.pageActual = page
      this.dataPage = []
      let begin = (page * this.elementsPerPage) - this.elementsPerPage
      let end = (page * this.elementsPerPage)
      this.dataPage = this.transactions.slice(begin, end)
    },
    getPreviousPage(){
      if (this.pageActual > 1){
        this.pageActual--;
      }
      this.getDataPage(this.pageActual)
    },
    getNextPage(){
      if (this.pageActual < this.totalPages()){
        this.pageActual++;
      }
      this.getDataPage(this.pageActual)
    },
    isActive(page){
      return page == this.pageActual ? 'active':''
    }
  },
  mounted() {
     this.$axios
      .get(`/vcards/${this.phoneNumber}/transactions`)
      .then(response =>{
        this.transactions = response.data.data; 
        this.getDataPage(1)
    });
  },
  computed: {
    newTransacion(){
      return this.$store.getters.newTransacion;
    }
  },
   watch: {
      newTransacion() {
        console.log("shit")
        if(this.$store.getters.newTransacion){
          this.$axios
          .get(`/vcards/${this.phoneNumber}/transactions`)
          .then(response =>{
          this.transactions = response.data.data; 
        });
        }
      }
   }
};
</script>

<style>
</style>