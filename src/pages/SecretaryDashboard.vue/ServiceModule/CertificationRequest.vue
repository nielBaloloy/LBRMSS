<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">
    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title> LBRMSS-Dashboard </q-toolbar-title>
        <div class="account">
          <q-avatar>
            <img src="../../../assets/quasar-logo-vertical.svg" />
          </q-avatar>
          <q-btn-dropdown flat class="dropdown-btn">
            <div class="row no-wrap flex">
              <div class="column">
                <div class="text-h6 q-mb-md bg-amber-5 flex flex-center">
                  <!-- Account content Here -->

                  <q-avatar
                    class="flex q-pt-lg"
                    style="width: 1.6em; height: 1.6em"
                  >
                    <img src="../../../assets/quasar-logo-vertical.svg" />
                  </q-avatar>
                </div>
                <div class="profile-info q-pa-sm">
                  <p
                    class="User-Account text-center q-mt-sm"
                    style="font-size: small; font-weight: 600"
                  >
                    <!-- username -->

                    {{ myObject.Username }}
                    <br />
                    <span class="text-amber-6">
                      <!-- Position -->
                      {{ myObject.AccessLvl }}</span
                    >
                  </p>
                  <q-list>
                    <q-item clickable v-ripple class="it-1">
                      <div class="item q-pa-xs">
                        <q-icon name="settings"></q-icon>
                      </div>
                      <div class="item q-pa-xs" style="width: 200px">
                        Account Settings
                      </div>
                    </q-item>
                    <q-item
                      clickable
                      v-ripple
                      class="it-1"
                      to="/SecretaryDashboard/AccountLog"
                    >
                      <div class="item q-pa-xs">
                        <q-icon name="settings"></q-icon>
                      </div>
                      <div class="item q-pa-xs" style="width: 200px">
                        Account Logs
                      </div>
                    </q-item>
                    <q-item clickable v-ripple class="it-1">
                      <div class="item q-pa-xs">
                        <q-icon name="settings"></q-icon>
                      </div>
                      <div class="item q-pa-xs" style="width: 200px">
                        Switch Account
                      </div>
                    </q-item>
                    <!-- dialog for switch Account -->

                    <q-item
                      clickable
                      v-ripple
                      class="text-red"
                      @click="Logout()"
                    >
                      <div class="item q-pa-xs">
                        <q-icon name="logout"></q-icon>
                      </div>
                      <div class="item q-pa-xs" style="width: 200px">
                        Logout
                      </div>
                    </q-item>
                  </q-list>
                </div>
              </div>
            </div>
          </q-btn-dropdown>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer
      class="bg-white text-dark q-pa-md"
      show-if-above
      v-model="leftDrawerOpen"
      side="left"
      behavior="desktop"
      bordered
    >
      <div class="LOGO q-mb-xl"></div>
      <SidebarMenu :menuItems="menuData" />
    </q-drawer>

    <q-page-container>
      <div class="q-pa-lg">
        <div class="text-h6 q-mb-md">Request Certificate</div>

        <!-- Purpose Dropdown -->
        <q-select
          outlined
          v-model="selectedPurpose"
          :options="purposes"
          label="Select Purpose"
          class="q-mb-md"
        />

        <!-- Search Name Input -->
        <q-select
          outlined
          ref="step1Ref"
          v-model="searchName"
          use-input
          hide-selected
          fill-input
          input-debounce="0"
          :options="filterOptions"
          option-label="fullname"
          option-value="id"
          @new-value="createValue"
          @filter="filterFn"
          style="width: 100%"
          :rules="[(val) => !!val || 'Field is required']"
        >
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey"> No results </q-item-section>
            </q-item>
          </template>
        </q-select>

        <q-select
          outlined
          v-model="Service"
          :options="serviceOption"
          label="Select Service"
          class="q-mb-md"
          @update:model-value="setSearch(Service)"
        />

        <!-- Print Button -->
        <q-btn
          label="Print"
          color="primary"
          @click="printDocument(selectedPurpose, searchName)"
          class="q-mb-lg"
        />

        <!-- Embedded PDF -->
        <div class="printcanvas" style="border: 2px dotted black">
          <iframe
            :src="pdfUrl"
            width="100%"
            height="600px"
            style="border: none"
          ></iframe>
        </div>
      </div>
    </q-page-container>
  </q-layout>
</template>
<style scoped>
@font-face {
  font-family: opensans;
  src: url("../../../css/Montserrat,Open_Sans/Open_Sans/static/OpenSans-Medium.woff");
}

