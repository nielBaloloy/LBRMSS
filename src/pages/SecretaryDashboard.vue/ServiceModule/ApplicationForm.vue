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
          <ServicesDashboard />
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
import { ref, defineComponent, onMounted } from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter, useRoute } from "vue-router";
import ServicesDashboard from "../../../components/ServicesComponents/ServicesDashboard.vue";
import SidebarMenu from "../../../components/DashboardComponents/navigation_left.vue";
import StepperForm from "src/components/ServicesComponents/StepperForm.vue";
import { menuData } from "src/data/menuData";
export default defineComponent({
  components: { ServicesDashboard, StepperForm, SidebarMenu },
  setup() {
    const leftDrawerOpen = ref(false);
    const $q = useQuasar();
    const router = useRouter();

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
    //dialog
    let dialog2 = ref(false);
    let showModal = (params) => {
      dialog2.value = true;
    };

    return {
      showModal,
      menuData,
      dialog2,
      Logout,
      myObject,
      activeclick,
      menu: ref(false),
      active,
      leftDrawerOpen,
      dialog: ref(false),
      firstItemEnabled: ref(false),
      cancelEnabled: ref(false),
      tab: ref("events"),
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
    };
  },
});
</script>
