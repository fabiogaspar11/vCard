<template>
  <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <h2>Vcard Details</h2>

    <div class="container-md bg-light rounded m-4" v-if="loaded">
      <div class="row">
        <div class="col-sm m-2">
          <p><b>Phone Number: </b>{{ vcard.phone_number }}</p>
        </div>
        <div class="col-sm m-2">
          <p><b>Name: </b>{{ vcard.name }}</p>
        </div>
        <div class="col-sm m-2"></div>
      </div>
      <div class="row d-flex align-items-center">
        <div class="col-sm m-2">
          <p><b>Balance: </b>{{ vcard.balance }}</p>
        </div>
        <div class="col-sm d-flex align-items-center m-2">
          <p><b>Debit limit: </b>{{ vcard.max_debit }}</p>
        </div>
        <div class="col-sm d-flex align-items-center m-2">
          <img
            class="m-2"
            :src="$serverURL + '/storage/fotos/' + vcard.photo_url"
            alt="Vcard user photo"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-sm m-2">
          <p><b>Email: </b>{{ vcard.email }}</p>
        </div>
        <div class="col-sm m-2">
          <p><b>Blocked: </b>{{ vcard.blocked == 1 ? "Yes" : "No" }}</p>
        </div>
        <div class="col-sm m-2"></div>
      </div>
    </div>
  </main>
</template>

<script>
import SideBardAdmin from "../../components/SideBarAdmin.vue";
export default {
  name: "VcardDetailsAdmin",
  components: {
    SideBardAdmin,
  },
  props: {
    phone_number: Number,
  },
  methods: {
    getDetails() {
      this.$axios
        .get(`vcards/${this.phone_number}`)
        .then((response) => {
          this.vcard = response.data.data;
          this.loaded = true;
        })
        .catch((error) => {
          if (error.response.status == 404) {
            this.$toast.error("This vcard could not be found");
            this.$router.push({ name: "dashboardAdmin" });
          }
        });
    },
  },
  data() {
    return {
      vcard: null,
      loaded: false,
    };
  },
  created() {
    this.getDetails();
  },
  computed: {
    changesListOfVcards() {
      return this.$store.getters.changesListOfVcards;
    },
  },
  watch: {
    changesListOfVcards: {
      handler() {
        if (this.$store.getters.changesListOfVcards) this.getDetails();
      },
      deep: true,
    },
  },
};
</script>

<style>
</style>