.titlebar {
  font-family: opensans;
  text-decoration: none;
  color: rgb(52, 52, 52);
}
.my-card {
  width: 300px;
}
.popUpService {
  max-width: 100%;
  height: 80em;
}
</style>
<script>
import { ref, defineComponent, nextTick, onMounted, watchEffect } from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter } from "vue-router";
import SidebarMenu from "../../../components/DashboardComponents/navigation_left.vue";
import { menuData, menuData2 } from "src/data/menuData";
import { loadSearch, names } from "src/composables/searcName";
import { baseUrl } from "src/data/menuData.js";
import { api } from "src/boot/axios";
// ✅ Async function defined before setup()

export default defineComponent({
  components: { SidebarMenu },

  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();
      loadSearch();
      await getSerivce(0); // Ensure fetch completes before proceeding

      dataLoaded.value = true; // ✅ Mark as loaded
    });
    const pdfUrl = ref("");
    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const router = useRouter();

    let myObject = ref();
    let sessionkey = SessionStorage.getItem("log");
    if (!sessionkey) {
      router.push({ path: "/" });
    } else {
      myObject.value = JSON.parse(sessionkey);
      console.log(myObject.value);
    }
    const selectedPurpose = ref("");
    const searchName = ref("");
    const Service = ref("");

    const purposes = [
      { label: "For Good Moral", value: "good_moral" },
      { label: "For Change of Records", value: "change_records" },
      { label: "Affidavit of Change Records", value: "affidavit_change" },
    ];
    function setSearch(service) {
      let id = service.value;
      loadSearch(id);
    }
    const serviceOption = [
      { value: "1", label: "Marriage" },
      { value: "2", label: "Baptism" },
      { value: "3", label: "Confirmation" },
    ];

    // Logout function
    const Logout = () => {
      $q.dialog({
        title: "Logout",
        message: "Are you sure you want to logout?",
        cancel: true,
        persistent: true,
      }).onOk(() => {
        SessionStorage.clear();
        router.push({ path: "/" });
      });
    };

    const filterOptions = ref([]);
    filterOptions.value = [...names.value];
    const createValue = (val, done) => {
      if (val.length > 0) {
        const exists = names.value.some(
          (item) => item.fullname.toLowerCase() === val.toLowerCase()
        );
        if (!exists) {
          const newId = (names.value.length + 1).toString();
          const newItem = {
            id: newId,
            event_id: newId, // ✅ Map event_id to newId
            fullname: val,
          };
          names.value.push(newItem);
        }
        done(val, "toggle");
      }
    };

    const filterFn = (val, update, abort) => {
      update(() => {
        if (val === "") {
          filterOptions.value = [...names.value];
        } else {
          const needle = val.toLowerCase();
          filterOptions.value = names.value.filter((v) =>
            v.fullname.toLowerCase().includes(needle)
          );
        }
      });
    };
    const printDocument = () => {
      const purpose = selectedPurpose.value?.label || selectedPurpose.value; // assuming it's a Quasar option object
      const Serviceswelect = Service.value?.label || Service.value;
      const name = searchName.value?.fullname || searchName.value;
      const event_id = searchName.value?.event_id;
      console.log("Sending:", { purpose, Serviceswelect, name, event_id });
      $q.dialog({
        title: "Print Certificate",
        message: "Please proceed to the Cashier to settle the payment first",
        cancel: true,
        persistent: true,
      }).onOk(() => {
        api
          .post("certificationpayment.php", { event_id, purpose })
          .then((response) => {
            console.log(response);
            if (response.data.Status == "Success") {
              $q.notify({
                type: "positive",
                message: "Payment Request Sent!",
              });
            }
          })
          .catch((error) => {
            console.log(error);
          });
        pdfUrl.value =
          baseUrl +
          "certificateprint.php?purpose=" +
          encodeURIComponent(purpose) +
          "&name=" +
          encodeURIComponent(name) +
          "&service=" +
          encodeURIComponent(Serviceswelect);
      });
    };

    return {
      Service,
      filterOptions,
      serviceOption,
      filterFn,
      createValue,
      searchName,
      printDocument,
      selectedPurpose,
      setSearch,
      pdfUrl,
      purposes,
      menuData,
      dialog2: ref(false),
      Logout,
      myObject,
      active: ref(false),
      leftDrawerOpen,
      tab: ref("events"),
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
  },
});
</script>
