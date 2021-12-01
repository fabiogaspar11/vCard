<template>
  <Navbar></Navbar>
     <nav
        id="sidebarMenu"
        class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
      >
        <div class="position-sticky pt-3">
          <ul class="nav flex-column">
            <li class="nav-item">
              <a class="btn btn-light" role="button" aria-pressed="true" @click="$router.push({name:'dashboardAdmin'})">
                   Administrator Dashboard
                </a>
            </li>
            <li class="nav-item">
              <a class="btn btn-light" role="button" aria-pressed="true" @click="$router.push({name:'administrators'})">
                   Administrators
                </a>
            </li>
           <li class="nav-item">
              <a class="btn btn-light" role="button" aria-pressed="true" @click="$router.push({name:'vcards'})">
                   Vcard Users
                </a>
            </li>
            <li class="nav-item">
              <a class="btn btn-light" role="button" aria-pressed="true"  @click="$router.push({name:'defaultCategories'})">
                   Default Categories
                </a>
            </li>
            <li class="nav-item">
              <a class="btn btn-light" role="button" aria-pressed="true" >
                   Statistics
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
                    src="../assets/img/avatar-exemplo-1.jpg"
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
</template>

<script>
import Navbar from "./Navbar.vue";
export default {
  name: "SideBarAdmin",
  components: {
    Navbar
  }
}
</script>

<style>

</style>