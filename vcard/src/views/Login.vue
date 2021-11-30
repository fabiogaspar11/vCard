<template>
  <br />
  <div class="imgCenter">
    <img id="logo" src="../assets/img/logo.png" />
  </div>
  <div class="imgCenter">
    <h1>Login</h1>
  </div>
  <div class="container">
    <label for="uname"><b>Phone Number:</b></label>
    <input
      type="text"
      placeholder="Enter Phone Number"
      name="uname"
      v-model="phone_number"
      required
    />
    <div v-show="errors.phoneNumber != undefined" class="text-danger">
      {{ errors.phoneNumber }}
    </div>
    <label for="psw"><b>Password:</b></label>
    <input
      type="password"
      placeholder="Enter password"
      name="psw"
      v-model="password"
      required
    />
    <div v-show="errors.password != undefined" class="text-danger">
      {{ errors.password }}
    </div>
    <div v-show="errors.login != undefined" class="text-danger">
      {{ errors.login }}
    </div>
    <button
      type="submit"
      id="buttonLogin"
      class="button"
      @click.prevent="login"
    >
      Login
    </button>
  </div>

  <div class="container" style="background-color: #f1f1f1">
    <button
      id="buttonCancel"
      class="button"
      @click="$router.push({ name: 'home' })"
    >
      Cancel
    </button>
  </div>
</template>

<script>
import axios from "axios";

export default {
  name: "login",
  data() {
    return {
      phone_number: localStorage.getItem("phone_number") || null,
      password: "",
      errors: [],
    };
  },
  methods: {
    login() {
      this.$store
        .dispatch("authRequest", {
          username: this.phone_number,
          password: this.password,
        })
        .then((response) => {
          axios.defaults.headers.common.Authorization =
            "Bearer " + response.data.access_token;
          localStorage.setItem("access_token", response.data.access_token);
          localStorage.setItem("phone_number", this.phone_number);
        })
        .then(() => {
          this.$router.push({
            name: "dashboard",
          });
        })
        .catch((error) => {
          localStorage.removeItem("access_token");
          localStorage.removeItem("phone_number");
          delete axios.defaults.headers.common.Authorization;
          this.errors = error.response.data;
        });
    },
  },
};
</script>



<style scope>
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

#buttonLogin {
  background-color: #04aa6d;
}
#buttonLogin:hover {
  transition-duration: 0.5s;
  background-color: #036340;
}

.container {
  text-align: center;
}

.button {
  border-radius: 10px;
}

.imgCenter {
  display: flex;
  justify-content: center;
}

/* Bordered form */
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