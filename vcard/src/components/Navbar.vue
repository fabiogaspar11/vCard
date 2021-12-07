<template>
    <nav
      class="
        navbar navbar-expand-md navbar-dark
        bg-dark
        sticky-top
        flex-md-nowrap
        p-0
        shadow
      "
    >
      <div class="container-fluid position-sticky">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3"
          ><img
            src="../assets/img/logo.png"
            alt=""
            width="30"
            height="24"
            class="d-inline-block align-text-top"
          />
          Vcard</a
        >
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#sidebarMenu"
          aria-controls="sidebarMenu"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-end">
          <ul class="navbar-nav">
            <li class="nav-item dropdown">
              <a
                class="nav-link dropdown-toggle"
                id="navbarDropdownMenuLink"
                role="button"
                data-bs-toggle="dropdown"
                aria-expanded="false"
              >
                <img
                  src="../assets/img/logo.png"
                  class="rounded-circle z-depth-0 avatar-img"
                  alt="avatar image"
                />
                <span class="avatar-text">{{ username }}</span>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
                aria-labelledby="navbarDropdownMenuLink"
              >
                <li>
                  <router-link
                    class="dropdown-item"
                    :class="{ active: $route.name === 'userdetails' }"
                    :to="{ name: 'userdetails' }"
                    ><i class="bi bi-person-square"></i>
                    User Details
                  </router-link>
                </li>
                <li>
                  <router-link
                    class="dropdown-item"
                    :class="{ active: $route.name === 'deleteVcard' }"
                    :to="{ name: 'deleteVcard' }"
                    ><i class="bi bi-trash-fill"></i>
                    Delete Vcard account
                  </router-link>
                </li>
                <li>
                  <hr class="dropdown-divider" />
                </li>
                <li>
                  <a class="dropdown-item" @click.prevent="logout"
                    ><i class="bi bi-arrow-right"></i>Logout</a
                  >
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>
</template>

<script>
import axios from "axios";

export default {
  name: "Navbar",
  data() {
    return {
      username: this.$store.getters.username || null,
      password: "",
    };
  },
  components: {},
  methods: {
    logout() { 
      this.$store
        .dispatch("authLogout")
        .then(() => {
          localStorage.removeItem("access_token");
          localStorage.removeItem("username");
          this.$socket.emit('logged_out', this.username);
          this.$toast.success('User has logged out of the application.');
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

<style lang="scss">
@import "../assets/css/dashboard.css";

.container-fluid {
  padding-left: 0px;
}
.avatar-img {
  //margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 2.5rem;
  height: 2.5rem;
  // border-width: 1px;
  // border-color: rgb(33, 37, 41);
  // border-style: solid;
}
.avatar-text {
  line-height: 2.2rem;
  margin: 1rem 0.5rem -2rem 0;
  padding-top: 1rem;
}

.dropdown-item {
  font-size: 0.875rem;
  cursor: pointer;
}
</style>
