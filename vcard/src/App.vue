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
    const username = localStorage.getItem("username");
    const userType = localStorage.getItem("user_type");

    if (token) {
      axios.defaults.headers.common["Authorization"] = "Bearer " + token;
      this.$socket.emit("logged_in", username, userType);
      this.$store.dispatch("fillStore").then(() => {
        this.$router.push({
          name: "dashboard",
        });
      });
    }
  },
  sockets: {
    changesListOfAdmins(params) {
      this.$store.commit("toggleChangesListOfAdmins", false);
      this.$store.commit("toggleChangesListOfAdmins", true);

      if (params[0] == "delete") {
        this.$toast.show(
          `This user ${params[1]} was deleted by an administrator`,
          {
            type: "info",
            timeout: 3000,
          }
        );
        if (params[1] == this.$store.getters.username) {
          this.$store.dispatch("logOutDeleteUser").then(() => {
            localStorage.removeItem("access_token");
            localStorage.removeItem("username");
            localStorage.removeItem("user_type");
            this.$socket.emit(
              "logged_out",
              params[1],
              this.$store.getters.userType
            );
            this.$toast.success("User has logged out of the application.");
            delete axios.defaults.headers.common.Authorization;
            this.$router.push({ name: "home" });
          });
        }
      }
    },
    changesListOfVcards() {
      this.$store.commit("toggleChangesListOfVcards", false);
      this.$store.commit("toggleChangesListOfVcards", true);
    },
    changesAdminDefaultCategories() {
      this.$store.commit("toggleChangesAdminDefaultCategories", false);
      this.$store.commit("toggleChangesAdminDefaultCategories", true);
    },
    newTransaction(params) {
      this.$store.commit("toggleNewTransacion", false);
      let transaction = params[0];
      let origin =
        transaction.type == "D"
          ? transaction.vcard
          : transaction.payment_reference;
      this.$toast.show(
        `Money was transfer to this account! [Amount: ${transaction.value} | From: ${origin}]`,
        { type: "success", timeout: 3000 }
      );
      this.$store.commit("toggleNewTransacion", true);
    },
    toggleVcardStatus(params) {
      this.$store.commit("toggleVcardStatus", false);
      let state = params[0] ? "blocked" : "unblocked";
      this.$toast.show(`This vcard account was ${state}`, {
        type: "info",
        timeout: 3000,
      });
      this.$store.commit("toggleVcardStatus", true);
      if (params[0]) {
        this.$store.dispatch("logOutDeleteUser").then(() => {
          localStorage.removeItem("access_token");
          localStorage.removeItem("username");
          localStorage.removeItem("user_type");

          this.$socket.emit(
            "logged_out",
            params[1],
            this.$store.getters.userType
          );
          this.$toast.success("User has logged out of the application.");
          delete axios.defaults.headers.common.Authorization;
          this.$router.push({ name: "home" });
        });
      }
    },
    userDeleted(username) {
      this.$toast.show(
        `This user ${username} was deleted by an administrator`,
        { type: "info", timeout: 3000 }
      );
      this.$store.dispatch("logOutDeleteUser").then(() => {
        localStorage.removeItem("access_token");
        localStorage.removeItem("username");
        localStorage.removeItem("user_type");
        this.$socket.emit("logged_out", username, this.$store.getters.userType);
        this.$toast.success("User has logged out of the application.");
        delete axios.defaults.headers.common.Authorization;
        this.$router.push({ name: "home" });
      });
    },
  },
};
</script>

<style>
</style>