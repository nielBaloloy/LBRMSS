<template>
  <div class="">
    <q-layout
      view="lHh lpr lFf"
      container
      style="height: 100vh"
      class="bg-grey-3 rounded-borders"
    >
      <q-header bordered class="bg-white text-primary">
        <q-toolbar>
          <q-toolbar-title> LBRMSS </q-toolbar-title>
          <div class="account">
            <q-avatar>
              <img src="../../assets/quasar-logo-vertical.svg" />
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
                      <img src="../../assets/quasar-logo-vertical.svg" />
                    </q-avatar>
                  </div>
                  <div class="profile-info q-pa-sm">
                    <p
                      class="User-Account text-center q-mt-sm"
                      style="font-size: small; font-weight: 600"
                    >
                      <!-- username -->

                      {{ myObject[0].Username }}
                      <br />
                      <span class="text-amber-6">
                        <!-- Position -->
                        {{ myObject[0].AccessLvl }}</span
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

      <q-footer bordered class="bg-grey-3 text-primary">
        <q-tabs
          no-caps
          active-color="primary"
          indicator-color="transparent"
          class="text-grey-8"
          v-model="tab"
        >
          <q-tab name="images" label="Images" />
          <q-tab name="videos" label="Videos" />
          <q-tab name="articles" label="Articles" />
        </q-tabs>
      </q-footer>

      <q-page-container>
        <q-page class="q-pa-md">
          <priestModule v-if="myObject[0].AccessLvl === 'priest'" />
        </q-page>
      </q-page-container>
    </q-layout>
  </div>
</template>

<script>
import { ref, defineComponent, onMounted } from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter, useRoute } from "vue-router";
import priestModule from "src/components/DashboardComponents/iosPage.vue";
import {
  getScheduledPriest,
  schedulePriest,
  getScheduledIndividualPriest,
  scheduleOnePriest,
} from "src/composables/getPriestSched";
export default defineComponent({
  components: {
    priestModule,
  },
  setup() {
    const $q = useQuasar();
    const router = useRouter();
    onMounted(() => {
      getScheduledIndividualPriest(myObject.value[0].account_id);
    });
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

    return {
      Logout,
      myObject,
      tab: ref("images"),
    };
  },
});
</script>
