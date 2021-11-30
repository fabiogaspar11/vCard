<template>
  <SideBardAdmin></SideBardAdmin>
    <div>
    <button type="button " class="btn btn-secondary">Left</button>
    <button type="button" class="btn btn-secondary">Middle</button>
    <button type="button" class="btn btn-secondary">Right</button>
    </div>
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