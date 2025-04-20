export const menuData = [
  {
    to: "/Dashboard",
    label: "Home",
    icon: "home",
  },
  {
    label: "Services",
    icon: "event",
    children: [
      { to: "/Services", label: "Ceremonial Services" },
      // { to: "/Request_Certificate", label: "Certification Request" },
    ],
  },
  {
    label: "Event Management",
    icon: "event",
    children: [
      { to: "/EventSchedule", label: "Scheduled Events" },
      { to: "/Priest_Schedule", label: "Priest Schedule" },
      { to: "/MassManagement", label: "Mass" },
    ],
  },
  {
    label: "Canonical Records",
    icon: "folder_open",
    children: [
      {
        to: "/Marriage",
        label: "Marriage Records",
      },
      {
        to: "/Baptism",
        label: "Baptismal Records",
      },
      {
        to: "/Burial",
        label: "Burial Records",
      },
      { to: "/Confirmation", label: "Confirmation Records" },
    ],
  },
  {
    to: "/SecretaryDashboard/Account-Management",
    label: "Accounts",
    icon: "group",
  },
  {
    to: "/SecretaryDashboard/Account-Management",
    label: "Logs and Audit Trails",
    icon: "dvr",
  },
  {
    to: "/PriestSetup",
    label: "Priest Setup",
    icon: "settings",
  },
];
/** Financial Module */
export const menuData2 = [
  {
    to: "/Dashboard",
    label: "Home",
    icon: "home",
  },

  {
    to: "/ServicePayment",
    label: "Service Fees & Payments",
    icon: "point_of_sale",
  },
  {
    to: "/Donations",
    label: "Donations",
    icon: "account_balance_wallet",
  },
  {
    to: "/FinancialRecords",
    label: "Financial Records",
    icon: "summarize",
  },
  {
    to: "/FinancialReport",
    label: "Financial Reports",
    icon: "summarize",
  },
  {
    label: "Tools",
    icon: "construction",
    children: [
      {
        to: "/Tools_and_Setup",
        label: "Service Fee Assignment",
      },
    ],
  },
];
export const baseUrl = "http://localhost/LBRMSS/Server/API/";
