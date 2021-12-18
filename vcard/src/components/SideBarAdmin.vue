<template>
  <Navbar></Navbar>
  <nav
    id="sidebarMenu"
    class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
  >
    <div class="position-sticky pt-3">
      <ul class="nav flex-column">
        <li class="nav-item">
          <a
            class="btn btn-light"
            role="button"
            aria-pressed="true"
            @click="$router.push({ name: 'dashboardAdmin' })"
          >
            Administrator Dashboard
          </a>
        </li>
        <li class="nav-item">
          <a
            class="btn btn-light"
            role="button"
            aria-pressed="true"
            @click="
              $router.push({
                name: 'transaction',
                params: { isAdmin: user_type == 'A' },
              })
            "
          >
            Credit Transactions
          </a>
        </li>
        <li class="nav-item">
          <a
            class="btn btn-light"
            role="button"
            aria-pressed="true"
            @click="$router.push({ name: 'administrators' })"
          >
            Administrators
          </a>
        </li>
        <li class="nav-item">
          <a
            class="btn btn-light"
            role="button"
            aria-pressed="true"
            @click="$router.push({ name: 'vcards' })"
          >
            Vcard Users
          </a>
        </li>
        <li class="nav-item">
          <a
            class="btn btn-light"
            role="button"
            aria-pressed="true"
            @click="$router.push({ name: 'defaultCategories' })"
          >
            Default Categories
          </a>
        </li>
        <li class="nav-item">
          <a
            class="btn btn-light"
            role="button"
            aria-pressed="true"
            @click="$router.push({ name: 'statisticsAdmin' })"
          >
            Statistics
          </a>
        </li>
        <li class="nav-item">
          <a
            class="btn btn-light"
            role="button"
            aria-pressed="true"
            @click.prevent="logout"
            ><i class="bi bi-arrow-right"></i>Logout</a
          >
        </li>
      </ul>
    </div>
  </nav>
</template>

<script>
import axios from "axios";
import Navbar from "./Navbar.vue";
export default {
  name: "SideBarAdmin",
  components: {
    Navbar,
  },
  data() {
    return {
      user_type: this.$store.getters.userType,
    };
  },
  methods: {
    logout() {
      this.$store
        .dispatch("authLogout")
        .then(() => {
          this.$socket.emit(
            "logged_out",
            this.username,
            this.$store.getters.userType
          );
          this.$toast.success("User has logged out of the application.");
          delete axios.defaults.headers.common.Authorization;
          this.$router.push({ name: "home" });
        })
        .catch(() => {
          delete axios.defaults.headers.common.Authorization;
        });
    },
  },
};
</script>

<style>
</style>