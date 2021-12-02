<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
<div class="filter-bar">
            <div class="filter-item">
                <label>Estado</label>          
            </div>
            <div class="filter-item">
                <label>Vcard user phone number</label>
                <input type="number" class="form-control" name="cliente" >
            </div>
            <div class="filter-item">
                <label>Vcard user name</label>
                <input type="text" class="form-control" name="data" >
            </div>
            <div class="filter-item" style="flex-grow: 0">
                <label>Filtrar</label>
                <a type="submit" class="btn btn-info" style="height: 38px;">
                    <i class="bi bi-search-fill"></i>
                </a>
            </div>
        </div>
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
            <td>{{vcard.blocked == 1 ? 'Yes' : 'No'}}</td>
            <td>
              <div class="container">
                 <a class="btn btn-info m-1" role="button" aria-pressed="true"  @click="$router.push({ name: 'userdetailsAdmin', params:{phone_number : vcard.phone_number }})">
                  <i class="bi bi-arrows-fullscreen" style="color:white;margin-right:25%"></i>
                </a>
                <a class="btn btn-danger m-1" role="button" aria-pressed="true"  @click="deleteVcard(vcard.phone_number)">
                  <i class="bi bi-trash" style="color:white;margin-right:25%"></i>
                </a>
               <a :class="vcard.blocked == 1 ? 'btn btn-success m-1' : 'btn btn-warning m-1'" role="button" aria-pressed="true" @click="toggleStatusBlock(vcard.phone_number)">
                  <i :class="vcard.blocked == 1 ? 'bi bi-unlock-fill' : 'bi bi-lock-fill'" style="color:white;margin-right:25%"></i>      
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
      },
      toggleStatusBlock(phone_number){
          this.$axios
         .get(`/vcards/${parseInt(phone_number)}/alterBlock`)
         .then(response =>{
            this.vcards = response.data.data; 
            alert(`Vcard ${response.data.data.name} was ${response.data.data.blocked == 1 ? 'blocked': 'unblocked'}`);
            this.$router.push({name:'dashboardAdmin'})
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