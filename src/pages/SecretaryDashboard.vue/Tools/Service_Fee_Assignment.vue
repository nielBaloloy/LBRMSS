<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">
    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title> Tools </q-toolbar-title>
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

    <q-page-container class="q-pa-md">
      <div class="panel q-pa-lg">
        <q-banner inline-actions class="text-black bg-grey-4">
          <p class="text-subtitle1">Service Fee Assignment</p>
          <template v-slot:action>
            <q-btn unelevated color="amber-5" no-caps @click="prompt = true">
              <q-icon left name="assignment_add" />
              <div>Assign Fee</div>
            </q-btn>
            <q-dialog v-model="prompt">
              <ServiceDialog @close-dialog="prompt = false" />
            </q-dialog>
          </template>
        </q-banner>
      </div>
      <div class="panel q-px-lg">
        <q-tabs
          v-model="tab"
          dense
          class="text-grey-9"
          active-color="primary"
          indicator-color="primary"
          inline-label
          align="left"
        >
          <q-tab
            name="marriage"
            icon="wc"
            label="Marriage"
            @click="loadFee(1)"
          ></q-tab>
          <q-tab
            name="bapt"
            icon="water_drop"
            label="Baptism"
            @click="loadFee(2)"
          />
          <q-tab
            name="conf"
            icon="person1"
            label="Confirmation"
            @click="loadFee(3)"
          />
          <q-tab
            name="burial"
            icon="church"
            label="Burial"
            @click="loadFee(4)"
          />
          <q-tab
            name="blessing"
            icon="assignment"
            label="Blessing"
            @click="loadFee(6)"
          />
          <q-tab
            name="annointingoftheSick"
            icon="assignment"
            label="Annointing of the Sick"
            @click="loadFee(5)"
          />
        </q-tabs>
        <q-separator />

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="marriage">
            <SSR_table_tools
              :columns="columns"
              :rowsData="fee_list"
              @filterData="applyDateTimeFilter"
            />
          </q-tab-panel>

          <q-tab-panel name="bapt">
            <SSR_table_tools
              :columns="columns"
              :rowsData="fee_list"
              @filterData="applyDateTimeFilter"
            />
          </q-tab-panel>

          <q-tab-panel name="conf">
            <SSR_table_tools
              :columns="columns"
              :rowsData="fee_list"
              @filterData="applyDateTimeFilter"
            />
          </q-tab-panel>
          <q-tab-panel name="burial">
            <SSR_table_tools
              :columns="columns"
              :rowsData="fee_list"
              @filterData="applyDateTimeFilter"
            />
          </q-tab-panel>
          <q-tab-panel name="blessing">
            <SSR_table_tools
              :columns="columns"
              :rowsData="fee_list"
              @filterData="applyDateTimeFilter"
            />
          </q-tab-panel>
          <q-tab-panel name="annointingoftheSick">
            <SSR_table_tools
              :columns="columns"
              :rowsData="fee_list"
              @filterData="applyDateTimeFilter"
            />
          </q-tab-panel>
        </q-tab-panels>
      </div>
    </q-page-container>
  </q-layout>
</template>
<script setup>
import { useQuasar, SessionStorage } from "quasar";
import { api } from "src/boot/axios";
import { ref, onMounted } from "vue";
import { menuData2 } from "src/data/menuData";
import { useRouter, useRoute } from "vue-router";
import ServiceDialog from "src/pages/SecretaryDashboard.vue/Tools/Service_Fee_Assignment_Add.vue";
import SidebarMenu from "src/components/DashboardComponents/navigation_left.vue";
import SSR_table_tools from "src/components/ServicesComponents/SSR_table_component_tools.vue";
const $q = useQuasar();
const leftDrawerOpen = ref(false);
const router = useRouter();
let prompt = ref(false);
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
let fee_list = ref([]);
let loadFee = (payload) => {
  api
    .get("tools_setup_loadfee.php", { params: { serviceId: payload } })
    .then((response) => {
      if (response.data.Status == "Success") {
        fee_list.value = response.data.fee;
        console.log(response.data.fee);
      } else {
        fee_list.value = [];
      }
    });
};
const tab = ref("marriage");

onMounted(async () => {
  loadFee(1);
});

const columns = [
  {
    name: "name",
    label: "Fee Title",
    field: "name",
    sortable: true,
    align: "center",
  },
  {
    name: "amount",
    label: "Amount",
    field: "amount",
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
</script>
<style scoped></style>
