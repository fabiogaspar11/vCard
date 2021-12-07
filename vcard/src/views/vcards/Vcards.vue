<template>
   <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container d-flex flex-column">
      <div class="container d-flex justify-content-between align-items-center" >

            <div class="w-50 m-2">
                <label>State</label>  
                <select v-model="stateFilter"  class="form-select" >
                <option value="1" >Blocked</option>
                <option value="0" >Not Blocked</option>
                </select>       
            </div>
            <div class="w-50 m-2">
                <label>Vcard Phone number</label>
                <input type="number"  class="form-control"  v-model="phoneNumberSearch">
            </div>
      </div>
      <div class="container d-flex justify-content-between align-items-center" >
      <label class="w-25">Vcard Name</label>
        <div class="w-75">       
            <input type="text" class="form-control" v-model="nameSearch">
        </div>
        <div class="text-end m-2">
          <a type="submit" class="btn btn-info">
            <i class="bi bi-search" style="color:white;margin-right:25%" @click="submitSearch"></i>
          </a>
        </div>
      </div>
    </div>
    <h2>Vcards</h2>   
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
        <tr v-for="vcard in vcards" :key="vcard.phone_number">
          <td>{{ vcard.phone_number }}</td>
          <td>{{ vcard.name }}</td>
          <td>{{ vcard.balance }}</td>
          <td>{{vcard.blocked == 1 ? 'Yes' : 'No'}}</td>
          <td class="w-25">
            <div class="container w-75">
                <a class="btn btn-info m-1 btn-sm" role="button" aria-pressed="true"  @click="$router.push({ name: 'userdetailsAdmin', params:{phone_number : vcard.phone_number }})">
                <i class="bi bi-arrows-fullscreen" style="color:white;margin-right:25%"></i>
              </a>
              <a class="btn btn-danger m-1 btn-sm" role="button" aria-pressed="true"  @click="deleteVcard(vcard.phone_number)">
                <i class="bi bi-trash" style="color:white;margin-right:25%"></i>
              </a>
              <a :class="vcard.blocked == 1 ? 'btn btn-success m-1 btn-sm ' : 'btn btn-warning m-1 btn-sm'" role="button" aria-pressed="true" @click="toggleStatusBlock(vcard.phone_number)">
                <i :class="vcard.blocked == 1 ? 'bi bi-unlock-fill' : 'bi bi-lock-fill'" style="color:white;margin-right:25%"></i>      
              </a>          
            </div>
          </td>
          <td  class="w-25">
              <div class="container d-flex flex-row">
              <a class="btn btn-primary m-1 btn-sm" role="button" aria-pressed="true" @click="selectVcard(vcard)">
                <i class="bi bi-credit-card" style="color:white;margin-right:25%"></i>
              </a>           
              <input v-if="selectedVcard === vcard" class="form-control w-50" type="number" v-model="newDebitLimit" >
              <a v-if="selectedVcard === vcard" class="btn btn-success m-1 btn-sm" role="button" aria-pressed="true" @click="changeDebidLimit(vcard.phone_number, vcard.max_debit)">
                <i class=" bi bi-check-circle-fill" style="color:white;margin-right:25%"></i>
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
      vcards: null,
      newDebitLimit:null,
      debitButtonclicked:false,
      selectedVcard:null,
      phoneNumberSearch:null,
      nameSearch:null,
      stateFilter:null,
       config : {
        header : {
          'Content-Type' : 'application/json'
        }
     }
    };
  },
  methods:{
    submitSearch(){
      let values = {};
      if(this.stateFilter == null && this.nameSearch == null && this.phoneNumberSearch == null){
          this.$toast.error("No filter information provided");
          return;
      }
      if(this.stateFilter != null){       
        values.state = this.stateFilter;
      }
      if(this.nameSearch != null){       
        values.name = this.nameSearch;     
      }
       if(this.phoneNumberSearch != null){       
         values.phone_number = this.phoneNumberSearch;
      }
 
       this.$axios
         .get("/vcards/filter", {params:values})
         .then(response =>{
            this.vcards = response.data.data; 
         })
         .catch((error)=>{
           console.log(error.response.data)
         });
    },
      deleteVcard(phone_number){
         this.$axios
         .delete(`/vcards/${parseInt(phone_number)}`)
         .then(() =>{
           this.$toast.info(`Vcard ${phone_number} removed`);
           this.$socket.emit('userDeleted', phone_number);   
            this.$axios
            .get(`/vcards`)
            .then(response =>{
            this.vcards = response.data.data;
            })
         })
         .catch((error)=>{
           console.log(error.response.data)
         });
      },
      selectVcard(vcard) {
            this.selectedVcard = vcard;
      },
      changeDebidLimit(phone_number, previousDebitLimit){
        if(this.newDebitLimit==null){
            this.$toast.error("Must insert new debit limit");
            return;
        }
        if(this.newDebitLimit==previousDebitLimit){
            this.$toast.error("New debit limit equal to the old debit limit - nothing to update");
            return;
        }
        let vcardDebit={};
        vcardDebit.max_debit = this.newDebitLimit;
         this.$axios
         .put(`/vcards/${parseInt(phone_number)}/alterDebitLimit`, vcardDebit)
         .then(response =>{
            this.vcards = response.data.data; 
            this.$toast.info(`Debit lemit of Vcard ${phone_number} was updated`);
            this.$router.push({name:'dashboardAdmin'})
         })
         .catch((error)=>{
            this.$toast.info("Error updating the debit limit "+error.response.data.max_debit);
         });
      },
      toggleStatusBlock(phone_number){
          this.$axios
         .get(`/vcards/${parseInt(phone_number)}/alterBlock`)
         .then(response =>{
            this.vcards = response.data.data; 
            this.$toast.info(`Vcard ${response.data.data.name} was ${response.data.data.blocked == 1 ? 'blocked': 'unblocked'}`);
            this.$socket.emit('toggleVcardStatus', response.data.data.blocked == 1, phone_number);
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