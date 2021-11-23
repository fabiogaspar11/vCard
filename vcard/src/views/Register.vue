<template>
  <br />
  <div class="imgCenter">
    <img id="logo" src="../assets/img/logo.png" style="max-width: 100%" />
  </div>
  <div class="imgCenter">
    <h1>Create a vCard</h1>
  </div>
  <div class="container">
    <label for="uname"><b>Phone Number:</b></label>
    <input
      type="text"
      v-model="phoneNumber"
      placeholder="Enter Phone Number"
      name="uname"
      required
    />
    <div v-show="errors.phone_number != undefined" class="text-danger">
      {{ errors.phone_number }}
    </div>
    <br />
    <label for="psw"><b>Password:</b></label>
    <input
      type="password"
      v-model="password"
      placeholder="Enter password"
      name="psw"
      required
    />
    <div v-show="errors.password != undefined" class="text-danger">
      {{ errors.password }}
    </div>
    <br />
    <!--
    <label for="psw"><b>Email:</b></label>
    <input type="text" v-model="email" placeholder="Enter email" name="psw" required>
    <div v-show="errors.email != undefined" class="text-danger">{{errors.email}}</div>
    <br>
    -->
    <label for="psw"><b>PIN:</b></label>
    <input
      type="password"
      v-model="pin"
      placeholder="Enter PIN"
      name="psw"
      required
    />
    <div v-show="errors.confirmation_code != undefined" class="text-danger">
      {{ errors.confirmation_code }}
    </div>
    <br />
    <button
      id="buttonRegister"
      class="button"
      type="submit"
      @click.prevent="createVcard"
    >
      Create a vCard
    </button>
  </div>
  <!--
  <div class="container" style="background-color: #f1f1f1">
    <button
      id="buttonCancel"
      class="button"
      @click="$router.push({ name: 'home' })"
    >
      Cancel
    </button>
  </div>
  -->
</template>

<script>
import axios from "axios";

export default {
  data() {
    return {
      phoneNumber: null,
      password: null,
      //email: null,
      pin: null,
      errors: [],
    };
  },
  methods: {
    createVcard() {
      let vcard = {};

      if (this.phoneNumber != null) {
        vcard.phone_number = this.phoneNumber;
      }
      vcard.name = "<Undefined>";

      vcard.email = "<Undefined>";

      /*
    if(this.email != null){
        vcard.email = this.email;
    }
    */

      if (this.password != null) {
        vcard.password = this.password;
      }

      if (this.pin != null) {
        vcard.confirmation_code = this.pin;
      }

      this.$axios
        .post(`/vcards`, vcard)
        .then((response) => {
          console.log(response);
          console.log(this.password);
          console.log(this.phoneNumber);
          this.$store
            .dispatch("authRequest", {
              username: this.phoneNumber,
              password: this.password,
            })
            .then(() => {
              axios.defaults.headers.common.Authorization =
                "Bearer " + response.data.access_token;
              localStorage.setItem("access_token", response.data.access_token);
              localStorage.setItem("phone_number", this.phone_number);
            })
            .then(() => {
              this.$router.push({
                name: "dashboard",
                params: {
                  successMessage:
                    "Vcard created with success (Click me to close)",
                },
              });
            })
            .catch((error) => {
              localStorage.removeItem("access_token");
              localStorage.removeItem("phone_number");
              delete axios.defaults.headers.common.Authorization;
              this.errors = error.response.data;
            });
          this.errors = [];
          this.phoneNumber = null;
          this.email = null;
          this.password = null;
          this.pin = null;
        })
        .catch((error) => {
          this.errors = [];
          Object.entries(error.response.data.errors).forEach(([key, val]) => {
            this.errors[key] = val[0];
          });
        });
    },
  },
};
</script>

<style scoped>
/* Bordered form */
#logo {
  width: auto;
  max-width: 100%;
  height: auto;
  margin: auto;
}
#page {
  text-align: center;
}

#buttonCancel {
  background-color: #ff6666;
}
#buttonCancel:hover {
  transition-duration: 0.5s;
  background-color: red;
}

#buttonRegister {
  background-color: #04aa6d;
}
#buttonRegister:hover {
  transition-duration: 0.5s;
  background-color: #036340;
}

.imgCenter {
  display: flex;
  justify-content: center;
}

form {
  border: 3px solid #f1f1f1;
}

/* Full-width inputs */
input[type="text"],
input[type="password"] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
button {
  background-color: #04aa6d;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}

/* Add a hover effect for buttons */
button:hover {
  opacity: 0.8;
}

/* Extra style for the cancel button (red) */
.cancelbtn {
  width: auto;
  padding: 10px 18px;
  background-color: #f44336;
}

/* Center the avatar image inside this container */
.imgcontainer {
  text-align: center;
  margin: 24px 0 12px 0;
  display: flex;
  justify-content: center;
}

/* Avatar image */
img.avatar {
  width: 40%;
  border-radius: 50%;
}

/* Add padding to containers */
.container {
  padding: 16px;
}

/* The "Forgot password" text */
span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
    display: block;
    float: none;
  }
  .cancelbtn {
    width: 100%;
  }
}
</style>