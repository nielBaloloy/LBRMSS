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
      <SidebarMenu :menuItems="menuData2" />
    </q-drawer>

    <q-page-container>
      <div class="row justify-end">
        <div class="q-pa-md">
          <q-btn label="Add" color="primary" @click="donationDialog = true" />
        </div>
      </div>
      <q-dialog v-model="donationDialog">
        <div class="donationPanwel">
          <q-card class="q-pa-md" style="width: 500px">
            <div class="text-h6">Donation Form</div>
            <q-form @submit="submitDonation" class="q-gutter-md">
              <!-- Donor Info -->
              <q-input
                v-model="donationForm.donor.name"
                label="Full Name"
                outlined
              />
              <q-input
                v-model="donationForm.donor.email"
                label="Email (optional)"
                type="email"
                outlined
              />

              <!-- Donation Details -->
              <q-select
                v-model="donationForm.donation.type"
                :options="donationTypes"
                label="Donation Type"
                outlined
              />
              <q-input
                v-model="donationForm.donation.description"
                type="textarea"
                label="Description"
                outlined
              />

              <q-input
                v-model.number="donationForm.donation.amount"
                label="Amount (₱)"
                type="number"
                outlined
                prefix="₱"
              />

              <q-select
                v-model="donationForm.donation.paymentMethod"
                :options="paymentMethods"
                label="Payment Method"
                outlined
              />

              <q-btn
                label="Donate Now"
                color="primary"
                type="submit"
                class="full-width q-mt-md"
              />
            </q-form>
          </q-card>
        </div>
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
} from "vue";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter } from "vue-router";
import SidebarMenu from "../../../components/DashboardComponents/navigation_left.vue";
import { menuData, menuData2 } from "src/data/menuData";
import { api } from "src/boot/axios";
// ✅ Async function defined before setup()

export default defineComponent({
  components: { SidebarMenu },

  setup() {
    const dataLoaded = ref(false); // ✅ Track data loading state
    onMounted(async () => {
      await nextTick();

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

    const donationTypes = [
      { label: "Church Fund", value: "church_fund" },
      { label: "Mass Intention", value: "mass_intention" },
      { label: "Building Project", value: "building_project" },
      { label: "Charity / Outreach", value: "charity" },
      { label: "Others", value: "others" },
    ];

    // Payment methods (example)
    const paymentMethods = [
      { label: "GCash", value: "gcash" },
      { label: "Bank Transfer", value: "bank_transfer" },
      { label: "Cash (On-Site)", value: "cash" },
    ];
    const donationForm = ref({
      donor: {
        name: "",
        email: "",
      },
      donation: {
        type: "", // e.g., 'Church Fund', 'Mass Intention'
        description: null,
        amount: null, // e.g., 500
        paymentMethod: "", // e.g., 'GCash', 'Bank Transfer'
      },
      date: new Date().toISOString(), // For record-keeping
    });
    const submitDonation = () => {
      console.log("Donation Form Submitted:", donationForm.value);

      $q.dialog({
        title: "Confirm",
        message: "This will be saved to database",
        cancel: true,
        persistent: true,
      })
        .onOk(() => {
          api
            .post("Donation.php", donationForm.value)
            .then((response) => {
              if (response.data.message == "") {
                $q.notify({
                  type: "positive",
                  message: "Payments submitted successfully!",
                });
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
    let donationDialog = ref(false);
    let description = ref(false);
    watch(
      () => donationForm.value.donation.type,
      (newVal) => {
        if (
          newVal["value"] == "mass_intention" ||
          newVal["value"] == "others"
        ) {
          description.value = true;
        } else {
          description.value = false;
        }
        console.log("Donation Type changed:", newVal);
      },
      { immediate: true }
    );
    return {
      description,
      donationDialog,
      donationTypes,
      paymentMethods,
      submitDonation,
      donationForm,
      menuData2,
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
