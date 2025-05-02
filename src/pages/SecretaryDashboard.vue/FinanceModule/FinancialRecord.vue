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
      <SidebarMenu :menuItems="menuData2" />
    </q-drawer>

    <q-page-container>
      <div class="q-pa-lg">
        <div class="row q-col-gutter-md">
          <!-- Per Day Card -->
          <div class="col-xs-12 col-sm-4">
            <div class="text-h6">Total Earnings</div>
            <div class="text-h6 q-mt-sm">₱ {{ overall }}</div>
          </div>

          <!-- Per Month Card -->
        </div>
        <div class="q-pt-md">
          <FinanceTable
            :title="Financial"
            :rowsData="frecord"
            :columns="columns"
          />
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
import FinanceTable from "src/components/ServicesComponents/SSR_table_component_v2.vue";
import {
  getFinanceRecord,
  overall,
  frecord,
} from "src/composables/financialRecord.js";
// ✅ Async function defined before setup()

export default defineComponent({
  components: { SidebarMenu, FinanceTable },

  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();
      await getFinanceRecord(); // Ensure fetch completes before proceeding
      dataLoaded.value = true; // ✅ Mark as loaded
    });

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
    const columns = [
      {
        name: "transaction_number",
        label: "OR Number",
        field: "reference_no",
        sortable: true,
        align: "left",
      },
      {
        name: "transaction_date",
        label: "Transaction Date",
        field: "date",
        sortable: true,
        align: "center",
      },
      {
        name: "payees",
        label: "Payees",
        field: "name",
        sortable: true,
        align: "center",
      },
      {
        name: "event_or_service",
        label: "Event or Service",
        field: "event_name",
        sortable: true,
        align: "center",
      },
      {
        name: "type",
        label: "Pay Type",
        field: "payment_type",
        sortable: true,
        align: "center",
      },
      {
        name: "description",
        label: "type",
        field: "fee_type",
        sortable: true,
        align: "center",
      },
      {
        name: "amount",
        label: "Amount",
        field: "amount_total",
        sortable: true,
        align: "center",
      },
      {
        name: "faction",
        label: "Action",
        field: "action",
        sortable: false,
        align: "center",
      },
    ];
    let Financial = "Financial Record";

    return {
      overall,
      frecord,
      Financial,

      columns,
      menuData2,
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
