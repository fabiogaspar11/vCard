<template>
  <Navbar></Navbar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
 
      <h1>Administrators</h1>
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Details</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="admin in administrators" :key="admin.id">
            <td>{{ admin.id }}</td>
            <td>{{ admin.name }}</td>
             <td>{{ admin.email }}</td>
            <td>
              <div class="container">
                <a class="btn btn-info m-1" role="button" aria-pressed="true" >
                    <i class="bi bi-arrows-fullscreen" style="color:white;margin-right:25%"></i>
                </a>
                <a class="btn btn-primary m-1" role="button" aria-pressed="true" >
                  <i class="bi bi-pencil-square" style="color:white;margin-right:25%"></i>
                </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
  </main>
</template>

<script>
import Navbar from "../../components/Navbar.vue";
export default {
  name: "Administrators",
  components: {
    Navbar,
  },
  data() {
    return {
      administrators: null
    };
  },
  mounted() {
     this.$axios
      .get(`/administrators`)
      .then(response =>{
      this.administrators = response.data.data; 
    });
  },
};
</script>

<style>
</style>