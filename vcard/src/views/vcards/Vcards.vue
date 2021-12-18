<template>
  <SideBardAdmin></SideBardAdmin>
  <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="container d-flex flex-column">
      <div class="container d-flex justify-content-between align-items-center">
        <div class="w-50 m-2">
          <label>State</label>
          <select v-model="stateFilter" class="form-select">
            <option value="1">Blocked</option>
            <option value="0">Not Blocked</option>
          </select>
        </div>
        <div class="w-50 m-2">
          <label>Vcard Phone number</label>
          <input
            type="number"
            class="form-control"
            v-model="phoneNumberSearch"
          />
        </div>
      </div>
      <div class="container d-flex justify-content-between align-items-center">
        <label class="w-25">Vcard Name</label>
        <div class="w-75">
          <input type="text" class="form-control" v-model="nameSearch" />
        </div>
        <div class="text-end m-2">
          <a type="submit" class="btn btn-info" @click="submitSearch">
            <i class="bi bi-search" style="color: white; margin-right: 25%"></i>
          </a>
        </div>
        <div class="text-end m-1">
          <a type="submit" class="btn btn-secondary" @click="clearFilters">
            Clear
          </a>
        </div>
      </div>
    </div>
    <h2>Vcards</h2>
    <nav aria-label="Page navigation example">
      <ul class="pagination d-flex justify-content-center">
        <li class="page-item">
          <a
            class="page-link"
            style="color: black"
            href="#"
            @click.prevent="getPreviousPage()"
            >Previous</a
          >
        </li>
        <li class="page-item">
          <a class="page-link" style="color: black" href="#">{{
            this.pageActual
          }}</a>
        </li>
        <li class="page-item">
          <a
            class="page-link"
            style="color: black"
            href="#"
            @click.prevent="getNextPage()"
            >Next</a
          >
        </li>
      </ul>
    </nav>
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
          <td>{{ vcard.blocked == 1 ? "Yes" : "No" }}</td>
          <td class="w-25">
            <div class="container w-75">
              <a
                class="btn btn-info m-1 btn-sm"
                role="button"
                aria-pressed="true"
                @click="
                  $router.push({
                    name: 'vcardDetailsAdmin',
                    params: { phone_number: vcard.phone_number },
                  })
                "
              >
                <i
                  class="bi bi-arrows-fullscreen"
                  style="color: white; margin-right: 25%"
                ></i>
              </a>
              <a
                class="btn btn-danger m-1 btn-sm"
                role="button"
                aria-pressed="true"
                @click="deleteVcard(vcard.phone_number)"
              >
                <i
                  class="bi bi-trash"
                  style="color: white; margin-right: 25%"
                ></i>
              </a>
              <a
                :class="
                  vcard.blocked == 1
                    ? 'btn btn-success m-1 btn-sm '
                    : 'btn btn-warning m-1 btn-sm'
                "
                role="button"
                aria-pressed="true"
                @click="toggleStatusBlock(vcard.phone_number)"
              >
                <i
                  :class="
                    vcard.blocked == 1 ? 'bi bi-unlock-fill' : 'bi bi-lock-fill'
                  "
                  style="color: white; margin-right: 25%"
                ></i>
              </a>
            </div>
          </td>
          <td class="w-25">
            <div class="container d-flex flex-row">
              <a
                class="btn btn-primary m-1 btn-sm"
                role="button"
                aria-pressed="true"
                @click="selectVcard(vcard)"
              >
                <i
                  class="bi bi-credit-card"
                  style="color: white; margin-right: 25%"
                ></i>
              </a>
              <input
                v-if="selectedVcard === vcard"
                class="form-control w-50"
                type="number"
                v-model="newDebitLimit"
              />
              <a
                v-if="selectedVcard === vcard"
                class="btn btn-success m-1 btn-sm"
                role="button"
                aria-pressed="true"
                @click="changeDebidLimit(vcard.phone_number, vcard.max_debit)"
              >
                <i
                  class="bi bi-check-circle-fill"
                  style="color: white; margin-right: 25%"
                ></i>
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
      newDebitLimit: null,
      debitButtonclicked: false,
      selectedVcard: null,
      phoneNumberSearch: null,
      nameSearch: null,
      stateFilter: null,
      queryString: null,
      pageActual: 1,
      lastPage: null,
      config: {
        header: {
          "Content-Type": "application/json",
        },
      },
    };
  },
  methods: {
    submitSearch() {
      this.pageActual = 1;
      this.getVcardsWithFilter();
    },
    deleteVcard(phone_number) {
      this.$axios
        .delete(`/vcards/${parseInt(phone_number)}`)
        .then(() => {
          this.$toast.info(`Vcard ${phone_number} removed`);
          this.$socket.emit("userDeleted", phone_number);
          this.$socket.emit("changesListOfVcards");
          this.$axios.get(`/vcards`).then(() => {
            console.log(this.vcards.length);
            if (this.vcards.length == 1) {
              this.pageActual -= 1;
            }
            this.getVcards();
          });
        })
        .catch((error) => {
          if (error.response.status == 404) {
            this.$toast.error("This vcard could not be found");
            this.$router.push({ name: "dashboardAdmin" });
          }
          if (error.response.status == 422) {
            this.$toast.error("This vcard could not be deleted");
          }
        });
    },
    selectVcard(vcard) {
      this.selectedVcard = vcard;
    },
    changeDebidLimit(phone_number, previousDebitLimit) {
      if (this.newDebitLimit == null) {
        this.$toast.error("Must insert new debit limit");
        return;
      }
      if (this.newDebitLimit == previousDebitLimit) {
        this.$toast.error(
          "New debit limit equal to the old debit limit - nothing to update"
        );
        return;
      }
      if (this.newDebitLimit <= 0) {
        this.$toast.error("New debit limit must be bigger than 0");
        return;
      }
      let vcardDebit = {};
      vcardDebit.max_debit = this.newDebitLimit;
      this.$axios
        .put(`/vcards/${parseInt(phone_number)}/alterDebitLimit`, vcardDebit)
        .then((response) => {
          this.vcards = response.data.data;
          this.$toast.info(`Debit lemit of Vcard ${phone_number} was updated`);
          this.$socket.emit("changesListOfVcards");        
          this.$router.push({ name: "dashboardAdmin" });
        })
        .catch((error) => {
          if (error.response.status == 404) {
            this.$toast.error("This vcard could not be found");
            this.$router.push({ name: "dashboardAdmin" });
          }
          if (error.response.status == 422) {
            this.$toast.error("Could not update debit limit");
          }
        });
    },
    toggleStatusBlock(phone_number) {
      this.$axios
        .get(`/vcards/${parseInt(phone_number)}/alterBlock`)
        .then((response) => {
          let message =
            response.data.data.blocked == 1 ? "blocked" : "unblocked";
          this.$toast.info(`Vcard ${response.data.data.name} was ${message}`);
          this.$socket.emit("toggleVcardStatus",response.data.data.blocked == 1, phone_number);
           this.$socket.emit("changesListOfVcards");
          this.getVcards();
        })
        .catch((error) => {
          if (error.response.status == 404) {
            this.$toast.error("This vcard could not be found");
            this.$router.push({ name: "dashboardAdmin" });
          }
        });
    },
    getPreviousPage() {
      if (this.pageActual > 1) {
        this.pageActual--;
        if (this.queryString != null) {
          this.getVcardsWithFilter();
        } else {
          this.getVcards();
        }
      }
    },
    getNextPage() {
      if (this.pageActual < this.lastPage) {
        this.pageActual++;
        if (this.queryString != null) {
          this.getVcardsWithFilter();
        } else {
          this.getVcards();
        }
      }
    },
    getVcards() {
      this.$axios.get(`/vcards?page=${this.pageActual}`).then((response) => {
        this.vcards = response.data.data;
        this.lastPage = response.data.meta.last_page;
      });
    },
    getVcardsWithFilter() {
      if (
        this.stateFilter == null &&
        this.nameSearch == null &&
        this.phoneNumberSearch == null
      ) {
        this.$toast.error("No filter information provided");
        return;
      }
      this.queryString = "?";
      let map = new Map();
      if (this.stateFilter != null) {
        map.set("state", this.stateFilter);
      }
      if (this.nameSearch != null) {
        map.set("name", this.nameSearch);
      }
      if (this.phoneNumberSearch != null) {
        map.set("phone_number", this.phoneNumberSearch);
      }
      map.forEach((value, key) => {
        this.queryString += key + "=" + value + "&";
      });
      this.queryString = this.queryString.substring(
        0,
        this.queryString.length - 1
      );

      this.$axios
        .get(`/vcards${this.queryString}&page=${this.pageActual}`)
        .then((response) => {
          this.vcards = response.data.data;
          this.lastPage = response.data.meta.last_page;
        })
        .catch((error) => {
          console.log(error.response.data);
          this.queryString = null;
        });
    },
    clearFilters() {
      this.stateFilter = null;
      this.nameSearch = null;
      this.phoneNumberSearch = null;
      this.getVcards();
    },
  },
  mounted() {
    this.getVcards();
  },
  computed: {
    changesListOfVcards() {
      return this.$store.getters.changesListOfVcards;
    },
  },
  watch: {
    changesListOfVcards: {
      handler() {
        if (this.$store.getters.changesListOfVcards) this.getVcards();
      },
      deep: true,
    },
  },
};
</script>

<style>
</style>