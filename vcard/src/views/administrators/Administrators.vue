<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

      <div class="container d-flex flex-row justify-content-between">
      <h2>Administrators</h2>
      <router-link class="m-2 btn btn-secondary" :to="{name:'administratorCreate'}">New Administrator</router-link>
      </div>
      <nav aria-label="Page navigation example" class="d-flex justify-content-center">
        <ul class="pagination">
          <li class="page-item"><a class="page-link" style="color: black;" href="#" @click.prevent="getPreviousPage()">Previous</a></li>
          <li class="page-item"><a class="page-link" style="color: black;" href="#">{{ this.pageActual }}</a></li>
          <li class="page-item"><a class="page-link" style="color: black;" href="#" @click.prevent="getNextPage()">Next</a></li>
        </ul>
      </nav>
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
      administrators: null,
      pageActual: 1,
      lastPage: null,
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
          .then(() =>{
            //this.administrators = response.data.data; 
            if (this.administrators.length == 1){
               this.pageActual -= 1
            }
            this.getAdministrators()
        });
      })
      .catch((error) => {
          if(error.response.status == 404){
                  this.$toast.error("This administrator could not be found");
                  this.$router.push({name: "dashboardAdmin"});
            }
      });
    },
    getPreviousPage(){
      if (this.pageActual > 1){
        this.pageActual--;
        this.getAdministrators()
      }
    },
    getNextPage(){
      if (this.pageActual < this.lastPage){
        this.pageActual++;
        this.getAdministrators()
      }
 
    },
    getAdministrators(){
      this.$axios
      .get(`/administrators?page=${this.pageActual}`)
      .then(response =>{
        this.administrators = response.data.data; 
        this.lastPage = response.data.meta.last_page;     
      });
    }
  },
  mounted() {
    this.getAdministrators()
  },
};
</script>

<style>
</style>