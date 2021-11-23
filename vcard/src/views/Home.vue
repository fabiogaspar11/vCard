<template>
  <div id="page" class="row">
    <div>
      <img class="center" src="../assets/img/logo.png" />
    </div>
    <div id="buttons">
      <button
        class="button buttonRegister"
        @click="$router.push({ name: 'login' })"
      >
        Login
      </button>
    </div>
    <div id="buttons">
      <button class="button buttonRegister" @click="$router.push({name:'register'})" style="background-color: #78a1e3">
        Create a vCard
      </button>
    </div>
  </div>

  <router-view />
</template>

<script>
export default {
  name: "Menu",
  components: {},
  data() {
    return {
      money: 0,
    };
  },
  computed: {
    moneyUser: function () {
      return this.money + "€";
    },
  },
};
</script>



<style scoped lang="scss">
.center {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 50%;
}

#page {
  text-align: center;
  margin: 0 auto;
}

#buttons {
  display: flex;
  justify-content: center;
}

button {
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
}
</style>
