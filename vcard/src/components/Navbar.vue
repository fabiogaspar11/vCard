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
        <router-link to="/dashboard" class="navbar-brand col-md-3 col-lg-2 me-0 px-3"
          ><img
            src="../assets/img/logo.png"
            alt=""
            width="30"
            height="24"
            class="d-inline-block align-text-top"
          />
          Vcard</router-link
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
                <img v-if="photo!=null"
                  :src='this.photo'
                  class="rounded-circle z-depth-0 avatar-img"
                  alt="avatar image"
                />
                <img v-else 
                  class="rounded-circle z-depth-0 avatar-img"
                  src="../assets/img/avatar-none.png"
                  alt="avatar image"
                />
                <span class="avatar-text">{{ username }}</span>
              </a>
              <ul
                class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
                aria-labelledby="navbarDropdownMenuLink"
              >
                <li v-if="userType == 'V'">
                  <router-link
                    class="dropdown-item"
                    :class="{ active: $route.name === 'deleteVcard' }"
                    :to="{ name: 'deleteVcard' }"
                    ><i class="bi bi-trash-fill"></i>
                    Delete Vcard account
                  </router-link>
                </li>
                <li>
                  <hr v-if="userType == 'V'" class="dropdown-divider" />
                </li>
                 <li v-if="userType == 'V'">
                  <router-link
                    class="dropdown-item"
                    :class="{ active: $route.name === 'deleteVcard' }"
                    :to="{ name: 'changeSecretValue', params:{isUpdatePassword:1,fieldUpdate:'New Pasword'} }"
                    ><i class="bi bi-key-fill"></i>
                   Change Password
                  </router-link>
                </li>
                  <li>
                  <hr v-if="userType == 'V'" class="dropdown-divider" />
                </li>
                <li v-if="userType == 'V'">
                     <router-link
                    class="dropdown-item"
                    :class="{ active: $route.name === 'deleteVcard' }"
                    :to="{ name: 'changeSecretValue', params:{isUpdatePassword:0,fieldUpdate:'Confirmation Code'} }"
                    ><i class="bi bi-key-fill"></i>
                   Change Confirmation Code
                  </router-link>
                </li>
                  <li>
                  <hr v-if="userType == 'V'" class="dropdown-divider" />
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
      username: null,
      userType: null,
      password: "",
      photo:null
    };
  },
  computed: {
    updatedPhoto() {
      return this.$store.getters.updatedPhoto;
    },
  },
  watch: {
    updatedPhoto:{
      handler() {
          if(this.$store.getters.updatedPhoto){
          this.$axios.get(`/vcards/storage/${this.username}`)
          .then((response)=>{
            if(response.data.length != 0){
              this.photo = this.$serverURL + "/storage/fotos/" + response.data;
            }else{
              this.photo = null
            }
          });
        }
      },
      deep:true
    }
  },
  created(){
    this.username =  this.$store.getters.username;
    this.userType =  this.$store.getters.userType;
        this.$axios.get(`/vcards/storage/${this.username}`)
        .then((response)=>{
            if(response.data.length != 0){
              this.photo = this.$serverURL + "/storage/fotos/" + response.data;
            }
            else{
              this.photo = null
            }
        });
    },
  methods: {
    logout() { 
      this.$store
        .dispatch("authLogout")
        .then(() => {
          this.$socket.emit('logged_out', this.username, this.$store.getters.userType);
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
  margin: 1px 4px;
  padding-top: 1rem;
}

.dropdown-item {
  font-size: 0.875rem;
  cursor: pointer;
}
</style>
