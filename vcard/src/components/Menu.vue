<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Menu</h1>
  </div>
  <div id="page" class="row">
    <div id="dashboard" >
      <img class="center" src="../assets/img/vcard.png"> 
      <h1 style="margin-bottom: 3%;">{{moneyUser}}</h1>
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

.icon{ 
  color: white;
  font-size:3rem;
}

.iconTitle{
  color: white;
  margin-bottom: 10%;
}

.buttonUserDetails{
  background: #17a2b8;
}
.buttonUserDetails:hover{
  background: #0e6471;
  transition-duration: 0.5s;
}


.buttonSendReceive{
   background: #198754;
}
.buttonSendReceive:hover{
  background: #105635;
  transition-duration: 0.5s;
}


.buttonTransactions{
  background: #ffc107;
}
.buttonTransactions:hover{
  background: #b38600;
  transition-duration: 0.5s;
}


.buttonPiggyBank{
  background: #dc3545;
}
.buttonPiggyBank:hover{
  background: #981b27;
  transition-duration: 0.5s;
}

.small-box {
  border-radius: 10px;
  cursor: pointer;
  margin-bottom: 10%;
  margin-top: 10%;
  
}
</style>
