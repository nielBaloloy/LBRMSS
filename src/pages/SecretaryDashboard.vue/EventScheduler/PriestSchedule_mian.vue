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
      <div class="q-pa-md">
        <div class="q-pa-lg bg-white" style="width: 100%; height: 600px">
          <label class="text-h6">Priest Schedule</label>
          <q-list>
            <q-item
              v-for="priest in schedulePriest"
              :key="priest.priest_id"
              class="q-pa-lg q-my-sm bg-grey-4"
              clickable
              v-ripple
            >
              <q-item-section avatar>
                <q-avatar text-color="white" color="primary">
                  {{ priest.priest_name.charAt(0) }}</q-avatar
                >
              </q-item-section>

              <q-item-section>
                <q-item-label class="text-subtitle2">
                  {{ priest.priest_name }}</q-item-label
                >
                <q-item-label caption lines="1">{{
                  priest.position
                }}</q-item-label>
              </q-item-section>

              <q-item-section side>
                <q-button @click="open(priest.priest_id)">
                  <q-icon size="md" name="event" color="green" />
                </q-button>
              </q-item-section>
            </q-item>
          </q-list>
        </div>
        <q-dialog v-model="openCalendarModal">
          <q-card style="width: 980px; max-width: 80vw">
            <CalendarPriest
              :initialView="'dayGridMonth'"
              :events="priestSchedBucket"
            />
          </q-card>
        </q-dialog>
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
import {
  getScheduledPriest,
  schedulePriest,
} from "src/composables/getPriestSched";
import {
  loadPriestSetup,
  priestList,
  posList,
  loadPos,
} from "src/composables/priestSetupSetlist";
import CalendarPriest from "src/components/ServicesComponents/priestCalendar.vue";

export default defineComponent({
  components: { SidebarMenu, CalendarPriest },

  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();
      await getScheduledPriest(); // Ensure fetch completes before proceeding
      dataLoaded.value = true; // ✅ Mark as loaded
    });
    let openCalendarModal = ref(false);
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
    let priestSchedBucket = ref([]);
    const open = (index) => {
      console.log(schedulePriest.value);
      const filteredSchedules = schedulePriest.value.filter((priest) => {
        return priest.priest_id === index;
      });

      console.log(`Schedules for priest_id ${index}:`);

      // Get the raw schedule data and the priest's name
      const selectedPriest = filteredSchedules[0] || {};
      const rawSchedule = selectedPriest.schedule || [];
      const priestName = selectedPriest.priest_name || ""; // Get the priest name

      // Transform the data into the required format
      priestSchedBucket.value = rawSchedule.map((event) => {
        return {
          all: event,
          title: priestName,
          start: `${event.date_from}T${event.time_from}`,
          end: `${event.date_to}T${event.time_to}`,
          backgroundColor: "#0000",

          seminar: event.seminar || [],
          payment: event.payment || [],
        };
      });

      // Open modal
      setTimeout(() => {
        openCalendarModal.value = true;
      }, 100);
    };

    return {
      openCalendarModal,
      priestSchedBucket,
      open,
      schedulePriest,
      priestList,
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
