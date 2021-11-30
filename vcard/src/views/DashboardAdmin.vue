<template>
  <SideBardAdmin></SideBardAdmin>
    <router-link to="layoutAdmin"> </router-link>
    <router-view />
</template>

<script>
import SideBardAdmin from "./../components/SideBarAdmin.vue";
export default {
  name: "DashboardAdmin",
  components: {
    SideBardAdmin
  }
}
</script>

<style>

</style>