<template>
  <q-layout view="hHh Lpr lff" container style="height: 100vh">
    <q-page-container>
      <q-page class="main-container">
        <div class="LOGIN-FORM justify-center">
          <div class="BACKGROUND"></div>
          <!-- Create Account Info container -->
          <div class="LOG-INFO q-pa-lg">
            <div class="create-acccount-title">
              <p class="q-pl-md q-pb-xl text-amber-7">LBRMSS</p>
              <p
                class="q-pt-xs q-pl-md text-grey-9"
                style="font-size: 23px; font-weight: 510"
              >
                Welcome
              </p>
              <span class="text-grey-5 q-pa-md text-weight-small justify-start"
                >Discover better ways to manage informations. Lets get
                started!</span
              >
            </div>
            <q-form @submit.prevent="login(loginData)">
              <div class="create-account-body q-pa-lg q-pl-md q-gutter-xl">
                <!-- NAME -->
                <div class="input-field q-pb-sm">
                  <p class="name q-pb-xs">Username</p>
                  <q-input
                    outlined
                    v-model="loginData.username"
                    :dense="dense"
                    :rules="[(val) => !!val || 'Required']"
                  />
                </div>
                <!-- USERNAME -->
                <div class="input-field q-pb-lg">
                  <div class="pass">
                    <p class="name q-pb-xs">Password</p>
                    <q-input
                      v-model="loginData.password"
                      ref="inputRef"
                      outlined
                      :type="isPwd ? 'password' : 'text'"
                      :dense="dense"
                      :rules="[(val) => !!val || 'Required']"
                    >
                      <template v-slot:append>
                        <q-icon
                          color="amber-6"
                          :name="isPwd ? 'visibility_off' : 'visibility'"
                          class="cursor-pointer"
                          @click="isPwd = !isPwd"
                        />
                      </template>
                    </q-input>
                  </div>
                </div>

                <!-- Loginbtn -->
                <div class="btn-grp q-pb-md">
                  <q-btn
                    class="btn"
                    color="amber-6"
                    text-color="white"
                    type="submit"
                    label="Login"
                  />
                </div>
              </div>
            </q-form>
          </div>

          <!-- Create Account Info container END -->
        </div>
      </q-page>
    </q-page-container>
  </q-layout>
</template>

<style lang="scss" scoped>
* {
  margin: 0;
  overflow: hidden;
}
.main-container {
  background: rgb(221, 221, 221);
  padding: 3em;
}
.LOGIN-FORM {
  display: flex;
  flex-wrap: wrap;
  width: 100%;
  max-height: auto;
  margin: auto;
  align-self: center;
}
.LOGIN-FORM > .BACKGROUND {
  background: url("../css/LOGIN-IMG/LoginBG.jpg");
  width: 25em;
  background-position: 50%;
  background-size: cover;
}
.LOGIN-FORM > .LOG-INFO {
  background-color: rgb(248, 248, 248);
  width: 38em;
  flex-shrink: 1;
  height: 100%;
}
.input-field-pass {
  display: flex;
  flex-wrap: wrap;
  width: 100%;
  justify-content: space-between;
}
.input-field-pass > .pass {
  flex-shrink: 1;
}
.btn-grp {
  width: 100%;
  background-color: aliceblue;
  display: flex;
  flex-wrap: wrap-reverse;
  justify-content: space-between;
}
.btn-grp .btn {
  flex: 1;
}
.warning {
  position: absolute;
}
.it-1 {
  display: flex;
  flex-direction: row;
}
</style>
<script>
import { defineComponent, ref, onMounted } from "vue";
import {
  LoginPayload,
  account,
  status,
  position,
  isActive,
} from "../composables/login";
import { useQuasar, SessionStorage } from "quasar";
import { useRouter, useRoute } from "vue-router";
import Swal from "sweetalert2";
export default defineComponent({
  setup() {
    const $q = useQuasar();
    const inputRef = ref(null);
    const router = useRouter();
    let timer;
    let loginData = ref({
      username: null,
      password: null,
      Module: "Login",
    });

    let sessionkey = SessionStorage.getItem("log");
    if (sessionkey === null || "") {
      router.push({ path: "/" });
    } else {
      router.push({ path: "/dashboard" });
    }
    let login = (payload) => {
      LoginPayload(payload);
      setTimeout(() => {
        loadData();
      }, 1000);

      //loading function'
      let loadData = () => {
        $q.loading.show({
          message:
            "Verifying Credentials, Searching the sacred archives for your account",
        });

        timer = setTimeout(() => {
          if (status.value === "Success" && isActive.value === 1) {
            if (position.value == "Secretary" || position.value == "Admin") {
              let accDetails = JSON.stringify(account.value);
              SessionStorage.set("log", accDetails);
              setTimeout(
                () =>
                  router.push({
                    path: "/dashboard",
                  }),
                Swal.fire({
                  title: "Hallelujah! You're in!",
                  text: new Date().toLocaleString(),
                  icon: "success",
                })
              );
            }
            if (position.value == "Assistant Secretary") {
              let accDetails = JSON.stringify(account.value);
              SessionStorage.set("log", accDetails);

              setTimeout(
                () =>
                  // sendlogs(payload),
                  router.push({
                    path: "/dashboard",
                  }),
                Swal.fire({
                  title: "Hallelujah! You're in!",
                  text: new Date().toLocaleString(),
                  // imageUrl: "https://i.imgur.com/O5l5SgN.png",
                  // imageWidth: 100,
                  // imageHeight: 100,
                  // imageAlt: "Angel Icon",
                  icon: "success",
                }),
                1000
              );
            } else if (position.value == "Cashier") {
              let accDetails = JSON.stringify(account.value);
              SessionStorage.set("log", accDetails);

              setTimeout(
                () =>
                  router.push({
                    path: "/dashboard",
                  }),
                Swal.fire({
                  title: "Hallelujah! You're in!",
                  text: new Date().toLocaleString(),
                  // imageUrl: "https://i.imgur.com/O5l5SgN.png", // Use a custom icon (e.g., angel, halo, cross)
                  // imageWidth: 100,
                  // imageHeight: 100,
                  // imageAlt: "Angel Icon",
                  icon: "success",
                }),

                1000
              );
            }
          } else if (status.value === "Failed") {
            $q.notify({
              message: "Username or Password is incorrect",
              color: "red-7",
            });
          } else if (isActive.value === "No") {
            $q.notify({
              message: "Your Account is no longer active  ",
              color: "red-7",
            });
          }

          $q.loading.hide();
          timer = void 0;
        }, 3000);
      };
    };
    return {
      inputRef,
      password: ref(""),
      isPwd: ref(true),
      dense: ref(true),
      login,
      loginData,
    };
  },
});
</script>
