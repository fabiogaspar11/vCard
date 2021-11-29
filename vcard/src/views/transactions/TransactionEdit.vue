<template>
    <Sidebar></Sidebar>
    <Navbar></Navbar>
    <div class="text-center mt-5">
      <h2>Edit Transaction Nº {{transactionId}}</h2>
      <div class="container">
       <TransactionCreateEdit @updateCategory="updateCategory" @updateDescription="updateDescription"></TransactionCreateEdit>
        <div class="container">
          <br>
          <button
            id="buttonRegister"
            class="btn btn-success"
            type="submit"
            @click.prevent="transactionEdit"
          >
            Confirm
          </button>
          <button
            id="buttonRegister"
            class="btn btn-danger"
            @click="$router.push({ name: 'transactions' })"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
</template>

<script>
import Navbar from "../../components/Navbar.vue";
import Sidebar from "../../components/Sidebar.vue";
import TransactionCreateEdit from "../transactions/TransactionCreateEdit.vue"
export default {
  name: "TransactionCreate",
  components: {
    Sidebar,
    Navbar,
    TransactionCreateEdit
  },
  props:{
    transactionId: Number
  },
  data() {
    return {
      errors: [],
      category: null,
      description: null
    };
  },
  methods: {
    updateCategory(category){
        this.category = category;
    },
    updateDescription(description){
        this.description = description;
    },
    fillTransaction(){
      let transaction = {};
      if (this.category != null) {
        transaction.category_id = this.category;
      }
      if (this.description != null) {
        transaction.description = this.description;
      }

      return transaction;
    },
    transactionEdit() {
     let transaction = this.fillTransaction();

      this.$axios
        .put(`transactions/${this.transactionId}`, transaction)
        .then(() => {
          transaction = null;
          this.$router.push({
            name: "transactions"
          });
        })
        .catch((error) => {
          this.errors = [];
          Object.entries(error.response.data.errors).forEach(([key, val]) => {
            this.errors[key] = val[0];
          });
        });
    },
  },
};
</script>

<style scoped>
</style>