<template>
   <SideBardAdmin></SideBardAdmin>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
        <h2>Create Administrator</h2>
        <label for="name"><b>Name:</b></label>
        <input type="text" v-model="name" class="form-control" placeholder="Enter Name" name="name" required/>
        <div v-show="errors.name != undefined" class="text-danger">
            {{ errors.name }}
        </div>
        <label for="email"><b>Email:</b></label>
        <input type="text" v-model="email" class="form-control" placeholder="Enter Email" name="email" required/>
        <div v-show="errors.email != undefined" class="text-danger">
            {{ errors.email }}
        </div>        
        <label for="psw"><b>Password:</b></label>
        <input type="password" v-model="password" placeholder="Enter password" name="psw" required />
        <div v-show="errors.password != undefined" class="text-danger">
        {{ errors.password }}
        </div>
        <button id="buttonSubmit" class="button" @click.prevent="createAdmin" >Create Administrator</button>
        <button id="buttonCancel" class="button" @click="$router.push({ name: 'administrators' })" >Cancel</button>
    </main>
</template>

<script>
import SideBardAdmin from "./../../components/SideBarAdmin.vue";

export default {
  name: "AdministratorCreate",
  components: {
    SideBardAdmin
  },
 data() {
    return {
      password: null,
      email: null,
      name: null,   
      errors: [],
     
    };
  },
  methods:{
      createAdmin(){
        let admin = {};
        if (this.name != null) {
          admin.name = this.name;
        }
        if(this.email != null){
          admin.email = this.email;
        }
        if (this.password != null) {
          admin.password = this.password;
        }
        this.$axios
        .post(`/administrators`, admin)
        .then(() => {
          this.$router.push({name: "dashboardAdmin" });
        })
        .catch((error) => {
          this.errors = [];
          Object.entries(error.response.data.errors).forEach(([key, val]) => {
            this.errors[key] = val[0];
          });
        });

      }
  }
}</script>

<style>

</style>