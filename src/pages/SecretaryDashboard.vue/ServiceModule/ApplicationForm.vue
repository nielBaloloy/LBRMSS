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
      <div class="AppPanel q-pa-lg">
        <q-banner class="banner-plate bg-secondary text-dark">
          <div class="titlebar flex q-pa-sm justify-between">
            <p class="text-h5">Services</p>
            <q-btn
              label="Add Service"
              color="primary"
              @click="dialog2 = true"
            ></q-btn>
          </div>
          <div class="dialog bg-primary">
            <q-dialog v-model="dialog2">
              <q-card class="popUpService">
                <q-card-section>
                  <div class="text-h6 text-center q-pt-md">Add New Service</div>
                </q-card-section>
                <q-card-section class="row items-center q-gutter-sm">
                  <StepperForm />
                </q-card-section>
              </q-card>
            </q-dialog>
          </div>
        </q-banner>
        <div class="tab q-pa-md">
          <!-- <ServicesDashboard /> -->
          <SSR_datatable
            title="PENDING"
            :columns="columns"
            :rowsData="Data"
            :getStatusColor="getStatusColor"
            :getStatusIcon="getStatusIcon"
            @filterData="applyDateTimeFilter"
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
import SSR_datatable from "src/components/ServicesComponents/SSR_table_component.vue";
import StepperForm from "src/components/ServicesComponents/StepperForm.vue";
import { menuData } from "src/data/menuData";
import { getSerivce, Data } from "src/composables/SeviceData.js";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from "@fullcalendar/daygrid"; // Month view
import timeGridPlugin from "@fullcalendar/timegrid"; // Week & Day views
import interactionPlugin from "@fullcalendar/interaction"; // Drag & Drop
import listPlugin from "@fullcalendar/list"; // List view
// ✅ Async function defined before setup()

export default defineComponent({
  components: { StepperForm, SidebarMenu, SSR_datatable },

  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();
      await getSerivce(0); // Ensure fetch completes before proceeding
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

    // Table columns
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
        label: "Status",
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
      getSerivce(filters.status);
    };

    const calendarOptions = ref({
      plugins: [dayGridPlugin, timeGridPlugin, interactionPlugin, listPlugin],
      initialView: "dayGridMonth", // Default view
      headerToolbar: {
        left: "prev,next today",
        center: "title",
        right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth", // Views
      },
      selectable: true,
      editable: true,
      events: [
        { title: "Event 1", start: "2025-02-25" },
        { title: "Event 2", start: "2025-02-28" },
        { title: "Meeting", start: "2025-03-02T10:00:00" },
        { title: "Workshop", start: "2025-03-05T14:00:00" },
      ],
    });
    return {
      calendarOptions,
      applyDateTimeFilter,
      save,
      Data,
      columns,
      getStatusIcon,
      getStatusColor,
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
