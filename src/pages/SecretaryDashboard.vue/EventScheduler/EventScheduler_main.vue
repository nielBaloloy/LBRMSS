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
  let seminarList = info._def.extendedProps.seminar || [];
  let paymentList = info._def.extendedProps.payment || [];

  let eventData = info._def.extendedProps.all;
  let priest =
    eventData.pos_prefix +
    " " +
    eventData.fname +
    " " +
    eventData.mname.substr(0, 1) +
    ". " +
    eventData.lname;

  const seminarRows = seminarList
    .map((seminar, index) => {
      return `
      <tr>
        <td style="padding: 8px; border: 1px solid #ccc;">${index + 1}</td>
         <td style="padding: 8px; border: 1px solid #ccc;">${
           seminar.seminar_title
         }</td>
        <td style="padding: 8px; border: 1px solid #ccc;">${
          seminar.date_from
        }</td>
        <td style="padding: 8px; border: 1px solid #ccc;">${
          seminar.time_from
        } - ${seminar.time_to}</td>
        <td style="padding: 8px; border: 1px solid #ccc;">${
          seminar.seminar_venue
        }</td>
       
      </tr>
    `;
    })
    .join("");

  /** */

  const payyment = paymentList
    .map((payment, index) => {
      return `
      <tr>
        <td style="padding: 8px; border: 1px solid #ccc;">${index + 1}</td>
        <td style="padding: 8px; border: 1px solid #ccc;">${
          payment.reference_no
        }</td>
        <td style="padding: 8px; border: 1px solid #ccc;">
          ${
            payment.status == 2
              ? "Partially Paid"
              : payment.status == 3
              ? "Fully Paid"
              : "Pending"
          }
        </td>
        <td style="padding: 8px; border: 1px solid #ccc;">${
          payment.due_date
        }</td>
      </tr>
    `;
    })
    .join("");

  // Conditional Seminar Section HTML
  const seminarSection =
    eventData.event_name === "Blessing" ||
    eventData.event_name === "Anointing of the Sick" ||
    eventData.event_name === "Misa"
      ? ""
      : `
  <div>
    <h5 style="font-weight: bold; color: #333;">Seminar</h5>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <thead>
          <tr style="background-color: #f0f0f0;">
            <th style="padding: 8px; border: 1px solid #ccc;">#</th>
            <th style="padding: 8px; border: 1px solid #ccc;">Title</th>
            <th style="padding: 8px; border: 1px solid #ccc;">Date</th>
            <th style="padding: 8px; border: 1px solid #ccc;">Time</th>
            <th style="padding: 8px; border: 1px solid #ccc;">Venue</th>
          </tr>
        </thead>
        <tbody>
          ${seminarRows}
        </tbody>
      </table>
    </div>
  </div>
`;
  const paymentInfo =
    eventData.event_name === "Anointing of the Sick" ||
    eventData.event_name === "Misa"
      ? ""
      : `
     <div>
        <h5 style="font-weight: bold; color: #333;">Payment Info</h5>
        <div style="overflow-x: auto;">
          <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead>
              <tr style="background-color: #f0f0f0;">
                <th style="padding: 8px; border: 1px solid #ccc;">#</th>
                <th style="padding: 8px; border: 1px solid #ccc;">Reference</th>
                <th style="padding: 8px; border: 1px solid #ccc;">Status</th>
                <th style="padding: 8px; border: 1px solid #ccc;">Due date</th>
              </tr>
            </thead>
            <tbody>
              ${payyment}
            </tbody>
          </table>
        </div>
      </div>
`;

  $q.dialog({
    title: `Event Details`,
    message: `
    <form style="display: flex; flex-direction: column; padding: 10px; font-family: Arial, sans-serif;">
      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Event Name</label>
        <input type="text" value="${eventData.event_name}" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>

      <div style="display:flex;flex-direction:row;" class="q-gutter-sm">
        <div>
          <label style="font-weight: bold; color: #333;">Date</label>
          <input type="text" value="${eventData.date}" readonly
            style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
        </div>

        <div>
          <label style="font-weight: bold; color: #333;">Time</label>
          <input type="text" value="${eventData.time_from} - ${
      eventData.time_to
    }" readonly
            style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
        </div>
      </div>

      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Venue</label>
        <input type="text" value="${
          eventData.venue_name || "Not specified"
        }" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>

      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Priest Assigned</label>
        <input type="text" value="${priest}" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>

      <div style="display: flex; flex-direction: column;">
        <label style="font-weight: bold; color: #333;">Requirement Status</label>
        <input type="text" value="${
          eventData.requirement_status ? "Completed" : "Pending"
        }" readonly
          style="width: 100%; padding: 8px; border-radius: 8px; border: 1px solid #ccc; background: #f9f9f9;">
      </div>

      ${seminarSection}
    ${paymentInfo}
    
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
.q-dialog__inner > div {
  min-width: 600px;
}
</style>
