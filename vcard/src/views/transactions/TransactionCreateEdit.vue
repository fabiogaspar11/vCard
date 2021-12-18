<template>
        <br>
          <label for="confirmationCode"><b>Confirmation Code: </b></label>
          <input
            type="password"
            v-model="confirmationCode"
             @change="updateConfirmationCode"
            class="form-control"
            placeholder="Enter Confirmation Code"
            name="confirmationCode"
            required
          />
          <div v-show="errors.confirmation_code != undefined" class="text-danger">
            {{ errors.confirmation_code }}
          </div>
          <br> 
        <div class="form-group" style="display: flex; flex-direction: column" >
            <label for="selectPaymentType"><b>Category</b></label>
            <select  @change="updateCategory" v-if="errorCategories==null" v-model="category" name="category" id="category" class="form-select" >
                <option v-for="category in categories" :key="category.id" :value="category.id" >
                    {{category.name}}
                </option>
            </select>
            <div v-else class="text-danger">
                {{ errorCategories }}
            </div>
          <div v-show="errors.category_id != undefined" class="text-danger">
                {{ errors.category_id }}
          </div>
        </div><br><br>
        <label for="description"><b>Description:</b></label>
        <input type="text" @change="updateDescription" v-model="description" class="form-control" placeholder="Enter Description" name="description" required/>
        <div v-show="errors.description != undefined" class="text-danger">
          {{ errors.description }}
        </div>
</template>

<script>

export default {
  name: "TransactionCreateEdit",
    props:{
    errors:Array,
    type:String
  },
  data() {
    return {
      description: null,
      category: null,
      categories: null,
      loadedCategories:false,
      errorCategories:null,
      phoneNumber : localStorage.getItem('username'),
      confirmationCode:null
    };
  },
  emits : [
    'updateCategory',
    'updateDescription',
    'updateConfirmationCode'
  ],
  methods:{
    updateConfirmationCode(){   
      this.$emit('updateConfirmationCode', this.confirmationCode)
    },
    updateCategory () {
      this.$emit('updateCategory', this.category)
    },
    updateDescription(){
      this.$emit('updateDescription', this.description)
    },

  },
  created(){
    this.$axios
      .get(`/vcards/${this.phoneNumber}/categories?type=${this.type}`)
      .then(response =>{
      this.categories = response.data.data; 
      this.loadedCategories = true;
    })
      .catch((error) => {
        this.errorCategories = error.response.data.error; 
        this.loadedCategories = false;
    });
  }
};
</script>

<style scoped>
</style>