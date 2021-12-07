<template>
  <Sidebar></Sidebar>
  <Navbar></Navbar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2>Transaction {{this.id}}</h2>

    <div class="container-md bg-light rounded" v-if="loaded">
      <div class="row text-start">
        <div class="col-sm">
          <p><b>Vcard: </b>{{ transaction.vcard }}</p>
        </div>
        <div class="col-sm">
          <p><b>Date: </b>{{ transaction.date }}</p>
        </div>
        <div class="col-sm">
          <p><b>Date and Time: </b>{{ transaction.datetime }}</p>
        </div>
      </div>
      <div class="row text-start">
        <div class="col-sm">
          <p><b>Type: </b>{{ transaction.type }}</p>
        </div>
        <div class="col-sm">
          <p><b>Payment Type: </b>{{ transaction.payment_type }}</p>
        </div>
        <div class="col-sm">
          <p><b>Old Balance: </b>{{ transaction.old_balance }}</p>
        </div>
      </div>
      <div class="row text-start">
        <div class="col-sm">
          <p><b>Value: </b>{{ transaction.value }}</p>
        </div>
        <div class="col-sm">
          <p><b>Payment reference: </b>{{ transaction.payment_reference }}</p>
        </div>
        <div class="col-sm">
          <p><b>New Balance: </b>{{ transaction.new_balance }}</p>
        </div>
      </div>

      <div class="row">
        <div v-if="transaction.category_id != null" class="col-sm">
          <p><b>Category: </b>{{ transaction.category_id.name }}</p>
        </div>
        <div v-if="transaction.description != null" class="col-sm">
          <p><b>Description: </b>{{ transaction.description }}</p>
        </div>
         <div class="col-sm">
        </div>
      </div>
    </div>
  </main>
</template>

<script>
import Sidebar from "../../components/Sidebar.vue";
import Navbar from "../../components/Navbar.vue";
export default {
  name: "Transaction",
  components: {
    Sidebar,
    Navbar,
  },
  props: {
    id: Number,
  },
  data() {
    return {
      transaction: null,
      loaded:false
    };
  },
  created() {
      this.$axios
      .get(`transactions/${this.id}`)
      .then((response) => {
        this.transaction = response.data.data;
        this.loaded = true;
      })
      .catch((error) => {
        console.log(error)
      });
  },
};
</script>

<style>
</style>