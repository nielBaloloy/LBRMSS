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
      <div class="row justify-end">
        <div class="q-pa-md">
          <q-btn label="Add" color="primary" @click="openModal = true" />
        </div>
      </div>
      <div class="q-pa-md">
        <q-table
          flat
          title="Mass Schedules"
          :rows="massrows"
          :columns="masscolumns"
          row-key="name"
        >
          <template v-slot:body-cell-actions="props">
            <q-td align="center">
              <q-btn
                color="primary"
                flat
                dense
                icon="edit"
                @click="editMass(props.row)"
              />
              <q-btn
                color="primary"
                flat
                dense
                icon="visibility"
                @click="ViewMasses(props.row)"
              />
              <q-btn
                color="negative"
                flat
                dense
                icon="delete"
                @click="removeMassEntry(props.row)"
              />
            </q-td>
          </template>
          <template v-slot:body-cell-priest="props">
            <q-td :props="props">
              <span v-if="props.row.priest_id">
                {{ props.row.lname }}, {{ props.row.fname }}
              </span>
              <span v-else> Not yet assigned </span>
            </q-td>
          </template></q-table
        >
      </div>
      <q-dialog v-model="openModal" style="width: 900px">
        <div style="width: 1000px; max-width: 70vw">
          <q-card class="q-pa-md" style="width: auto">
            <div class="text-h6">Mass Schedule Setup</div>
            <div class="q-gutter-md">
              <!-- Date (Once Only) -->
              <div class="mass">
                Mass Schedule Date
                <q-input
                  v-model="scheduleDate"
                  mask="####-##-##"
                  outlined
                  dense
                >
                  <template #append>
                    <q-icon name="event" class="cursor-pointer">
                      <q-popup-proxy>
                        <q-date v-model="scheduleDate" mask="YYYY-MM-DD" />
                      </q-popup-proxy>
                    </q-icon>
                  </template>
                </q-input>
              </div>

              <q-form @submit.prevent="addMassEntry" class="q-gutter-md">
                <!-- Mass Title -->
                <div class="mass">
                  Mass Title
                  <q-input v-model="tempMass.mass_title" outlined dense />
                </div>

                <!-- Language -->
                <div class="mass">
                  Language
                  <q-select
                    v-model="tempMass.language"
                    :options="['Bicol', 'English']"
                    outlined
                    dense
                  />
                </div>

                <div class="mass">
                  Time From
                  <q-input
                    outlined
                    dense
                    v-model="tempMass.time_from"
                    mask="time"
                  >
                    <template v-slot:append>
                      <q-icon name="access_time" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-time v-model="tempMass.time_from">
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Close"
                                color="primary"
                                flat
                              />
                            </div>
                          </q-time>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>

                <div class="mass">
                  Time to
                  <q-input
                    outlined
                    dense
                    v-model="tempMass.time_to"
                    mask="time"
                  >
                    <template v-slot:append>
                      <q-icon name="access_time" class="cursor-pointer">
                        <q-popup-proxy
                          cover
                          transition-show="scale"
                          transition-hide="scale"
                        >
                          <q-time v-model="tempMass.time_to">
                            <div class="row items-center justify-end">
                              <q-btn
                                v-close-popup
                                label="Close"
                                color="primary"
                                flat
                              />
                            </div>
                          </q-time>
                        </q-popup-proxy>
                      </q-icon>
                    </template>
                  </q-input>
                </div>
                <div class="venue">
                  <p>Venue Type</p>
                  <q-btn-toggle
                    unelevated
                    v-model="tempMass.venue_type"
                    toggle-color="primary"
                    :options="[
                      { label: 'Pastoral Center', value: '2' },
                      { label: 'Church', value: '1' },
                      { label: 'Outside', value: '3' },
                    ]"
                    @update:model-value="
                      setAddress(tempMass.venue_type, tempMass)
                    "
                  />
                </div>

                <div class="Venue" v-show="Address_A">
                  Venue Address
                  <q-input
                    outlined
                    v-model="tempMass.venue"
                    :dense="true"
                    class="input-field"
                  />
                </div>
                <div class="servicefield">
                  Preffered Priest
                  <q-select
                    :dense="true"
                    outlined
                    clearable
                    ref="step1Ref"
                    v-model="tempMass.assigned_priest"
                    :options="
                      availablePriest && availablePriest.length
                        ? availablePriest
                        : [
                            {
                              priest_id: null,
                              priest_name: 'No available priest',
                            },
                          ]
                    "
                    option-value="priest_id"
                    option-label="priest_name"
                  />
                </div>

                <div class="mass">
                  <q-btn
                    label="Add to Schedule"
                    type="submit"
                    color="primary"
                  />
                </div>
              </q-form>

              <!-- Mass Schedule Table -->
              <div>
                <q-table
                  title="Mass Schedule"
                  :rows="massList"
                  :columns="columns"
                  row-key="mass_title"
                  flat
                  bordered
                >
                  <template v-slot:body-cell-actions="props">
                    <q-td align="center">
                      <q-btn
                        color="negative"
                        flat
                        dense
                        icon="delete"
                        @click="removeMassEntry(props.row)"
                      />
                    </q-td>
                  </template>
                </q-table>
              </div>

              <!-- Submit All Button -->
              <div class="mass" v-if="massList.length">
                <q-btn
                  label="Submit All Schedule"
                  color="positive"
                  @click="submitAllSchedule"
                />
              </div>
            </div>
          </q-card>
        </div>
      </q-dialog>
      <q-dialog v-model="massViewDialog" persistent>
        <q-card style="min-width: 600px; max-width: 90vw">
          <q-card-section class="text-h6 text-primary">
            Mass Information
          </q-card-section>

          <q-separator />

          <q-card-section class="q-pt-none">
            <div class="q-gutter-sm q-mb-md">
              <q-item>
                <q-item-section>
                  <q-item-label
                    ><strong>Mass Title:</strong>
                    {{ massData.mass_title }}</q-item-label
                  >
                  <q-item-label
                    ><strong>Date:</strong> {{ massData.date }}</q-item-label
                  >
                  <q-item-label
                    ><strong>Time:</strong> {{ massData.time_from }} -
                    {{ massData.time_to }}</q-item-label
                  >
                  <q-item-label
                    ><strong>Venue:</strong> {{ massData.venue }}</q-item-label
                  >
                  <q-item-label
                    ><strong>Priest Name:</strong> {{ massData.fname }}
                    {{ massData.lname }}</q-item-label
                  >
                </q-item-section>
              </q-item>
            </div>

            <div class="text-h6 text-primary q-mb-sm">Details</div>

            <q-table
              dense
              bordered
              :rows="massData.details"
              :columns="massescolumns"
              row-key="id"
              flat
              :pagination="{ rowsPerPage: 5 }"
            >
              <template v-slot:body-cell-intention_type="props">
                <q-td :props="props">
                  <q-badge color="green" outline>{{ props.value }}</q-badge>
                </q-td>
              </template>

              <template v-slot:body-cell-intention_desc="props">
                <q-td :props="props">
                  <q-tooltip>{{ props.value }}</q-tooltip>
                  {{
                    props.value.length > 20
                      ? props.value.slice(0, 20) + "..."
                      : props.value
                  }}
                </q-td>
              </template>
            </q-table>
          </q-card-section>

          <q-separator />

          <q-card-actions align="right">
            <q-btn flat label="Close" color="primary" v-close-popup />
          </q-card-actions>
        </q-card>
      </q-dialog>
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
import {
  ref,
  defineComponent,
  nextTick,
  onMounted,
  watchEffect,
  watch,
  computed,
} from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter } from "vue-router";
import SidebarMenu from "../../../components/DashboardComponents/navigation_left.vue";
import { menuData } from "src/data/menuData";
import { api } from "src/boot/axios";
import { getAvailablePriest, availablePriest } from "src/composables/getPriest";
import { loadScheduleTable, massrows } from "src/composables/loadMassSchedule";
// ✅ Async function defined before setup()

