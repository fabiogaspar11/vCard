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

      <div id="dashboard" v-if="this.vcard != null">
        <div class="container-fluid">
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
                  <h5>Phone number:</h5>
                </div>
                <div class="col-8">
                  <input type="text" class="form-control inputdetails" placeholder="Phone number" v-model="this.phoneNumber" />
                </div>
              </div>
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
              <hr />
              <div class="row">
                <div class="col-4 details">
                  <h5>Confirmation Code:</h5>
                </div>
                <div class="col-4">
                </div>
                <div class="col-4">
                  <input type="text" class="form-control inputdetails" placeholder="Confirmation Code" v-model="this.confirmation_code" />
                  <div v-show="errors.confirmation_code != undefined" class="text-danger">{{errors.confirmation_code}}</div>
                </div>
              </div>
              <div class="row">
                <div class="col-4 details">
                  <h5>Password:</h5>
                </div>
                 <div class="col-4">
                  <input type="text" class="form-control inputdetails" placeholder="Current Password" v-model="this.old_password" />
                  <div v-show="errors.password != undefined" class="text-danger">{{errors.password}}</div>
                </div>
                <div class="col-4">
                  <input type="text" class="form-control inputdetails" placeholder="New Password" v-model="this.password" />
                </div>
              </div>
              <button type="button" class="btn btn-primary saveDetails" @click.prevent="save">Save</button>
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

      <div id="page" class="row">
        <div class="container-fluid">
          <div class="row">
            <div class="col-lg-3 col-6">
              <div
                class="small-box buttonUserDetails"
                @click="$router.push({ name: 'userdetails' })"
              >
                <i class="bi bi-person-bounding-box icon"></i>
                <h4><i class="iconTitle"> User Details </i></h4>
                <br />
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box buttonSendReceive">
                <i class="bi bi-cash-coin icon"></i>
                <h4><i class="iconTitle"> Send/Receive </i></h4>
                <br />
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div
                class="small-box buttonTransactions"
                @click="$router.push({ name: 'transactions' })"
              >
                <i class="bi bi-list-columns icon" style="color: white"></i>
                <h4><i class="iconTitle"> Transactions </i></h4>
                <br />
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box buttonPiggyBank">
                <i class="bi bi-piggy-bank icon"> </i>
                <h4 class="title"><i class="iconTitle"> Piggy Bank</i></h4>
                <br />
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>
<script>
import Sidebar from "../../components/Sidebar.vue";
import Navbar from "../../components/Navbar.vue";


export default {
  name: "Userdetails",
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
      photo : "",
      pin: null,
      password: null,
      currentPasswordCC: null,
      currentPasswordP: null,
      loaded: false,
      errors: [],

      config : {
        header : {
          'Content-Type' : 'multipart/form-data',
          'Authorization' : "Bearer " + localStorage.getItem("access_token")
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
      console.log(this.photo_url)
    },
    
    closeSuccessMesage: function () {
      this.showMessage = false;
    },
    save(){
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
      if(this.password != null){
        formData.append('password', this.password);
      }
      if(this.old_password != null){
        formData.append('old_password', this.old_password);
      }
      formData.append('_method', 'PUT')

      console.log(...formData.entries());

      this.errors = [];
      this.$axios.post(`/vcards/${this.$store.getters.username}`, formData, this.config)
        .then(response =>{
          this.vcard = response.data.data
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
      
      /*
    this.$axios.get(`/vcards/storage/900000002`)
    .then((response) => {
   
      var reader = new FileReader();

      reader.onload = (e) => {
        this.photo = e.target.result;
      }

      if (response){
        this.photo_url = btoa(response);
        console.log(this.photo_url);
        reader.readAsDataURL(this.photo_url);
      }
    })
    */
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

.align {
  display: flex;
  justify-content: right;
}

#page {
  text-align: center;
  margin: 0 auto;
}

#userdetails {
  background: #e6e6e6;
}

.icon {
  color: white;
  font-size: 3rem;
}

.iconTitle {
  color: white;
  margin-bottom: 10%;
}

.small-box {
  border-radius: 10px;
  cursor: pointer;
  margin-bottom: 10%;
  margin-top: 10%;
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

.btn-secondary {
  margin-top: 10%;
}

.details {
  margin-top: 1.5%;
}

.inputdetails {
  height: 60%;
}

.profileMoney {
  text-align: center;
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
