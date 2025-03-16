const routes = [
  {
    path: "/",
    component: () => import("layouts/MainLayout.vue"),
    children: [{ path: "", component: () => import("pages/IndexPage.vue") }],
  },
  {
    path: "/Dashboard",
    component: () => import("layouts/DashboardLayout.vue"),
    children: [
      {
        path: "",
        component: () =>
          import("../pages/SecretaryDashboard.vue/MainDashboard.vue"),
      },
    ],
  },
  {
    path: "/Services",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/ServiceModule/ApplicationForm.vue"
      ),
  },

  {
    path: "/EventSchedule",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/EventScheduler/EventScheduler_main.vue"
      ),
  },

  {
    path: "/Marriage",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/CannonicalRecords/MarriageMain.vue"
      ),
  },
  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
