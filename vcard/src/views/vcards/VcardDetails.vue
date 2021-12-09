<template>
  <div>
    <Sidebar></Sidebar>
    <Navbar></Navbar>

    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
      <br />
      <div class="imgCenter color">
        <img :src="photo" style="max-width: 50%; max-height: 270px" />
      </div>
      <div class="form-group">
        <input
          type="file"
          v-on:change="onFileChange"
          class="form-control"
          name="imagem_url"
          id="inputFoto"
          style="height: auto"
        />
      </div>

      <div v-show="errors.photo_url != undefined" class="text-danger">
        {{ errors.photo_url }}
      </div>
      <br />
      <div class="container color" v-if="this.vcard != null">
        <label for="name"><b>Name:</b></label>
        <input
          type="text"
          class="form-control inputdetails"
          placeholder="Name"
          v-model="this.name"
        />
        <div v-show="errors.name != undefined" class="text-danger">
          {{ errors.name }}
        </div>
        <br />

        <label for="email"><b>Email:</b></label>
        <input
          type="text"
          class="form-control inputdetails"
          placeholder="Email"
          v-model="this.email"
        />
        <div v-show="errors.email != undefined" class="text-danger">
          {{ errors.email }}
        </div>

        <button type="button" class="btn btn-primary" @click.prevent="save">
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
        (this.photo = "http://laravelapi.test/storage/fotos/" + this.photo_url),
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
  border-radius: 30%;
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
