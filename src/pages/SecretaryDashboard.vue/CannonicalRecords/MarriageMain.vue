<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">
    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title> LBRMSS </q-toolbar-title>
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
      <div class="container">
        <SSR_datatable_v2
          title="PENDING"
          :columns="columns"
          :rowsData="MarraigeData"
          @edit="editForm"
          @filterData="applyDateTimeFilter"
        />
      </div>

      <div class="dialog bg-primary">
        <q-dialog v-model="dialog">
          <q-card class="popUpService" style="width: 950px; max-width: 100vw">
            <q-card-section class="row items-center q-gutter-sm">
              <h6>Edit Marriage</h6>
            </q-card-section>
            <q-card-section class="row items-center q-gutter-sm">
              <EditDialog :eventData="selectedService" />
            </q-card-section>
          </q-card>
        </q-dialog>
      </div>
    </q-page-container>
  </q-layout>
</template>

<script>
import { ref, defineComponent, nextTick, onMounted, watchEffect } from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter, useRoute } from "vue-router";
import SSR_datatable_v2 from "../../../components/ServicesComponents/SSR_table_component_v2.vue";
import SidebarMenu from "../../../components/DashboardComponents/navigation_left.vue";
import { menuData, menuData2 } from "src/data/menuData";
import EditDialog from "src/pages/SecretaryDashboard.vue/CannonicalRecords/MarriageEdit.vue"; // Import the component
import { getMarriage, MarraigeData } from "src/composables/Marriage.js";
export default defineComponent({
  components: { SidebarMenu, SSR_datatable_v2, EditDialog },
  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();
      await getMarriage();

      dataLoaded.value = true; // ✅ Mark as loaded
    });
    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const router = useRouter();

    const selectedService = ref(null);
    const dialog = ref(false);

    function editForm(data) {
      console.log(data);
      selectedService.value = data;
      dialog.value = true;
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
        label: "Groom and Bride",
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
    return {
      columns,
      editForm,

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
