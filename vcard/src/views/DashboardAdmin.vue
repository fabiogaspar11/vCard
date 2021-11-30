<template>
    <SideBardAdmin></SideBardAdmin>
     <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
     <div class="container d-flex flex-row" style="width:75%">
        <button class="m-2 btn btn-dark btn-lg" @click="$router.push({name:'administrators'})">Administrators</button>
        <button class="m-2 btn btn-dark btn-lg" @click="$router.push({name:'defaultCategories'})">Default Categories</button>
        <button class="m-2 btn btn-dark btn-lg">Transactions</button>
        <button class="m-2 btn btn-dark btn-lg">Statistics</button>
     </div>
     </main>
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