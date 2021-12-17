<template>
  <div>
    <Sidebar></Sidebar>
    <Navbar></Navbar>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <br />
      <div class="d-flex justify-content-center">
        <img id="photoProfile" :src="photo">
      </div>
      <h5 id="title"> {{this.phoneNumber}}</h5>
      <div class="form-group d-flex justify-content-center">
        <input
          type="file"
          v-on:change="onFileChange"
          class="form-control inputFile"
          name="imagem_url"
        />
      </div>

      <div v-show="errors.photo_url != undefined" class="text-danger">
        {{ errors.photo_url }}
      </div>

      <br />
      <div class="container details" v-if="this.vcard != null">
        <label for="name"><b>Name:</b></label>
        <div class="container-fluid statistics">
          <div class="row">
            <div class="col-2 chartDesign">
              <i class="bi bi-person-fill" style="font-size: 300%;"></i>
            </div>
             <div class="col-10 chartDesign">
              <input type="text" class="form-control" placeholder="Name" v-model="this.name"/>
            </div>
          </div>
        </div>
        
        <div v-show="errors.name != undefined" class="text-danger">
          {{ errors.name }}
        </div>
        <br />

        <label for="email"><b>Email:</b></label>
        <div class="container-fluid statistics">
          <div class="row">
            <div class="col-2 chartDesign">
              <i class="bi bi-envelope-fill" style="font-size: 300%;"></i>
            </div>
             <div class="col-10 chartDesign">
              <input type="text" class="form-control" placeholder="Email" v-model="this.email"/>
            </div>
          </div>
        </div>
        <div v-show="errors.email != undefined" class="text-danger">
          {{ errors.email }}
        </div>
        <br />
        <button type="button" class="btn btn-secondary" style="width: 30%;" @click.prevent="save">
          Save
        </button>
        <div
          @click="closeSuccessMesage"
          v-if="showMessage"
          class="alert alert-danger alert-dismissible"
        >
          To Save changes you first need to edit your information
        </div>
      </div>
    </main>
  </div>
</template>
<script>
import Sidebar from "../../components/Sidebar.vue";
import Navbar from "../../components/Navbar.vue";

export default {
  name: "VcardDetails",
  components: {
    Sidebar,
    Navbar,
  },
  data() {
    return {
      showMessage: this.successMessage != null ? true : false,
      phoneNumber: localStorage.getItem("username"),
      vcard: null,
      name: null,
      name_old: null,
      email: null,
      email_old: null,
      photo_url: null,
      photo_url_old: null,
      loaded: false,
      errors: [],
      photo: "",
      config: {
        header: {
          "Content-Type": "multipart/form-data",
        },
      },
    };
  },
  methods: {
    closeSuccessMesage: function () {
      this.showMessage = false;
    },
    onFileChange(e) {
      var files = e.target.files;
      if (!files.length) {
        return;
      }
      var reader = new FileReader();
      reader.onload = (e) => {
        this.photo = e.target.result;
      };
      this.photo_url = files[0];
      reader.readAsDataURL(files[0]);
    },
    save() {
 
      this.$store.commit("toggleUpdatedPhoto", false);
      let formData = new FormData();
      if (this.name != null && this.name != this.name_old) {
        formData.append("name", this.name);
      }
      if (this.email != null && this.email != this.email_old) {
        formData.append("email", this.email);
      }
      if (this.photo_url != null && this.photo_url != this.photo_url_old) {
        formData.append("photo_url", this.photo_url);
      }

      if (
        this.name != this.name_old ||
        this.email != this.email_old ||
        this.photo_url != this.photo_url_old
      ) {
        this.name_old = this.name;

        this.email_old = this.email;
        this.photo_url_old = this.photo_url;

        formData.append("_method", "PUT");
        this.errors = [];
        this.$axios
          .post(
            `/vcards/${this.$store.getters.username}`,
            formData,
            this.config
          )
          .then((response) => {
            this.$toast.success("User information was saved");
            this.vcard = response.data.data;
            this.$store.commit("toggleUpdatedPhoto", true);
          })
          .catch((error) => {
            this.errors = [];
            Object.entries(error.response.data.errors).forEach(([key, val]) => {
              this.errors[key] = val[0];
            });
          });
      } else {
        this.showMessage = true;
      }
    },
  },
  created() {
    this.$axios
      .get(`/vcards/${this.$store.getters.username}`)
      .then((response) => {
        this.vcard = response.data.data;
        this.name = this.name_old = this.vcard.name;
        this.email = this.email_old = this.vcard.email;
        this.photo_url = this.photo_url_old = this.vcard.photo_url;
        (this.photo = this.$serverURL + "/storage/fotos/" + this.photo_url),
          (this.loaded = true);
      })
      .catch(() => {
        this.$router.push({
          name: "dashboard",
        });
      });
  },
};
</script>
<style scoped lang="scss">

#photoProfile{
  width:  200px;
  height: 200px;
  background-size: cover;
  border-radius: 20px;
}
  
#title{
  padding-top: 1%;
  padding-bottom: 1%;
  text-align: center;
  color: #cccccc;
}

.inputFile{
  height: auto; 
  width: 30%; 
  border-radius: 5px;
}

.details{
  background-color: #e6e6e6;
  text-align: center;
  width: 70%;
  border-radius: 10px;
  box-shadow: 0 7px 2px -2px gray;
}

</style>
