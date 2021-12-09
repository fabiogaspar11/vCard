<template>
    <SideBardAdmin></SideBardAdmin>
     <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
     <div class="container d-flex flex-row" style="width:90%">
        <button class="m-2 btn btn-dark btn-lg" @click="$router.push({name:'administrators'})">Administrators</button>
        <button class="m-2 btn btn-dark btn-lg" @click="$router.push({name:'defaultCategories'})">Default Categories</button>
        <button class="m-2 btn btn-dark btn-lg" @click="$router.push({name:'vcards'})">Vcard Users</button>
        <button class="m-2 btn btn-dark btn-lg" @click="$router.push({name:'transaction', params:{isAdmin:user_type=='A'}})">Credit Transactions</button>
        <button class="m-2 btn btn-dark btn-lg" @click="$router.push({name:'statisticsAdmin'})">Statistics</button>
     </div>
     </main>
</template>

<script>
import SideBardAdmin from "./../components/SideBarAdmin.vue";
export default {
  name: "DashboardAdmin",
  components: {
    SideBardAdmin
  },
   data() {
    return {
      user_type: this.$store.getters.userType
    };
  }
}
</script>

<style>

</style>