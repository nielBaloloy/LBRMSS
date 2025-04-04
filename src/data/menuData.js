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
      { to: "/Dashboard", label: "Certification Request" },
    ],
  },
  {
    label: "Event Management",
    icon: "event",
    children: [
      { to: "/EventSchedule", label: "Scheduled Events" },
      { to: "/SecretaryDashboard", label: "Priest Schedule" },
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
        to: "/SecretaryDashboard/Account-Management",
        label: "Baptismal Records",
      },
      {
        to: "/SecretaryDashboard/Account-Management",
        label: "Burial Records",
      },
      { to: "/SecretaryDashboard", label: "Confirmation Records" },
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
];

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
    to: "/Services",
    label: "Donations",
    icon: "account_balance_wallet",
  },
  {
    to: "/Services",
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
