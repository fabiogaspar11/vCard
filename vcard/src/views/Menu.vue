<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Menu</h1>
  </div>
  <div id="page" class="row">
    <div>
      <img class="center" src="../assets/img/logo.png"> 
    </div>
    <div id="buttons">
         <button class="button buttonRegister" @click="$router.push('Login')"> Login </button>
         <button class="button buttonRegister" @click="$router.push('Register')"> Register </button>
    </div>
  </div>

<router-view/>
</template>

<script>
export default {
  name: 'Menu',
  components: {
    
  },
  data() {
    return {
      money: 0
    }
  },
  computed: {
    moneyUser: function() {
      return this.money + "€";
    }
  }
}
</script>



<style scoped lang="scss">
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
 
}

#page{
  text-align: center;
  margin: 0 auto;
}

#buttons{
   text-align: center;
   display: flex;
   justify-content:center;
}

  button {
    background-color: #04AA6D;
    color: white;
    padding: 14px 20px;
    margin: 8px 0;
    border: none;
    cursor: pointer;
    width: 100%;
  }

</style>
