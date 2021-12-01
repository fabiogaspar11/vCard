<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

      <h1>Vcards</h1>   
      <table class="table">
        <thead>
          <tr>
            <th>Phone Number</th>
            <th>Name</th>
            <th>Balance</th>
            <th>Blocked</th>
            <th>Options</th>
            <th>Change Debit</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="vcard in vcards" :key="vcard.id">
            <td>{{ vcard.phone_number }}</td>
            <td>{{ vcard.name }}</td>
            <td>{{ vcard.balance }}</td>
            <td>{{ vcard.blocked }}</td>
            <td>
              <div class="container">
                <a class="btn btn-danger m-1" role="button" aria-pressed="true"  @click="deleteVcard(vcard.phone_number)">
                  <i class="bi bi-trash" style="color:white;margin-right:25%"></i>
                </a>
               <a v-if="vcard.blocked == 1" class="btn btn-success m-1" role="button" aria-pressed="true">
                  <i class="bi bi-unlock-fill" style="color:white;margin-right:25%"></i>      
                </a>
               <a v-else class="btn btn-warning m-1" role="button" aria-pressed="true">
                  <i class="bi bi-lock-fill" style="color:white;margin-right:25%"></i>      
                </a>            
              </div>
            </td>
            <td>
                <div class="container">
                <a class="btn btn-primary m-1" role="button" aria-pressed="true">
                  <i class="bi bi-credit-card" style="color:white;margin-right:25%"></i>
                </a>
                </div>
            </td>
          </tr>
        </tbody>
      </table>
  </main>
</template>

<script>
import SideBardAdmin from "../../components/SideBarAdmin.vue";
export default {
  name: "Vcards",
  components: {
    SideBardAdmin,
  },
  data() {
    return {
      vcards: null
    };
  },
  methods:{
      deleteVcard(phone_number){
         this.$axios
         .delete(`/vcards/${parseInt(phone_number)}`)
         .then(response =>{
            this.vcards = response.data.data; 
         })
         .catch((error)=>{
           console.log(error.response.data)
         });
      }
  },
  mounted() {
     this.$axios
      .get(`/vcards`)
      .then(response =>{
      this.vcards = response.data.data; 
    });
  },
};
</script>

<style>
</style>