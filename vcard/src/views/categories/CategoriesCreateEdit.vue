<template>
        <label for="name"><b>Name:</b></label>
        <input type="text" @change="updateName" v-model="name" class="form-control" placeholder="Enter Name" name="name" required/>
        <div v-show="errors.name != undefined" class="text-danger">
          {{ errors.name }}
        </div>
        <div class="d-flex justify-content-center  align-items-center" >
          <label class="m-2" for="type"><b>Type Credit "{{previousType == 'C' ? 'D': 'C'}}"</b></label>
          <input class="m-2" type="checkbox" @click="updateType($event)" :value="previousType == 'C' ? 'D': 'C'" name="type" required/>
          
        </div>
        <div v-show="errors.type != undefined" class="text-danger">
          {{ errors.type }}
        </div>
        <div v-show="errors.default != undefined" class="text-danger">
          {{ errors.default }}
        </div>
</template>

<script>
export default {
  name: "DefaultCategoriesCreateEdit",
  props:{
    errors:Array,
    previousType:{
      type:String,
      required:false
    }
  },
  data() {
    return {
      name: null,
      vcard: null
    };
  },
  emits : [
    'updateType',
    'updateName',
    'getVcard'
  ],
  methods:{
    updateType(event) {
      let type;
      if(this.previousType == null && !event.target.checked){
        type = 'D';
      }
      else if(!event.target.checked){
        type = this.previousType;
      }else{
        type = event.target.value
      }    
      this.$emit('updateType', type)
      
    },
    updateName(){
      if (this.name != ""){
        this.$emit('updateName', this.name)
      }
      else{
        this.$emit('updateName', null)
      }
    },
    getVcard(){
      this.$emit('getVcard', this.vcard)
    }
  },
  created(){
   this.$axios.get(`/vcards/${this.$store.getters.username}`)
    .then(response =>{
      this.vcard = response.data.data
      this.getVcard()
    })
  },
}
</script>

<style>

</style>