<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

      <div class="container d-flex flex-row justify-content-between">
      <h2>Default Categories</h2>
      <router-link class="m-2 btn btn-secondary" :to="{name:'defaultCategoriesCreate'}">New Default Category</router-link>
      </div>
      <nav aria-label="Page navigation example" class="d-flex justify-content-center">
        <ul class="pagination">
          <li class="page-item"><a class="page-link" href="#" @click.prevent="getPreviousPage()">Previous</a></li>
          <li class="page-item"><a class="page-link" href="#">{{ this.pageActual }}</a></li>
          <li class="page-item"><a class="page-link" href="#" @click.prevent="getNextPage()">Next</a></li>
        </ul>
      </nav>
      <table class="table-spacing">
        <thead>
          <tr>
            <th>Id</th>
            <th>Type</th>
            <th>Name</th>          
            <th>Options</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="category in defaultCategories" :key="category.id">
            <td>{{ category.id }}</td>
            <td>{{ category.type == 'C' ? "Credit": "Debit" }}</td>
            <td>{{ category.name }}</td>
             <td>{{ category.email }}</td>
            <td>
               <div class="container">
                    <a class="btn btn-danger m-1" role="button" aria-pressed="true" @click="deleteCategory(category.id)">
                    <i class="bi bi-trash" style="color:white;margin-right:25%"></i>
                    </a>
                    <a class="btn btn-info m-1" role="button" aria-pressed="true" @click="$router.push({name:'defaultCategoriesEdit', params: {id: category.id, previousName: category.name, previousType: category.type}})">
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
import SideBardAdmin from "../../components/SideBarAdmin.vue";
export default {
  name: "DefaultCategories",
  components: {
    SideBardAdmin,
  },
  data() {
    return {
      defaultCategories: null,
      pageActual: 1,
      lastPage: null,
    };
  },
  methods:{
    deleteCategory(id){
      this.$axios.delete(`defaultCategories/${id}`)
      .then(response =>{
        this.$toast.info(`Default Category ${response.data.data.name} removed`);
        this.$router.push({name:'dashboardAdmin'})
      })
      .catch(() => {
        this.$toast.info(`Could not delete default category ${id}`);
      });
    },
    getPreviousPage(){
      if (this.pageActual > 1){
        this.pageActual--;
        this.getDefaultCategories()
      }
    },
    getNextPage(){
      if (this.pageActual < this.lastPage){
        this.pageActual++;
        this.getDefaultCategories()
      }
 
    },
    getDefaultCategories(){
      this.$axios
        .get(`/defaultCategories?page=${this.pageActual}`)
        .then(response =>{
          this.defaultCategories = response.data.data; 
          this.lastPage = response.data.meta.last_page;       
        });
    }
  },
  mounted() {
     this.getDefaultCategories()
  },
};
</script>

<style>
table td {
  border: 10px solid white;
  background-color: #e6e6e6;
  border-right: 0;
  border-left: 0;
}

.table-spacing{
  width:90%;
  text-align: center;
  margin-left: auto;
  margin-right: auto;
}
</style>