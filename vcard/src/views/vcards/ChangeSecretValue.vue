<template>
  <div>
    <Sidebar></Sidebar>
    <Navbar></Navbar>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

    <div class="color m-2" >
        <div class="container">
              <div class="row">
                 <div class="col">
                        <h5>Password</h5>
                    </div>
                    <div class="col">
                        <input type="password" class="form-control" v-model="old_password" />
                        <div v-show="errors.old_password != undefined" class="text-danger">{{errors.old_password}}</div>
                    </div>
              </div>
              <div class="row">
                <div class="col details">
                    <h5>{{fieldUpdate}}</h5>
                </div>
                <div class="col">
                    <input type="password" class="form-control" v-model="dynamicField"  />
                    <div v-show="errors.password != undefined" class="text-danger">{{errors.password}}</div>
                    <div v-show="errors.confirmation_code != undefined" class="text-danger">{{errors.confirmation_code}}</div>
                </div>
              </div>
              <div class="right">
              <button type="button" class="btn btn-primary" @click.prevent="updateField">Save</button>
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
  name: "ChangeSecretValue",
  components: {
    Sidebar,
    Navbar,
  },
  props:{
     isUpdatePassword:{
      type:String,
      required:true
    },
    fieldUpdate:{
      type:String,
      required:true
    }
  },
   data() {
    return {
      old_password: null,
      dynamicField:null,
      errors:{},
    }
  },
  methods:{
      updateField(){
        var URL = '';
        var message = '';
        let username = this.$store.getters.username;
        var object={};
        if(this.old_password != null){
           object.old_password  = this.old_password ;
        }
        if(this.isUpdatePassword == 1){
            URL = `vcards/${username}/password/change`;
            if(this.dynamicField != null){
                object.password  = this.dynamicField;
            }
            message = 'Password was succesfully updated'
          }else{
            URL = `vcards/${username}/confirmationCode/change`;
            if(this.dynamicField != null){
                object.confirmation_code  = this.dynamicField;
            }
            message = 'Confirmation code was succesfully updated'
          }

          this.$axios.put(URL, object)
            .then(() => {
              this.$toast.success(message);
              this.$router.push({name: "dashboard"});
            })
            .catch((error) => {
                this.errors = [];
                Object.entries(error.response.data.errors).forEach(([key, val]) => {
                    this.errors[key] = val[0];
                });
            })
         }
      }
 }


</script>

<style>
.right {
  display: block;
  margin-left: auto;
  margin-right: 0;
  width: 50%;
}
.details {
  margin-top: 1.5%;
}


</style>