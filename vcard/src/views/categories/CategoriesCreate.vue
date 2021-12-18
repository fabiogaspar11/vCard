<template>
  <Navbar></Navbar>
  <Sidebar></Sidebar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="text-center mt-5">
      <h2>Create Category</h2>
      <div class="container">
       <CategoriesCreateEdit @updateType="updateType" @updateName="updateName" @getVcard="getVcard" :errors="errors"></CategoriesCreateEdit>
        <div class="container">
          <br>
          <button
            id="buttonRegister"
            class="btn btn-success"
            type="submit"
            @click.prevent="createcategory"
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
import CategoriesCreateEdit from "../categories/CategoriesCreateEdit.vue"
export default {
  name: "CategoriesCreate",
  components: {
    Sidebar,
    Navbar,
    CategoriesCreateEdit
  },
data() {
    return {
      vcard: null,
      name: null,
      type: null,
      errors: [],
    };
  },
methods: {
  updateType(type) {
    this.type = type;
  },
  updateName(name) {
    this.name = name;
  },
  getVcard(vcard){
    this.vcard = vcard;
  },
  createcategory() {
    //if it doesn't click then nothing is emitted
    if(this.type == null){
      this.type = 'D';
    }

    let category = {};
    if(this.name != null){
        category.name = this.name;
    }
    if(this.type != null && this.type != this.previousType){
        category.type = this.type;
    }
    category.vcard = this.vcard.phone_number

    this.errors = [];
    this.$axios.post(`/categories`, category)
      .then(() => {
        this.$router.push({name: "categories"});
      })
      .catch((error) => {
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