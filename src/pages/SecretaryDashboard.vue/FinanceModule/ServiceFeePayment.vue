<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">
    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title> Service Fee and Payments </q-toolbar-title>
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
      <SidebarMenu
        :menuItems="menuData"
        v-if="myObject.AccessLvl === 'Secretary'"
      />
      <SidebarMenu
        :menuItems="menuData2"
        v-if="myObject.AccessLvl === 'Cashier'"
      />
    </q-drawer>

    <q-page-container class="bg-accent">
      <div class="request_tab q-pa-xl">
        <q-card flat>
          <q-tabs
            v-model="tab"
            dense
            class="text-grey"
            active-color="primary"
            indicator-color="primary"
            align="justify"
            inline-label
            narrow-indicator
          >
            <q-tab name="service" label="Services Request">
              <div class="counter" v-if="pendingService_Marriage > 0">
                <q-badge color="red" style="margin-left: 30px" floating>{{
                  pendingService_Marriage
                }}</q-badge>
              </div>
            </q-tab>
            <q-tab name="certificate" label="Certificate" @click="loadCert">
              <div class="counter" v-if="pendingService_Marriage > 0">
                <q-badge color="red" style="margin-left: 30px" floating>{{
                  pendingService_Cert
                }}</q-badge>
              </div>
            </q-tab>
          </q-tabs>

          <q-separator />

          <q-tab-panels v-model="tab" animated>
            <q-tab-panel name="service">
              <div class="tab">
                <!-- <ServicesDashboard /> -->
                <SSR_datatable
                  :columns="columns"
                  :rowsData="PaymentList"
                  :getStatusColor="getStatusColor"
                  :getStatusIcon="getStatusIcon"
                  @filterData="applyDateTimeFilter"
                  @openReq="editForm"
                />
              </div>
            </q-tab-panel>

            <q-tab-panel name="certificate">
              <SSR_datatable
                :columns="columnscert"
                :rowsData="certPaymentList"
                :getStatusColor="getStatusColor"
                :getStatusIcon="getStatusIcon"
                @filterData="applyDateTimeFilter"
                @openReq="editForm"
              />
            </q-tab-panel>

            <q-tab-panel name="movies">
              <div class="text-h6">Movies</div>
              Lorem ipsum dolor sit amet consectetur adipisicing elit.
            </q-tab-panel>
          </q-tab-panels>
        </q-card>
        <div class="dialog bg-primary">
          <q-dialog v-model="dialog">
            <q-card class="popUpService" style="width: 950px; max-width: 100vw">
              <q-card-section class="row items-center q-gutter-sm">
                <q-banner
                  inline-actions
                  rounded
                  class="bg-amber-5 text-white"
                  style="width: 950px"
                >
                  <strong>View Request</strong>
                  <template v-slot:action>
                    <p class="text-subtitle2">
                      Reference Number
                      <strong>{{ selectedService.all.reference_no }}</strong>
                    </p>
                  </template>
                </q-banner>
                <OpenRequestModal
                  :requestData="selectedService"
                  @closeDialog="dialog = false"
                  :feeRows="feeRows"
                />
              </q-card-section>
            </q-card>
          </q-dialog>
        </div>
      </div>
    </q-page-container>
  </q-layout>
</template>

