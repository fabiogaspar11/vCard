<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

      <div class="container d-flex flex-row justify-content-between">
      <h1>Administrators</h1>
      <router-link class="m-2 btn btn-primary" :to="{name:'administratorCreate'}">New Administrator</router-link>
      </div>
      <table class="table">
        <thead>
          <tr>
            <th>Id</th>
            <th>Name</th>
            <th>Email</th>
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="admin in administrators" :key="admin.id">
            <td>{{ admin.id }}</td>
            <td>{{ admin.name }}</td>
             <td>{{ admin.email }}</td>
            <td>
              <div class="container">
                <a class="btn btn-danger m-1" role="button" aria-pressed="true" @click="deleteAdmin(admin.id, admin.email)">
                  <i class="bi bi-trash" style="color:white;margin-right:25%"></i>
                </a>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
  </main>
</template>

<script>
import SideBardAdmin from "./../../components/SideBarAdmin.vue";
export default {
  name: "Administrators",
  components: {
    SideBardAdmin,
  },
  data() {
    return {
      administrators: null
    };
  },
  methods:{
    deleteAdmin(id,username){
    this.$axios.delete(`administrators/${id}`)
    .then(() =>{
      this.$toast.info(`Administrator ${id} removed`);
      this.$socket.emit('userDeleted', username);   
        this.$axios
        .get(`/administrators`)
        .then(response =>{
        this.administrators = response.data.data; 
      });
    })
    .catch(() => {
      this.$toast.info(`Could not delete administrator ${id}`);
    });
    }
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