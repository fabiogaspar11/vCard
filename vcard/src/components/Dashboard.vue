<template>
  <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
  </div>
  <div id="dashboard" class=" justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottoms">
    <img src="../assets/img/vcard.png"> 
    <h1>{{moneyUser}}</h1>
    <a id="borderOption" href="#">
      <div class="background">
      <h2>DashBoard</h2>
      </div>
    </a>
  </div>
</template>

<script>
export default {
  name: 'Dashboard',
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
#dashboard{
  text-align: center;
  margin: 0 auto;
}
#borderOption{
  color: white;
  text-decoration: none;
}
.background{
  background-color: gray;
  width: 30%;
  
}

</style>
