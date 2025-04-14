<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">
    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title> Priest Setup </q-toolbar-title>
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
      <div class="canvas">
        <div class="row justify-end">
          <div class="q-pa-md">
            <q-btn
              unelevated
              label="Add Priest"
              color="primary"
              @click="openModal = true"
            />
          </div>
        </div>
        <q-dialog v-model="openModal" style="width: 900px"
          ><div class="card" style="width: 900px">
            <q-card>
              Add Priest
              <q-form @submit.prevent="addPriest" class="q-gutter-md q-pa-lg">
                <div>
                  <div>Last Name</div>
                  <q-input v-model="priest.lname" outlined dense />
                </div>

                <div>
                  <div>First Name</div>
                  <q-input v-model="priest.fname" outlined dense />
                </div>

                <div>
                  <div>Middle Name</div>
                  <q-input v-model="priest.mname" outlined dense />
                </div>

                <div>
                  <div>Suffix Name</div>
                  <q-input v-model="priest.sufix_name" outlined dense />
                </div>
                <div>
                  <div>Contact Number</div>
                  <q-input
                    v-model="priest.contact_number"
                    type="tel"
                    outlined
                    dense
                    mask="####-###-####"
                    hint="Format: 0912-345-6789"
                  />
                </div>

                <div>
                  <div>Status</div>
                  <q-toggle
                    v-model="priest.status"
                    true-value="1"
                    false-value="0"
                    checked-icon="check"
                    unchecked-icon="close"
                    color="primary"
                    dense
                  />
                </div>

                <div>
                  <div>Position</div>
                  <q-select
                    v-model="priest.position"
                    :options="posList"
                    emit-value
                    map-options
                    outlined
                    dense
                  />
                </div>

                <div class="row justify-end">
                  <q-btn label="Submit" type="submit" color="primary" />
                </div>
              </q-form>
            </q-card>
          </div>
        </q-dialog>
        <div class="tablePanel q-pa-lg">
          <SSR_table_component_tools
            :title="title"
            :rowsData="priestList"
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
import { api } from "src/boot/axios";
import SidebarMenu from "../../../components/DashboardComponents/navigation_left.vue";
import SSR_table_component_tools from "src/components/ServicesComponents/SSR_table_component_tools.vue";
import { menuData, menuData2 } from "src/data/menuData";
import {
  loadPriestSetup,
  priestList,
  posList,
  loadPos,
} from "src/composables/priestSetupSetlist";

// ✅ Async function defined before setup()

export default defineComponent({
  components: { SidebarMenu, SSR_table_component_tools },

  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      loadPriestSetup();
      await nextTick();
      loadPos();
      await getSerivce(0); // Ensure fetch completes before proceeding
      dataLoaded.value = true; // ✅ Mark as loaded
    });

    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const router = useRouter();
    let openModal = ref(false);
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
    let title = "Priest Setup";
    let priest = ref({
      priest_id: "",
      lname: "",
      mname: "",
      fname: "",
      status: "1",
      sufix_name: "",
      contact_number: "",
      position: "",
    });
    const addPriest = () => {
      $q.dialog({
        title: "Submit",
        message: "Are you sure you want to logout?",
        cancel: true,
      })
        .onOk(() => {
          api
            .post("priestSetup.php", priest.value)
            .then((response) => {
              if (response.data.message == "") {
                $q.notify({
                  type: "positive",
                  message: "Info submitted successfully!",
                });
                loadPriestSetup();
                openModal.value = false;
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
        .onCancel(() => {});
      console.log(priest.value);
    };
    const positionOptions = [
      { label: "Rev Fr", value: "1" },
      { label: "Assistant Priest", value: "assistant_priest" },
      { label: "Visiting Priest", value: "visiting_priest" },
    ];

    const columns = [
      {
        name: "priest_id",
        align: "center",
        label: "#",
        field: "priest_id",
        sortable: true,
      },
      {
        name: "position",
        label: "Position",
        field: "pos_prefix",
        sortable: true,
      },
      { name: "fullname", label: "Name", field: "fullName" },

      {
        name: "status",
        label: "Status",
        field: "status",
        sortable: true,
      },
      {
        name: "action",
        label: "Action",
        field: "action",
      },
    ];
    return {
      title,
      posList,
      priestList,
      columns,
      positionOptions,
      priest,
      addPriest,
      openModal,
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
