<template>
  <div>
    <Sidebar></Sidebar>
    <Navbar></Navbar>
    <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    
    <h1 id="title"> Change {{fieldUpdate}} </h1>
    <div class="background">
        <div class="container-fluid">
              <div class="row">
                <div class="col-5">
                  <h5 style="padding-top: 4%;">Password</h5>
                </div>
                <div class="col-7">
                  <input type="password" class="form-control" v-model="old_password" />
                  <div v-show="errors.old_password != undefined" class="text-danger">{{errors.old_password}}</div>
                </div>
              </div>
              <div class="row">
                <div class="col-5">
                    <h5 style="padding-top: 5%;">{{fieldUpdate}}</h5>
                </div>
                <div class="col-7">
                    <input type="password" class="form-control" v-model="dynamicField"  />
                    <div v-show="errors.password != undefined" class="text-danger">{{errors.password}}</div>
                    <div v-show="errors.confirmation_code != undefined" class="text-danger">{{errors.confirmation_code}}</div>
                </div>
              </div>
              <div class="row d-flex justify-content-center">
                <div class="saveChange ">
                  <button type="button" class="btn btn-secondary" @click.prevent="updateField">Save</button>
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
#title{
  text-align: center;
  padding-top: 3%;
  padding-bottom: 3%;
}
.details {
  margin-top: 1.5%;
}

.background{
  background-color: #e6e6e6;
  width: 70%; 
  text-align:center; 
  margin: auto;
  padding-top: 3%;
  padding-bottom: 2%;
  border-radius: 10px;
}

.saveChange{
  width: 50%;
  padding-top: 2%;
}

</style>