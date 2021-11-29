<template>
  <Sidebar></Sidebar>
  <Navbar></Navbar>

  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="text-center mt-5">
      <h2>Create Transaction</h2>
      <div class="container">
        <div
          class="form-group"
          style="display: flex; flex-direction: column"
          v-show="loadedPaymentTypes"
        >
          <label for="selectPaymentType"><b>Payment Type</b></label>
          <select
            v-model="payment_type"
            name="payment_type_id"
            id="selectPaymentType"
            class="form-select"
          >
            <option
              v-for="payment in payment_types"
              :key="payment.code"
              :value="payment.code"
            >
              {{ payment.name }}
            </option>
          </select>
          <div v-show="errors.payment_type != null" class="text-danger">
            {{ errors.payment_type }}
          </div>
        </div>
        <br />
        <label for="payment_ref"><b>Payment reference: </b></label>
        <input
          type="text"
          v-model="payment_reference"
          placeholder="Enter Payment reference"
          name="payment_ref"
          required
        />
        <div v-show="errors.payment_reference != undefined" class="text-danger">
          {{ errors.payment_reference }}
        </div>
        <div v-show="errors.pair_vcard != undefined" class="text-danger">
          {{ errors.pair_vcard }}
        </div>
        <label for="value"><b>Value: </b></label>
        <input
          type="number"
          v-model="value"
          class="form-control"
          placeholder="Enter Value"
          name="value"
          required
        />
        <div v-show="errors.value != undefined" class="text-danger">
          {{ errors.value }}
        </div>
        <TransactionCreateEdit
          @updateCategory="updateCategory"
          @updateDescription="updateDescription"
        ></TransactionCreateEdit>
        <div class="container">
          <br />
          <button
            id="buttonRegister"
            class="btn btn-success"
            type="submit"
            @click.prevent="transactionCreate"
          >
            Confirm
          </button>
          <button
            id="buttonRegister"
            class="btn btn-danger"
            @click="$router.push({ name: 'dashboard' })"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
  </main>
</template >

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
  data() {
    return {
      phoneNumberPairVcard: null,
      value: null,
      errors: [],
      payment_types: [],
      loadedPaymentTypes: false,
      payment_type: null,
      phoneNumber: localStorage.getItem("phone_number"),
      payment_reference: null,
      category: null,
      description: null,
    };
  },
  methods: {
    updateCategory(category) {
      this.category = category;
    },
    updateDescription(description) {
      this.description = description;
    },
    fillTransaction() {
      let transaction = {};
      transaction.vcard = parseInt(this.phoneNumber);
      transaction.type = "D";
      if (this.payment_type != null) {
        transaction.payment_type = this.payment_type;
      }
      if (this.payment_reference != null) {
        transaction.payment_reference = this.payment_reference;
        if (this.payment_type == "VCARD") {
          transaction.pair_vcard = this.payment_reference;
        }
      }
      if (this.value != null) {
        transaction.value = this.value;
      }

      if (this.category != null) {
        transaction.category_id = this.category;
      }
      if (this.description != null) {
        transaction.description = this.description;
      }
      if (this.value != null) {
        transaction.value = this.value;
      }
      return transaction;
    },

    transactionCreate() {
      let transaction = this.fillTransaction();

      this.$axios
        .post(`/transactions`, transaction)
        .then(() => {
          transaction = null;
          this.$router.push({
            name: "dashboard",
            params: {
              successMessage:
                "Transaction created successfully (Click me to close)",
            },
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
  created() {
    this.$axios
      .get(`/paymentTypes`)
      .then((response) => {
        this.payment_types = response.data.data;
        this.loadedPaymentTypes = true;
      })
      .catch((error) => {
        this.errors.payment_type = error.response.data;
        this.loadedPaymentTypes = false;
      });
  },
};
</script>

<style scoped>
</style>