<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="text-center mt-5">
      <h2>Create Default category</h2>
      <div class="container">
       <DefaultCategoriesCreateEdit @updateType="updateType" @updateName="updateName" :errors="errors"></DefaultCategoriesCreateEdit>
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
import DefaultCategoriesCreateEdit from "../defaultCategories/DefaultCategoriesCreateEdit.vue"
export default {
  name: "DefaultCategoriesCreate",
  components: {
    SideBardAdmin,
    DefaultCategoriesCreateEdit
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
        createDefaultCategory() {
        let defaultCategory = {};
        if(this.name != null){
            defaultCategory.name = this.name;
        }
        if(this.type != null){
            defaultCategory.type = this.type;
        }
        this.$axios
            .post(`/defaultCategories`, defaultCategory)
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