<template>
<form action="action_page.php" method="post">
  <div id="page" class="row">

    <img id="logo" src="../assets/img/logo.png"> 

  </div>  

  <div class="container">
    <label for="uname"><b>Phone Number:</b></label>
    <input type="text" placeholder="Enter Username" name="uname" required>

    <label for="psw"><b>Password:</b></label>
    <input type="password" placeholder="Enter password" name="psw" required>

    <button type="submit" id="buttonLogin" class="button">Login</button>
  </div>
  <div class="container" >
    <button id="buttonCancel" class="button" @click="$router.push('/')"> Cancel </button>
 
  </div>
</form>
</template>



<script>
export default {

}
</script>



<style scope>
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

#buttonLogin{
  background-color: #04AA6D;
}
#buttonLogin:hover{
  transition-duration: 0.5s;
  background-color: #036340;
}

.container{
  text-align: center;
}

.button{
  border-radius: 10px;
}

/* Bordered form */
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