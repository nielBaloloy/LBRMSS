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
  {
    path: "/Baptism",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/CannonicalRecords/BaptismMain.vue"
      ),
  },
  {
    path: "/Confirmation",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/CannonicalRecords/ConfirmationMain.vue"
      ),
  },
  {
    path: "/Burial",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/CannonicalRecords/BurialMain.vue"
      ),
  },
  {
    path: "/ServicePayment",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/FinanceModule/ServiceFeePayment.vue"
      ),
  },
  {
    path: "/Tools_and_Setup",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/Tools/Service_Fee_Assignment.vue"
      ),
  },
  {
    path: "/Donations",
    component: () =>
      import("../pages/SecretaryDashboard.vue/FinanceModule/DonationPage.vue"),
  },
  {
    path: "/FinancialRecords",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/FinanceModule/FinancialRecord.vue"
      ),
  },
  {
    path: "/FinancialReport",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/FinanceModule/FinancialReport.vue"
      ),
  },
  {
    path: "/MassManagement",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/EventScheduler/MassManagement.vue"
      ),
  },
  {
    path: "/PriestSetup",
    component: () =>
      import("../pages/SecretaryDashboard.vue/Tools/PriestSetup.vue"),
  },
  {
    path: "/Priest_Schedule",
    component: () =>
      import(
        "../pages/SecretaryDashboard.vue/EventScheduler/PriestSchedule_mian.vue"
      ),
  },
  //ServicePayment
  // Always leave this as last one,
  // but you can also remove it
  {
    path: "/:catchAll(.*)*",
    component: () => import("pages/ErrorNotFound.vue"),
  },
];

export default routes;
