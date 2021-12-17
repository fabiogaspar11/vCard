<template>
  <Navbar></Navbar>
  <Sidebar></Sidebar>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 ">
    <div class="row" style="width: 90%; text-align:center">
      <div style="padding-top: 2%;" class="col-sm">
        <h2>Categories</h2>
      </div>
      <div class="col-sm">
      </div>
      <div style="padding-top: 2%;" class="col-sm">
        <button
          type="button"
          class="btn btn-secondary"
          style="width: 50%;"
          @click="$router.push({ name: 'categoriesCreate' })"
        >
          New Category
        </button>
      </div>
    </div>
    <br />
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
        <tr v-for="category in categories" :key="category.id">
          <td>{{ category.id }}</td>
          <td>{{ category.type == 'C' ? "Credit": "Debit" }}</td>
          <td class="alnleft">{{ category.name }}</td>
          <td>
            <div class="container">
              <a
                class="btn btn-danger" style="margin-right: 1%;"
                role="button"
                aria-pressed="true"
                @click="deleteCategory(category.id)"
              >
                <i
                  class="bi bi-trash buttonIcons"
                ></i>
              </a>
              <a
                class="btn btn-info"
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
                  class="bi bi-pencil-square buttonIcons"
                ></i>
              </a>
            </div>
          </td>
        </tr>
      </tbody>
    </table>
    <nav aria-label="Page navigation example">
      <ul class="pagination d-flex justify-content-center">
        <li class="page-item"><a class="page-link" style="color: black;" href="#" @click.prevent="getPreviousPage()">Previous</a></li>
        <li class="page-item">
          <a class="page-link" href="#"  v-bind:class="{'white': !clickedPage1, 'black': clickedPage1}" @click="clickedPage1 = true, clickedPage2 = false, clickedPage3 = false, getPage(this.pages)">
            {{ this.pages }}
          </a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#" v-if="this.pages < this.lastPage" v-bind:class="{'white': !clickedPage2, 'black': clickedPage2}" @click="clickedPage2 = true, clickedPage1 = false, clickedPage3 = false, getPage(this.pages+1)">
            {{ this.pages+1 }}
          </a>
        </li>
        <li class="page-item">
          <a class="page-link" href="#" v-if="this.pages < this.lastPage" v-bind:class="{'white': !clickedPage3, 'black': clickedPage3}" @click="clickedPage3 = true, clickedPage1 = false, clickedPage2 = false, getPage(this.pages+2)">
            {{ this.pages+2 }}
          </a>
        </li>
        <li class="page-item"><a class="page-link" style="color: black;" href="#" @click.prevent="getNextPage()">Next</a></li>
      </ul>
    </nav>
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
      pageActual: 1,
      clickedPage1: true,
      pages: 1,
      lastPage: null,
    };
  },
  methods: {
    deleteCategory(id) {
      this.$axios
        .delete(`categories/${id}`)
        .then((response) => {
          this.$toast.info(`Category ${response.data.data.name} removed`);
          if (this.categories.length == 1){
            this.pageActual -= 1
            this.pages -= 3
            this.clickedPage1 = false
            this.clickedPage2 = false
            this.clickedPage3 = true
          }
          this.getCategories()
        })
        .catch(() => {
          this.$toast.info(`Could not delete category ${id}`);
        });
    },
    getPreviousPage(){
      if (this.pageActual > 3){
        this.pages -= 3;
        this.pageActual = this.pages;
        this.clickedPage1 = true
        this.clickedPage2 = false
        this.clickedPage3 = false
        this.getCategories()
      }
    },
    getNextPage(){
      if (this.pageActual < this.lastPage && this.pages+3 <= this.lastPage){
        this.pages += 3;
        this.pageActual = this.pages;
        this.clickedPage1 = true
        this.clickedPage2 = false
        this.clickedPage3 = false
        this.getCategories()
      }
      console.log(this.page)
      console.log(this.pageActual)
    },
    getPage(selectedPage){     
      this.pageActual = selectedPage
      this.getCategories()
    },
    getCategories(){
      this.$axios
        .get(`/vcards/${this.phone_number}/categories?page=${this.pageActual}`)
        .then((response) => {
          this.categories = response.data.data;
          this.lastPage = response.data.meta.last_page;       
        });
    }
  },
  mounted() {},
  created() {
    this.$axios
      .get(`/vcards/${this.phone_number}`)
      .then((response) => {
        this.vcard = response.data.data;
        this.getCategories()
      })
      .catch((error) => {
        console.log(error);
      });
  },
};
</script>

<style scoped>

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

.buttonIcons{
  color: white; 
  margin-right: 25%;
  font-size: 100%;
}

.alnleft { 
  text-align: left;
  padding-left: 13%;
}

.white {
  background-color: white;
  color: black;
}
.black {
  background-color: #6c757d;
  color: white;
}
</style>