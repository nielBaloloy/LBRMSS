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
      <SidebarMenu :menuItems="menuDatacalendar" />
    </q-drawer>

    <q-page-container class="calendar-container">
      <Calendar
        :initialView="'dayGridMonth'"
        :events="eventList"
        :viewEventFunction="viewEvent"
      />
    </q-page-container>
  </q-layout>
</template>
<script setup>
import { useQuasar } from "quasar";
import { api } from "src/boot/axios";
import { ref, onMounted } from "vue";
import { menuData } from "src/data/menuData";
import Calendar from "src/components/DashboardComponents/calendarComponent.vue";
import SidebarMenu from "src/components/DashboardComponents/navigation_left.vue";
const menuDatacalendar = menuData;
const eventList = ref([]);
const $q = useQuasar();
let load = "";
const loadScheduleData = () => {
  return new Promise((resolve, reject) => {
    api
      .get("event_service_data.php", { params: { type: load } })
      .then((response) => {
        if (response.data.Status == "Success") {
          eventList.value = response.data.data;
        } else {
          eventList.value = response.data.data;
        }
      })
      .catch((error) => {
        reject(error);
      });
  });
};

//function to view event information
const viewEvent = (info) => {
  let eventData = info._def.extendedProps.all;
  let priest =
    eventData.pos_prefix +
    " " +
    eventData.fname +
    " " +
    eventData.mname.substr(0, 1) +
    ". " +
    eventData.lname;
  $q.dialog({
    title: `Event Details`,
    message: ` <form style="
      display: flex;
      flex-direction: column;
      gap: 12px;
      padding: 10px;
      font-family: Arial, sans-serif;
    ">
      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Event Name</label>
        <input type="text" value="${eventData.event_name}" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>
      

      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Date</label>
        <input type="text" value="${eventData.date}" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>

      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Priest Assigned</label>
        <input type="text" value="${priest}" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>

      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Time</label>
        <input type="text" value="${eventData.time_from} - ${
      eventData.time_to
    }" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>

      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Venue</label>
        <input type="text" value="${
          eventData.venue_name || "Not specified"
        }" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>

      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Requirement Status</label>
        <input type="text" value="${
          eventData.requirement_status ? "Completed" : "Pending"
        }" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>
    </form>
  `,
    html: true,
  })
    .onOk(() => {
      // console.log('OK')
    })
    .onCancel(() => {
      // console.log('Cancel')
    })
    .onDismiss(() => {
      // console.log('I am triggered on both OK and Cancel')
    });
};

onMounted(async () => {
  loadScheduleData();
});
</script>
<style scoped>
.calendar-container {
  height: 100vh; /* Full viewport height */
  overflow-y: auto; /* Enable vertical scrolling */
  padding: 10px; /* Optional padding */
}
</style>
