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
};
</script>

<style>
</style>