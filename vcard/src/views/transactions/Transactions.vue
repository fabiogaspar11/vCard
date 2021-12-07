<template>
  <Sidebar></Sidebar>
  <Navbar></Navbar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
 
      <h2>Transactions</h2>
   <div class="container d-flex flex-column">
      <h6 class="w-25">Filter by:</h6>
      <div class="container d-flex justify-content-between align-items-center" >
            <div class="w-50 m-2">
                <label>Begin Date</label>
                <input type="date"  class="form-control" v-model="beginDate">
            </div>
             <div class="w-50 m-2">
                <label>End Date</label>
                <input type="date"  class="form-control"  v-model="endDate">
            </div>
      </div>
      <div class="container d-flex justify-content-between align-items-center">
            <div class="w-100 m-2">
                <label>Credit Or Debit</label>  
                <select v-model="typeFilter"  class="form-select" >
                <option value="C" >Credit</option>
                <option value="D" >Not Debit</option>
                </select>  
                <!--errors-->     
            </div>
      </div>
      <h6 class="w-25">Order by:</h6>
      <div class="container d-flex justify-content-between align-items-center" >
        <div class="d-flex justify-content-center  align-items-center">    
            <label class="m-1">Amount (ASC)</label>   
            <input class="m-1" type="checkbox" v-model="amount">
        </div>
         <div class="d-flex justify-content-center  align-items-center" >    
            <label class="m-1">Most Recent</label>   
            <input class="m-1" type="checkbox" v-model="mostRecent">
        </div>
        <div class="text-end m-1">
          <a type="submit" class="btn btn-info">
            <i class="bi bi-search" style="color:white;margin-right:25%" @click="submitFilterOrderBy"></i>
          </a>
        </div>
      </div>
      <div v-show="errors.filterOrderBy != undefined" class="text-danger">
      {{ errors.filterOrderBy }}
    </div>
    </div>
      <div style="text-align:center;">
        <nav aria-label="Page navigation example" >
          <ul class="pagination">
            <li class="page-item "   @click.prevent="getPreviousPage()"><a class="page-link" href="#">Previous</a></li>
            <li v-for="page in totalPages()" :key="page" @click.prevent="getDataPage(page)" class="page-item" v-bind:class="isActive(page)"><a class="page-link" href="#">{{page}}</a></li>
            <li class="page-item" @click.prevent="getNextPage()"><a class="page-link" href="#">Next</a></li>
          </ul>
        </nav>
      </div>  
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
      pageActual: 1,
      typeFilter:null,
      beginDate:null,
      endDate:null,
      amount:false,
      mostRecent:false,
      errors:{}
    };
  },
  methods: {
    submitFilterOrderBy(){ 
      this.errors={};
      let queryString= "?";
      let map = new Map();
      if(this.beginDate != null){
        map.set("beginDate",this.beginDate)
      }
      if(this.endDate != null){
        map.set("endDate",this.endDate)
      }
      if(this.typeFilter != null){
        map.set("transactionType",this.typeFilter)
      }
      if(this.amount){
        map.set("orderByAmount",this.amount)
      }
      if(this.mostRecent){
        map.set("orderByMostRecent",this.mostRecent)
      }

    map.forEach ((value, key) =>
    { 
        queryString += key + "=" + value + "&"
    })
     queryString = queryString.substring(0, queryString.length - 1);
      
      this.transactions = this.$axios
      .get(`/vcards/${this.phoneNumber}/transactions${queryString}`)
      .then(response =>{
      this.transactions = response.data.data; 
      this.loaded = true;
      this.mostRecent = null;
      this.amount = null;
      this.beginDate = null;
      this.endDate = null;
      this.typeFilter = null;
      this.errors={};
      }).catch((error) => {
         this.errors ={};
          Object.entries(error.response.data.errors).forEach(([key, val]) => {
            this.errors[key] = val[0];
          });
      });
    },
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