<template>
  <Sidebar></Sidebar>
  <Navbar></Navbar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
 
      <h1>Transactions</h1>
      <table class="table">
        <thead>
          <tr>
            <th>Date/Time</th>
            <th>Type</th>
            <th>Value</th>
            <th>Payment type</th>
            <th>Payment reference</th>
            <th>Details</th>
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
              <div class="container">
                <a class="btn btn-info m-1" role="button" aria-pressed="true" @click="$router.push({name:'transactionDetails', params: {id: transaction.id}})">
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
      phoneNumber : localStorage.getItem('phone_number')
    };
  },
  mounted() {
     this.$axios
      .get(`/vcards/${this.phoneNumber}/transactions`)
      .then(response =>{
      this.transactions = response.data.data; 
    });
  },
};
</script>

<style>
</style>