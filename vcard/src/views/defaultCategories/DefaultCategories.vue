<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

      <div class="container d-flex flex-row justify-content-between">
      <h1>Default Categories</h1>
      <router-link class="m-2 btn btn-primary" :to="{name:'defaultCategoriesCreate'}">New Default Category</router-link>
      </div>
      <table class="table">
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
            <td>{{ category.type }}</td>
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
      defaultCategories: null
    };
  },
  methods:{
    deleteCategory(id){
    this.$axios.delete(`defaultCategories/${id}`)
    .then(response =>{
      alert(`Default Category ${response.data.data.name} removed`);
      this.$router.push({name:'dashboardAdmin'})
    })
    .catch(() => {
      alert(`Could not delete default category ${id}`);
    });
    },
  },
  mounted() {
     this.$axios
      .get(`/defaultCategories`)
      .then(response =>{
      this.defaultCategories = response.data.data; 
    });
  },
};
</script>

<style>
</style>