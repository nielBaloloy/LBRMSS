<template>
  <q-layout view="lHh lpR fFf" class="bg-accent">

    <q-header reveal elevated class="bg-info text-dark">
      <q-toolbar>
        <q-btn dense flat round icon="menu" @click="toggleLeftDrawer" />

        <q-toolbar-title>
         
         LBRMSS-Dashboard
        </q-toolbar-title>
        <div class="account">
          <q-avatar>
            <img src="../../assets/quasar-logo-vertical.svg"/>
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
                  <img src="../../assets/quasar-logo-vertical.svg"/>
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
                    <q-item
                      clickable
                      v-ripple
                      class="it-1"
                    
                    >
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
                    <q-item
                      clickable
                      v-ripple
                      class="it-1"
                     
                    >
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

    <q-drawer  class="bg-white text-dark q-pa-md" show-if-above v-model="leftDrawerOpen" side="left" behavior="desktop" bordered  >
      <div class="LOGO q-mb-xl"></div>
    <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
          <q-icon color="primary" name="home" />
      </q-item-section>

      <q-item-section>
          Home
      </q-item-section>
      </q-item>
      <q-item  to="/SecretaryDashboard/Services"  :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
          <q-icon color="primary" name="group" />
      </q-item-section>

      <q-item-section>
          Services
      </q-item-section>
      </q-item>
     
          
      <q-expansion-item
        expand-separator
        icon="event"
        label="Event Management"
        header-class="text-dark"
      >
        
          <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
          <q-item-section avatar></q-item-section>
              <q-item-section>
                  Calendar 
              </q-item-section>
          </q-item>
          <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
          <q-item-section avatar></q-item-section>
              <q-item-section>
                  Priest Schedule
              </q-item-section>
          </q-item>
          </q-expansion-item>
      
          <q-expansion-item
        expand-separator
        icon="folder_open"
        label=" Cannonical Records"
        header-class="text-dark"
      >
        
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
        
      </q-item-section>

      <q-item-section>
         Marriage Records
      </q-item-section>
      </q-item>
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
        
      </q-item-section>

      <q-item-section>
        Baptismal Records
      </q-item-section>
      </q-item>
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
        
      </q-item-section>

      <q-item-section>
         Burial Records
      </q-item-section>
      </q-item>
          <q-item to="/SecretaryDashboard" :active="active" active-class="text-orange" exact @click="activeclick()">
          <q-item-section avatar></q-item-section>
              <q-item-section>
                Confirmation Records
              </q-item-section>
          </q-item>
          </q-expansion-item>
      
      
      

      
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
          <q-icon color="primary" name="group" />
      </q-item-section>

      <q-item-section>
          Accounts
      </q-item-section>
      </q-item>
      <q-item to="/SecretaryDashboard/Account-Management" :active="active" active-class="text-orange" exact @click="activeclick()">
      <q-item-section avatar>
          <q-icon color="primary" name="dvr" />
      </q-item-section>

      <q-item-section>
          Logs and Audit Trails
      </q-item-section>
      </q-item>
    </q-drawer>

    <q-page-container class="bg-accent">
     
     <div class="banner q-pa-lg">
      
      <q-banner class="banner-plate bg-secondary text-dark q-pa-md">
      <p class="text-h5">  Welcome {{ myObject.Fullname }}</p>
     
    </q-banner>
    <EventCard />
     </div>
     
      
    </q-page-container>

  </q-layout>
</template>

<script>
import { ref ,defineComponent } from 'vue';
import { useQuasar, SessionStorage } from "quasar";
import { useRouter, useRoute } from "vue-router";
import EventCard  from '../../components/DashboardComponents/ScheduleCard-PLate.vue';
export default defineComponent( {
  components : {EventCard},
  setup () {
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
   
    let active=ref(false);
      let activeclick=()=>{
          active
          .value=true;
      }
      //logout
      const Logout=()=>{
       
      $q.dialog({
        title: 'Logout',
        message: 'Are you sure you want to logout?',
        cancel: true,
        persistent: true
      }).onOk(() => {
        SessionStorage.clear();
        router.push({ path: "/" });
      }).onOk(() => {
        // console.log('>>>> second OK catcher')
      }).onCancel(() => {
        // console.log('>>>> Cancel')
      }).onDismiss(() => {
        // console.log('I am triggered on both OK and Cancel')
      })
    
      }
    return {
      Logout,
     myObject,
      activeclick,
      menu: ref(false),
      active,
      leftDrawerOpen,
      toggleLeftDrawer () {
        leftDrawerOpen.value = !leftDrawerOpen.value
      }
    }
  },
  
})
</script>
<style scoped>
@font-face {
font-family: opensans;
src: url(../../../css/Montserrat,Open_Sans/Open_Sans/static/OpenSans-Medium.woff);
}

       
.titlebar{
  font-family: opensans;
  text-decoration: none;
  color:rgb(52, 52, 52)
};

</style>