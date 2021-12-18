<template>
  <Sidebar></Sidebar>
  <Navbar></Navbar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="text-center mt-5">
      <h2>Edit Transaction Nº {{ transactionId }}</h2>
      <p>
        Current Description:
        {{
          this.transaction.description != null
            ? this.transaction.description
            : "---"
        }}
        | Category:
        {{
          this.transaction.category_id != null
            ? this.transaction.category_id.name
            : "---"
        }}
      </p>
      <div class="container">
        <TransactionCreateEdit
          @updateCategory="updateCategory"
          @updateDescription="updateDescription"
          :errors="errors"
          @updateConfirmationCode="updateConfirmationCode"
          :type="this.type"
        ></TransactionCreateEdit>
        <div class="container">
          <br />
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
  </main>
</template>

<script>
import Navbar from "../../components/Navbar.vue";
import Sidebar from "../../components/Sidebar.vue";
import TransactionCreateEdit from "../transactions/TransactionCreateEdit.vue";
export default {
  name: "TransactionCreate",
  components: {
    Sidebar,
    Navbar,
    TransactionCreateEdit,
  },
  props: {
    transactionId: String,
    type: String,
  },
  data() {
    return {
      errors: [],
      category: null,
      description: null,
      transaction: [],
    };
  },
  methods: {
    updateCategory(category) {
      this.errors = [];
      if (
        this.transaction.category_id != null &&
        this.transaction.category_id.id == category
      ) {
        this.errors.category_id = "Category is equal";
        return;
      }
      this.category = category;
    },
    updateDescription(description) {
      this.errors = [];
      if (
        this.transaction.description != null &&
        this.transaction.description == description
      ) {
        this.errors.description = "Description is equal";
        return;
      }
      this.description = description;
    },
    fillTransaction() {
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
            name: "transactions",
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
  async created() {
    await this.$axios
      .get(`/transactions/${parseInt(this.transactionId)}`)
      .then((response) => {
        this.transaction = response.data.data;
      });
  },
};
</script>

<style scoped>
</style>