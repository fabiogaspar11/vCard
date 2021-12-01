<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="text-center mt-5">
      <h2>Edit category Nº {{id}}</h2>
      <div class="container">
        <p>Current Name: {{previousName}} | Type: {{previousType}}</p>
       <DefaultCategoriesCreateEdit @updateType="updateType" @updateName="updateName" :errors="errors" :previousType="previousType"></DefaultCategoriesCreateEdit>
        <div class="container">
          <br>
          <button
            id="buttonRegister"
            class="btn btn-success"
            type="submit"
            @click.prevent="editDefaultCategory"
          >
            Confirm
          </button>
          <button
            class="btn btn-danger"
            @click="$router.push({ name: 'defaultCategories' })"
          >
            Cancel
          </button>
        </div>
      </div>
    </div>
    </main>
</template>

<script>
import SideBardAdmin from "../../components/SideBarAdmin.vue";
import DefaultCategoriesCreateEdit from "./DefaultCategoriesCreateEdit.vue"
export default {
  name: "DefaultCategoriesCreate",
  components: {
    SideBardAdmin,
    DefaultCategoriesCreateEdit
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
        editDefaultCategory() {
           this.errors = [];
        if(this.name == this.previousName){
          this.errors.name = "Name is equal";
          this.name = null
          return;
        }
        if(this.name == null && this.type == null){
          this.errors.type = "Nothing to update";
        }
        //if it doesn't click then nothing is emitted
        if(this.type == null){
          this.type = this.previousType;
        }
        let defaultCategory = {};
        if(this.name != null){
            defaultCategory.name = this.name;
        }
        if(this.type != null && this.type != this.previousType){
            defaultCategory.type = this.type;
        }
        this.$axios
            .put(`/defaultCategories/${this.id}`, defaultCategory)
            .then(() => {
            this.$router.push({name: "dashboardAdmin"});
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