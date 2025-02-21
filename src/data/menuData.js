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
      { to: "/SecretaryDashboard", label: "Scheduled Events" },
      { to: "/SecretaryDashboard", label: "Priest Schedule" },
    ],
  },
  {
    label: "Canonical Records",
    icon: "folder_open",
    children: [
      {
        to: "/SecretaryDashboard/Account-Management",
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