export default defineComponent({
  components: { SidebarMenu },

  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();
      loadScheduleTable();
      getAvailablePriest(); // Ensure fetch completes before proceeding
      dataLoaded.value = true; // ✅ Mark as loaded
    });
    const scheduleDate = ref(null);
    const massList = ref([]);
    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const router = useRouter();
    let massViewDialog = ref(false);
    let openModal = ref(false);

    let myObject = ref();
    let sessionkey = SessionStorage.getItem("log");
    if (!sessionkey) {
      router.push({ path: "/" });
    } else {
      myObject.value = JSON.parse(sessionkey);
      console.log(myObject.value);
    }

    const mmassData = ref({
      mass_id: null,
      mass_title: null,
      date: null,
      time_from: null,
      time_to: null,
      language: null,
      Venue_type: null, // added
      Venue: null,
      Assigned_Priest: null, // added
      created_at: null,
      created_by: null,
      remark: "1",
    });
    const submitForm = () => {
      console.log("Submitting Mass Data:", mmassData.value);
      $q.dialog({
        title: "Confirm",
        message: "Would you like to turn on the wifi?",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          api
            .post("mass_schedule.php", mmassData.value)
            .then((response) => {
              console.log(response);
              if (response.data.Status == "Success") {
                $q.notify({
                  type: "positive",
                  message: "Saved Successfully",
                });
                openModal.value = false;
                loadScheduleTable();
              }
              window.location.reload();
            })
            .catch((error) => {
              reject(error);
              $q.notify({
                type: "negative",
                message: "Network Error",
              });
            });
        })

        .onCancel(() => {
          // console.log('>>>> Cancel')
        });
    };

    const tempMass = ref({
      mass_title: "",
      language: "",
      venue: "",
      venue_type: "",
      assigned_priest: "",
      time_from: "",
      time_to: "",
      remark: "1",
    });

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
    const languageOptions = [
      { label: "Bicol", value: "Bicol" },
      { label: "English", value: "English" },
    ];

    const eventDetails = computed(() => ({
      date: tempMass.value.date,
      timeFrom: tempMass.value.time_from,
      timeTo: tempMass.value.time_to,
      venuetype: tempMass.value.venue_type,
    }));
    watch(
      () => [
        tempMass.value.date,
        tempMass.value.time_from,
        tempMass.value.time_to,
        tempMass.value.venue_type,
      ],
      ([newDate, newTimeFrom, newTimeTo, newVenue]) => {
        if (newDate && newTimeFrom && newTimeTo && newVenue) {
          tempMass.value.assigned_priest = null;
          getAvailablePriest(eventDetails.value);
        }
      }
    );
    let Address_A = ref(false);
    let setAddress = (venueType, tempMass) => {
      console.log(venueType);
      if (venueType == 1) {
        tempMass.venue = "St Raphael Church";
        Address_A.value = false;
      } else if (venueType == 2) {
        tempMass.venue = "Pastoral Center";
        Address_A.value = false;
      } else if (venueType == 3) {
        tempMass.venue = null;
        Address_A.value = true;
      }
    };

    // Add entry to massList using the global date
    const addMassEntry = () => {
      if (!scheduleDate.value) {
        alert("Please select a date first.");
        return;
      }

      if (
        tempMass.value.mass_title &&
        tempMass.value.time_from &&
        tempMass.value.time_to
      ) {
        massList.value.push({
          ...tempMass.value,
          date: scheduleDate.value,
        });

        // Reset entry
        tempMass.value = {
          mass_title: "",
          language: "",
          venue: "",
          venue_type: "",
          assigned_priest: "",
          time_from: "",
          time_to: "",
          remark: "1",
        };
      } else {
        alert("Please complete all required fields.");
      }
    };

    // Table columns
    const columns = [
      { name: "mass_title", label: "Mass Title", field: "mass_title" },
      { name: "language", label: "Language", field: "language" },
      { name: "venue", label: "Venue", field: "venue" },
      { name: "date", label: "Date", field: "date" },
      { name: "time_from", label: "From", field: "time_from" },
      { name: "time_to", label: "To", field: "time_to" },
      {
        name: "actions",
        label: "Actions",
        field: "actions",
        align: "center",
        sortable: false,
      },
    ];

    //main table
    const masscolumns = [
      { name: "mass_title", label: "Mass Title", field: "mass_title" },
      { name: "language", label: "Language", field: "language" },
      { name: "venue", label: "Venue", field: "venue" },
      { name: "date", label: "Date", field: "date" },
      { name: "time_from", label: "From", field: "time_from" },
      { name: "time_to", label: "To", field: "time_to" },
      {
        name: "priest",
        label: "Priest",
        field: "priest",
        align: "center",
        sortable: false,
      },
      {
        name: "actions",
        label: "Actions",
        field: "actions",
        align: "center",
        sortable: false,
      },
    ];

    const submitAllSchedule = () => {
      console.log("Submitting entire mass schedule:", massList.value);
      $q.dialog({
        title: "Confirm",
        message: "Would you like to turn on the wifi?",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          api
            .post("mass_schedule.php", massList.value)
            .then((response) => {
              console.log(response);
              if (response.data.Status == "Success") {
                $q.notify({
                  type: "success",
                  message: "Saved Successfully",
                });

                openModal.value = false;
                loadScheduleTable();
              }
            })
            .catch((error) => {
              reject(error);
              $q.notify({
                type: "negative",
                message: "Network Error",
              });
            });
        })

        .onCancel(() => {
          // console.log('>>>> Cancel')
        });
    };
    const removeMassEntry = (row) => {
      massList.value = massList.value.filter((mass) => mass !== row);
    };
    let massData = ref(null);

    function ViewMasses(mass) {
      console.log(mass);
      massData.value = { ...mass };
      massViewDialog.value = true;
    }
    const massescolumns = [
      {
        name: "intention_type",
        label: "Type",
        field: "intention_type",
        align: "left",
      },
      {
        name: "intention_desc",
        label: "Description",
        field: "intention_desc",
        align: "left",
      },
    ];
    return {
      massData,
      massViewDialog,
      massescolumns,
      ViewMasses,
      massrows,
      masscolumns,
      removeMassEntry,
      submitAllSchedule,
      addMassEntry,
      scheduleDate,
      columns,
      massList,
      tempMass,
      Address_A,
      setAddress,
      getAvailablePriest,
      availablePriest,
      languageOptions,
      openModal,
      submitForm,
      mmassData,
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
