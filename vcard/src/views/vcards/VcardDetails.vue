<template>
  <div>
    <Sidebar></Sidebar>
    <Navbar></Navbar>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <div
        class="
          d-flex
          justify-content-between
          flex-wrap flex-md-nowrap
          align-items-center
          pt-3
          pb-2
          mb-3
          border-bottom
        "
      >
        <h1 class="h2"> User Details</h1>
        <div class="align">
        </div>
      </div>

  <div class="color" v-if="this.vcard != null">
        <div class="container-fluid m-5">
          <div class="row">
            <div class="col-lg-3 profileDiv">
              <img class="profile" :src="photo" />
  
              <div v-show="errors.photo_url != undefined" class="text-danger">{{errors.photo_url}}</div>
              <div class="form-group">
                <input type="file" v-on:change="onFileChange" class="form-control" name="imagem_url" id="inputFoto" style="height: auto" >
              </div>
            </div>

            <div class="col-lg-8">
              <div class="row">
                <div class="col-4 details">
                  <h5>Name:</h5>
                </div>
                <div class="col-8">
                  <input type="text" class="form-control inputdetails" placeholder="Name" v-model="this.name" />
                  <div v-show="errors.name != undefined" class="text-danger">{{errors.name}}</div>
                </div>
              </div>
              <div class="row">
                <div class="col-4 details">
                  <h5>Email:</h5>
                </div>
                <div class="col-8">
                  <input type="text" class="form-control inputdetails" placeholder="Email" v-model="this.email" />
                  <div v-show="errors.email != undefined" class="text-danger">{{errors.email}}</div>
                </div>
              </div>
              <div class="right">
              <button type="button" class="btn btn-primary" @click.prevent="save">Save</button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div style="text-align: center;">
        <div
          class="spinner-border text-primary loading_vcard"
          role="status"
          v-show="!this.loaded"
        >
          <span class="sr-only"></span>
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
      email: null,
      photo_url : null,
      loaded: false,
      errors: [],
      photo:'',
      config : {
        header : {
          'Content-Type' : 'multipart/form-data'
        }
      }
    };
  },
  methods: {
     onFileChange(e) {
      var files = e.target.files;
      if (!files.length){
        return;
      }     
      var reader = new FileReader();
      reader.onload = (e) => {
        this.photo = e.target.result;
      }
      this.photo_url = files[0]
      reader.readAsDataURL(files[0]);
    }, 
    save(){
      this.$store.commit('toggleUpdatedPhoto', false)
      let formData = new FormData();
      if (this.name != null) {
        formData.append('name', this.name);
      }
      if(this.email != null){
        formData.append('email', this.email);
      }
      if(this.photo_url != null){
        formData.append('photo_url', this.photo_url);
      }
      formData.append('_method', 'PUT');
      this.errors = [];
      this.$axios.post(`/vcards/${this.$store.getters.username}`, formData, this.config)
        .then(response =>{
          this.$toast.success("User information was saved");
          this.vcard = response.data.data
           this.$store.commit('toggleUpdatedPhoto', true)
        })
        .catch((error) => {
          this.errors = [];
          Object.entries(error.response.data.errors).forEach(([key, val]) => {
            this.errors[key] = val[0];
          });
        });
    },
  },
  created() {
    this.$axios.get(`/vcards/${this.$store.getters.username}`)
      .then((response) => {
        this.vcard = response.data.data;
        this.name = this.vcard.name;
        this.email = this.vcard.email;
        this.photo_url = this.vcard.photo_url;
        this.photo = "http://laravelapi.test/storage/fotos/" + this.photo_url,
        this.loaded = true;
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
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}

.right {
  display: block;
  margin-left: auto;
  margin-right: 0;
  width: 50%;
}

.align {
  display: flex;
  justify-content: right;
}


#userdetails {
  background: #e6e6e6;
}

.profile {
  border-radius: 30px;
  width: 100%;
  height: 85%;
}

.profileDiv {
  padding-top: 3%;
  padding-left: 3%;
  margin-bottom: 5%;
}

.details {
  margin-top: 1.5%;
}

.inputdetails {
  height: 60%;
}

.saveDetails {
  width: 20%;
  height: 10%;
  float: right;
  margin-bottom: 3%;
}

.loading_vcard {
  margin: 5% auto auto auto;
  width: 10rem;
  height: 10rem;
}

</style>
