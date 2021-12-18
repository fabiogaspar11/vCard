<template>
  <Navbar></Navbar>
  <Sidebar></Sidebar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="text-center mt-5">
      <h2>Edit category Nº {{id}}</h2>
      <div class="container">
        <p>Current Name: {{previousName}} | Type: {{previousType}}</p>
       <CategoriesCreateEdit @updateType="updateType" @updateName="updateName" :errors="errors" :previousType="previousType"></CategoriesCreateEdit>
        <div class="container">
          <br>
          <button
            id="buttonRegister"
            class="btn btn-success"
            type="submit"
            @click.prevent="editCategory"
          >
            Confirm
          </button>
          <button
            class="btn btn-danger"
            @click="$router.push({ name: 'categories' })"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
    </main>
</template>

<script>
import Sidebar from "../../components/Sidebar.vue";
import Navbar from "../../components/Navbar.vue";
import CategoriesCreateEdit from "./CategoriesCreateEdit.vue"
export default {
  name: "DefaultCategoriesCreate",
  components: {
    Sidebar,
    Navbar, 
    CategoriesCreateEdit
  },
    props:{
      id: String,
      previousName: String,
      previousType: String
  },
data() {
  return {
    name: null,
    type:null,
    errors: []
  };
},
methods: {
  updateType(type) {
    this.type = type;
  },
  updateName(name) {
    this.name = name;
  },
  editCategory() {
    this.errors = [];
    if(this.name == this.previousName && this.type == this.previousType){
      this.errors.name = "Name is equal";
      this.name = null
      return;
    }
    if(this.name == null && this.type == null){
      this.errors.type = "Nothing to update";
      return
    }
    if(this.type == null){
      this.type = this.previousType;
    }
    

    let category = {};
    if(this.name != null){
        category.name = this.name;
    }
    if(this.type != null && this.type != this.previousType){
        category.type = this.type;
    }
    this.$axios.put(`/categories/${this.id}`, category)
    .then(() => {
      this.$router.push({name: "categories"});
    })
    .catch((error) => {
      this.errors = [];
      Object.entries(error.response.data.errors).forEach(([key, val]) => {
        this.errors[key] = val[0];
      });
    });
  }, 
}
}
</script>

<style>

</style>