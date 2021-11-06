<template>
  <nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top flex-md-nowrap p-0 shadow">
    <div class="container-fluid">
      <a
        class="navbar-brand col-md-3 col-lg-2 me-0 px-3"

      ><img
          src="./assets/logo.png"
          alt=""
          width="30"
          height="24"
          class="d-inline-block align-text-top"
        >
        App name</a>
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
          <li class="nav-item">
            <a
              class="nav-link"
             
            ><i class="bi bi-person-check-fill"></i>
              Register
            </a>
          </li>
          <li class="nav-item">
            <a
              class="nav-link"
            
            ><i class="bi bi-box-arrow-in-right"></i>
              Login
            </a>
          </li>
          <li class="nav-item dropdown">
            <a
              class="nav-link dropdown-toggle"
              
              id="navbarDropdownMenuLink"
              role="button"
              data-bs-toggle="dropdown"
              aria-expanded="false"
            >
              <img
                src="./assets/img/avatar-exemplo-1.jpg"
                class="rounded-circle z-depth-0 avatar-img"
                alt="avatar image"
              >
              <span class="avatar-text">User Name</span>
            </a>
            <ul
              class="dropdown-menu dropdown-menu-dark dropdown-menu-end"
              aria-labelledby="navbarDropdownMenuLink"
            >
              <li><a
                  class="dropdown-item"
                 
                ><i class="bi bi-person-square"></i>Profile</a></li>
              <li><a
                  class="dropdown-item"
                
                ><i class="bi bi-key-fill"></i>Change password</a></li>
              <li>
                <hr class="dropdown-divider">
              </li>
              <li><a
                  class="dropdown-item"
                 
                ><i class="bi bi-arrow-right"></i>Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid">
    <div class="row">
      <nav
        id="sidebarMenu"
        class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
      >
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a
                class="nav-link active"
                aria-current="page"
               
              >
                <i class="bi bi-house"></i>
                Dashboard
              </a>
            </li>

            <li class="nav-item">
              <a
                class="nav-link"
               
              >
                <i class="bi bi-currency-euro"></i>
                Transfer Money
              </a>
            </li>

            <li class="nav-item">
              <a
                class="nav-link"
               
              >
                <i class="bi bi-people"></i>
                User Details
              </a>
            </li>
            <li class="nav-item">
              <a
                class="nav-link"
                
              >
                <i class="bi bi-bar-chart-line"></i>
                Transactions
              </a>
            </li>
          </ul>       

          <div class="d-block d-md-none">
            <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-4 mb-1 text-muted">
              <span>User</span>
            </h6>
            <ul class="nav flex-column mb-2">
              <li class="nav-item">
                <a
                  class="nav-link"
                
                ><i class="bi bi-person-check-fill"></i>
                  Register
                </a>
              </li>
              <li class="nav-item">
                <a
                  class="nav-link"
                
                >
                  <i class="bi bi-box-arrow-in-right"></i>
                  Login
                </a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  
                  id="navbarDropdownMenuLink2"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  <img
                    src="./assets/img/avatar-exemplo-1.jpg"
                    class="rounded-circle z-depth-0 avatar-img"
                    alt="avatar image"
                  >
                  <span class="avatar-text">User Name</span>
                </a>
                <ul
                  class="dropdown-menu"
                  aria-labelledby="navbarDropdownMenuLink2"
                >
                  <li><a
                      class="dropdown-item"
                     
                    ><i class="bi bi-person-square"></i>Profile</a></li>
                  <li><a
                      class="dropdown-item"
                  >
                    ><i class="bi bi-key-fill"></i>Change password</a></li>
                  <li>
                    <hr class="dropdown-divider">
                  </li>
                  <li><a
                      class="dropdown-item"
                    
                    ><i class="bi bi-arrow-right"></i>Logout</a></li>
                </ul>
              </li>
            </ul>
          </div>

        </div>
      </nav>

      <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <component :is="activeComponent"></component>
      </main>
    </div>
  </div>
</template>

<script>
import Dashboard from "./components/Dashboard.vue"
import Menu from "./components/Menu.vue"
import Home from "./views/Home.vue"

export default {
  name: 'RootComponent',
  components: {
    Dashboard,
    Home,
    Menu
  },
  data () {
    return {
      activeComponent: "dashboard"
    }
  },
  methods: {
  },
}
</script>

<style lang="scss">
@import "./assets/css/dashboard.css";

.avatar-img {
  margin: -1.2rem 0.8rem -2rem 0.8rem;
  width: 3.3rem;
  height: 3.3rem;
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
}
</style>
