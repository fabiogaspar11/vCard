<template>
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
            @click.prevent="createDefaultCategory"
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
import CategoriesCreateEdit from "../categories/CategoriesCreateEdit.vue"
export default {
  name: "DefaultCategoriesCreate",
  components: {
    Sidebar,
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
  createDefaultCategory() {
    //if it doesn't click then nothing is emitted
    if(this.type == null){
      this.type = 'D';
    }

    let defaultCategory = {};
    if(this.name != null){
        defaultCategory.name = this.name;
    }
    if(this.type != null && this.type != this.previousType){
        defaultCategory.type = this.type;
    }
    defaultCategory.vcard = this.vcard.phone_number

    this.errors = [];
    this.$axios.post(`/categories`, defaultCategory)
      .then(() => {
        this.$router.push({name: "categories"});
      })
      .catch((error) => {
        Object.entries(error.response.data.errors).forEach(([key, val]) => {
          this.errors[key] = val[0];
        });
        console.log(error.response.data)
    });
  },
}
}
</script>

<style>

</style>