<script>
import {
  ref,
  defineComponent,
  nextTick,
  onMounted,
  watch,
  watchEffect,
} from "vue";
import { useQuasar, SessionStorage, QSpinnerFacebook } from "quasar";
import { useRouter, useRoute } from "vue-router";
import SSR_datatable from "src/components/ServicesComponents/SSR_table_component_v3.vue";
import SidebarMenu from "../../../components/DashboardComponents/navigation_left.vue";
import { menuData, menuData2 } from "src/data/menuData";
import {
  pendingCounter_Marriage,
  pendingService_Marriage,
  pendingCounter_Cert,
  pendingService_Cert,
} from "src/composables/counterpendingFee";
import {
  getSerivce,
  Data,
  paymentSetList,
  PaymentList,
  certpaymentSetList,
  certPaymentList,
} from "src/composables/SeviceData.js";
import { getMarriage, MarraigeData } from "src/composables/Marriage.js";
import OpenRequestModal from "src/pages/SecretaryDashboard.vue/FinanceModule/ServiceFee_open_request.vue";
import { api } from "src/boot/axios";
export default defineComponent({
  components: { SidebarMenu, SSR_datatable, OpenRequestModal },
  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();
      pendingCounter_Marriage(1);
      pendingCounter_Cert(1);
      await paymentSetList(1, 1); // Ensure fetch completes before proceeding
      dataLoaded.value = true; // ✅ Mark as loaded
    });

    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const router = useRouter();
    const tab = ref("service");
    const selectedService = ref(null);
    const dialog = ref(false);

    let feeRows = ref([]); // ✅ Makes feeRows reactive

    function editForm(data) {
      let timer;
      console.log("data", data);
      selectedService.value = data;

      var id = data["all"]["service_id"];
      var feeType = data["all"]["fee_type"];

      var type = data["all"]["type"];
      var category = data["burial_option"]["burial_option"]
        ? data["burial_option"]["burial_option"]
        : "";

      if (id == 4 && category == 2) {
        api
          .get("request_fee.php", { params: { serviceId: id, type: 3 } })
          .then((response) => {
            if (response.data.Status === "Success") {
              console.log("feelist", feeType);
              if (feeType == 1) {
                feeRows.value = response.data.fee;
                console.log("feedata", response.data.fee);
              }
              feeRows.value = response.data.fee;
              console.log("feedata", response.data.fee);
              // ✅ Use `.value`
              console.log("Updated feeRows:", feeRows.value); // ✅ Log after assignment
            } else {
              console.log("No fee data found");
              feeRows.value = [];
            }
          })
          .catch((error) => {
            console.error("Error fetching fee list:", error);
          });
      } else if (id == 4 && category == 1) {
        api
          .get("request_fee.php", { params: { serviceId: id, type: 4 } })
          .then((response) => {
            if (response.data.Status === "Success") {
              console.log("feelist", feeType);
              if (feeType == 1) {
                feeRows.value = response.data.fee;
                console.log("feedata", response.data.fee);
              } else {
                feeRows.value = response.data.fee;
                console.log("feedata", response.data.fee);
              }
              console.log("Updated feeRows:", feeRows.value); // ✅ Log after assignment
            } else {
              console.log("No fee data found");
              feeRows.value = [];
            }
          })
          .catch((error) => {
            console.error("Error fetching fee list:", error);
          });
      } else {
        api
          .get("request_fee.php", { params: { serviceId: id, type: type } })
          .then((response) => {
            if (response.data.Status === "Success") {
              console.log("feelist", feeType);
              console.log("feedata", response.data.fee);
              if (feeType == 1) {
                feeRows.value = response.data.fee.filter((fee) =>
                  fee.name.toLowerCase().includes("certificate")
                );
                console.log("feedROWata", feeRows.value);
              } else {
                feeRows.value = response.data.fee;
                console.log("feedata", response.data.fee);
              }
              console.log("Updated feeRows:", feeRows.value); // ✅ Log after assignment
            } else {
              console.log("No fee data found");
              feeRows.value = [];
            }
          })
          .catch((error) => {
            console.error("Error fetching fee list:", error);
          });
      }

      $q.loading.show({
        spinner: QSpinnerFacebook,
        spinnerColor: "yellow",
        spinnerSize: 100,
        backgroundColor: "white",
        message: "Hold tight! Summoning the Holy Calculator... 📖✝️",
        messageColor: "black",
      });
      timer = setTimeout(() => {
        $q.loading.hide();
        timer = void 0;
        dialog.value = true;
      }, 2000);
    }
    let myObject = ref();
    let sessionkey = SessionStorage.getItem("log");
    if (sessionkey === null || "") {
      router.push({ path: "/" });
    } else {
      myObject.value = JSON.parse(sessionkey);
      console.log(myObject.value);
    }

    let active = ref(false);
    let activeclick = () => {
      active.value = true;
    };
    //logout
    const Logout = () => {
      $q.dialog({
        title: "Logout",
        message: "Are you sure you want to logout?",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          SessionStorage.clear();
          router.push({ path: "/" });
        })
        .onOk(() => {
          // console.log('>>>> second OK catcher')
        })
        .onCancel(() => {
          // console.log('>>>> Cancel')
        })
        .onDismiss(() => {
          // console.log('I am triggered on both OK and Cancel')
        });
    };

    // ===================== Table columns ============================
    const columns = [
      {
        name: "name",
        label: "Client",
        field: "Client",
        sortable: true,
        align: "center",
      },
      {
        name: "Service",
        label: "Service",
        field: "Service",
        sortable: true,
        align: "center",
      },
      {
        name: "Type",
        label: "Type",
        field: "Type",
        sortable: true,
        align: "center",
      },
      {
        name: "Date",
        label: "Date",
        field: "Date",
        sortable: true,
        align: "center",
      },
      {
        name: "Venue",
        label: "Venue",
        field: "Venue",
        sortable: true,
        align: "center",
      },
      {
        name: "Assigned Priest",
        label: "Assigned Priest",
        field: "Assigned_Priest",
        sortable: true,
        align: "center",
      },
      {
        name: "Status",
        label: "Payment Status",
        field: "EventProgress",
        sortable: true,
        align: "center",
        icon: "pause",
      },
      {
        name: "Action",
        label: "Action",
        field: "Action",
        sortable: false,
        align: "center",
      },
    ];
    const confirmAction = () => {
      console.log("User confirmed!");
    };

    const handleDialogAction = (actionLabel) => {
      console.log(`User clicked: ${actionLabel}`);
    };
    const getStatusColor = (status) => {
      switch (status) {
        case "Pending":
          return "orange"; // 🟠 Pending
        case "Scheduled":
          return "blue"; // 🔵 Scheduled
        case "Done":
          return "green"; // 🟢 Done
        default:
          return "grey"; // ❔ Default
      }
    };

    const getStatusIcon = (status) => {
      switch (status) {
        case "Pending":
          return "hourglass_empty"; // ⏳
        case "Scheduled":
          return "event"; // 📅
        case "Done":
          return "check_circle"; // ✅
        default:
          return "help"; // ❓
      }
    };
    const save = (props) => {
      console.log(props);
    };
    const applyDateTimeFilter = (filters) => {
      console.log("Filtering with:", filters);

      watch(
        () => tab,
        (newVal) => {
          console.log("tab", newVal.value);
          if (newVal.value == "certificate") {
            paymentSetList(filters, 2);
          } else {
            paymentSetList(filters, 1);
          }
        },
        { immediate: true }
      );
    };

    /** Load Counter */
    function loadCert() {
      certpaymentSetList(1);
    }
    const columnscert = [
      {
        name: "name",
        label: "Reference Number",
        field: "Reference",
        sortable: true,
        align: "center",
      },
      {
        name: "name",
        label: "Client",
        field: "Client",
        sortable: true,
        align: "center",
      },
      {
        name: "Service",
        label: "Service",
        field: "Service",
        sortable: true,
        align: "center",
      },

      {
        name: "Date",
        label: "Date",
        field: "Date",
        sortable: true,
        align: "center",
      },

      {
        name: "Status",
        label: "Payment Status",
        field: "EventProgress",
        sortable: true,
        align: "center",
        icon: "pause",
      },
      {
        name: "Action",
        label: "Action",
        field: "Action",
        sortable: false,
        align: "center",
      },
    ];
    return {
      columnscert,
      loadCert,
      certpaymentSetList,
      certPaymentList,
      pendingService_Marriage,
      pendingService_Cert,
      getStatusIcon,
      applyDateTimeFilter,
      getStatusColor,
      columns,
      editForm,
      feeRows,
      tab,
      PaymentList,
      Data,
      selectedService,
      MarraigeData,
      menuData2,
      menuData,
      dialog,
      Logout,
      myObject,
      activeclick,
      menu: ref(false),
      active,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
  },
});
</script>
<style scoped>
@font-face {
  font-family: opensans;
  src: url(../../../css/MontserratOpen_Sans/Open_Sans/static/OpenSans-Medium.woff);
}

.titlebar {
  font-family: opensans;
  text-decoration: none;
  color: rgb(52, 52, 52);
}
</style>
