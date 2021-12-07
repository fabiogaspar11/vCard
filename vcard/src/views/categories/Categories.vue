<template>
  <Navbar></Navbar>
  <Sidebar></Sidebar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="row">
      <div class="col-sm">
        <h2>Categories</h2>
      </div>
      <div class="col-sm">
      </div>
      <div class="col-sm">
        <button
          type="button"
          class="btn btn-primary newCategorie"
          @click="$router.push({ name: 'categoriesCreate' })"
        >
          New Category
        </button>
      </div>
    </div>
          <br />

    <table class="table color">
      <thead>
        <tr>
          <th>Id</th>
          <th>Type</th>
          <th>Name</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="category in categories" :key="category.id">
          <td>{{ category.id }}</td>
          <td>{{ category.type }}</td>
          <td>{{ category.name }}</td>
          <td>
            <div class="container">
              <a
                class="btn btn-danger m-1"
                role="button"
                aria-pressed="true"
                @click="deleteCategory(category.id)"
              >
                <i
                  class="bi bi-trash"
                  style="color: white; margin-right: 25%"
                ></i>
              </a>
              <a
                class="btn btn-info m-1"
                role="button"
                aria-pressed="true"
                @click="
                  $router.push({
                    name: 'categoriesEdit',
                    params: {
                      id: category.id,
                      previousName: category.name,
                      previousType: category.type,
                    },
                  })
                "
              >
                <i
                  class="bi bi-pencil-square"
                  style="color: white; margin-right: 25%"
                ></i>
              </a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
  </main>
</template>

<script>
import Navbar from '../../components/Navbar.vue';
import Sidebar from "../../components/Sidebar.vue";
export default {
  name: "Categories",
  components: {
    Sidebar,
    Navbar,
  },
  data() {
    return {
      vcard: null,
      categories: null,
      phone_number: localStorage.getItem("username") || null,
    };
  },
  methods: {
    deleteCategory(id) {
      this.$axios
        .delete(`categories/${id}`)
        .then((response) => {
          this.$toast.info(`Category ${response.data.data.name} removed`);
        })
        .catch(() => {
          this.$toast.info(`Could not delete category ${id}`);
        });
    },
  },
  mounted() {},
  created() {
    this.$axios
      .get(`/vcards/${this.phone_number}`)
      .then((response) => {
        this.vcard = response.data.data;
        this.$axios
          .get(`/vcards/${this.phone_number}/categories`)
          .then((response) => {
            this.categories = response.data.data;
          });
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style scoped>
#page {
  margin: 5% auto 5% auto;
}

.newCategorie {
  width: 70%;
  height: 100%;
}
</style>