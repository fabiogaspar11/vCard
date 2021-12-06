<template>
  <div>
    <router-link to="/"> </router-link>
    <router-view />
  </div>
</template>

<script>
import axios from "axios";

export default {
  mounted() {
    const token = localStorage.getItem("access_token");

    if (token) {
      axios.defaults.headers.common["Authorization"] = "Bearer " + token;
      this.$store.dispatch("changeState").then(() => {
        this.$router.push({
          name: "dashboard",
        });
      });
    }
  },
    sockets: {
    newTransaction (params) {
    this.$store.commit('toggleNewTransacion', false)
    let transaction = params[0];
    let origin = transaction.type == 'D' ? transaction.vcard : transaction.payment_reference;
    this.$toast.show(`Money was transfer to this account! [Amount: ${transaction.value} | From: ${origin}]`, {type:"success",timeout:3000})
    this.$store.commit('toggleNewTransacion', true)
 }
 }
};
</script>

<style>
</style>