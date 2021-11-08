<template>
<form action="action_page.php" method="post" enctype="multipart/form-data">
  <div id="page" class="row">
      <img id="logo" src="../assets/img/logo.png"/>
  </div>    

  <div class="container">
    
    <label for="uname"><b>Phone Number:</b></label>
    <input type="text" v-model="phoneNumber" placeholder="Enter Username" name="uname" required>

    <label for="psw"><b>Name:</b></label>
    <input type="text" v-model="name"  placeholder="Enter name" name="psw" required>

    <label for="psw"><b>Password:</b></label>
    <input type="password" v-model="password" placeholder="Enter password" name="psw" required>

    <div class="form-group">
      <label for="img" class="btn btn-dark">Upload a Photo</label>
      <input type="file" @change="changeFile($event)" id="img" style="display:none">
      <span v-if="imageFile!=null">File '{{imageFile.name}}' uploaded</span>
    </div>
    <br>
    <label for="psw"><b>Email:</b></label>
    <input type="text" v-model="email" placeholder="Enter email" name="psw" required>
    
    <label for="psw"><b>Confirmation code:</b></label>
    <input type="password"  v-model="confirmationCode" placeholder="Enter confirmation code" name="psw" required>

    
    <button id="buttonRegister" class="button" type="submit" @click.prevent="createVcard">Register</button>
  </div>
  <div class="container" style="background-color:#f1f1f1">
     <button id="buttonCancel" class="button" @click="$router.push('/')"> Cancel </button>
  </div>
</form>
</template>

<script>
import axios from 'axios'
const urlBase = 'http://laravelapi.test/api'
export default {
  data () {
        return {
            phoneNumber: null,
            name: null,
            password: null,
            confirmationCode: null,
            imageFile : null
        }
    },
  methods: {
    changeFile (evt){
        this.imageFile =  evt.target.files[0];
    },
    createVcard () {
      var headers = {
      'Content-Type':  "multipart/form-data"
    }
    let vcard = {
      phone_number: this.phoneNumber,
      name: this.name,
      email: this.email,
      photo_url: this.imageFile,
      password: this.password,
      confirmation_code: this.confirmationCode
    }
    axios.post(`${urlBase}/vcards`,vcard,headers)
    .then(function (response) {
      console.log(response);
    })
    .catch(function (error) {
      console.log(error.response);
    });

    },
    }
}
</script>

<style scoped>
/* Bordered form */
#logo{
  width: auto ;
  max-width: 100% ;
  height: auto ;
    margin: auto;
}
#page{
  text-align: center;

}

#buttonCancel{
  background-color: #ff6666;
}
#buttonCancel:hover{
  transition-duration: 0.5s;
  background-color: red;
}

#buttonRegister{
  background-color: #04AA6D;
}
#buttonRegister:hover{
  transition-duration: 0.5s;
  background-color: #036340;
}





form {
  border: 3px solid #f1f1f1;
}

/* Full-width inputs */
input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

/* Set a style for all buttons */
button {
  background-color: #04AA6D;
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