<template>
  <nav
    id="sidebarMenu"
    class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse"
  >
    <div class="position-sticky pt-3">
      <ul class="nav flex-column">
        <li class="nav-item">
          <router-link
            class="nav-link"
            :class="{ active: $route.name === 'dashboard' }"
            :to="{ name: 'dashboard' }"
            ><i class="bi bi-house"></i>
            Dashboard
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            class="nav-link"
            :class="{ active: $route.name === 'categories' }"
            :to="{ name: 'categories' }"
            ><i class="bi bi-bookmark"></i>
            Categories
          </router-link>
        </li>

        <li class="nav-item">
          <router-link
            class="nav-link"
            :class="{ active: $route.name === 'userdetails' }"
            :to="{ name: 'userdetails' }"
            ><i class="bi bi-people"></i>
            User Details
          </router-link>
        </li>
        <li class="nav-item">
          <router-link
            class="nav-link"
            :class="{ active: $route.name === 'statistics' }"
            :to="{ name: 'statistics' }"
            ><i class="bi bi-bar-chart-line-fill"></i>
            Statistics
          </router-link>
        </li>

      </ul>
    </div>
  </nav>
</template >

<script>
export default {};
</script>

<style>
.btn:focus {
  outline: none;
  box-shadow: none;
}

#sidebarMenu {
  overflow-y: auto;
}
</style>