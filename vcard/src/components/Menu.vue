<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Menu</h1>
  </div>
  <div id="page" class="row">
    <div id="dashboard">
      <img class="center" src="../assets/img/logo.png"> 
    </div>
      <div id="buttons" class="col-md-3 col-md-offset-5">
        <div class="small-box buttonSendReceive">                  
            <i class="bi bi-cash-coin icon"></i> 
            <h4><i class="iconTitle"> Register </i> </h4> <br>               
        </div>
      <button class="button buttonRegister">Register</button>
    </div>
</div>
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

#dashboard{
  background: #e6e6e6;
}

#buttons{
   text-align: center;
   padding-top: 3%;
}

.buttonSendReceive{
   background: #198754;
  
}
.buttonSendReceive:hover{
  background: #105635;
  transition-duration: 0.5s;
}

.icon{ 
  color: white;
  font-size:3rem;
}

.small-box {
  border-radius: 10px;
  cursor: pointer;
  margin-bottom: 10%;
  margin-top: 10%;
  
}
</style>
