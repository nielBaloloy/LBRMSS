
const routes = [
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') }
    ]
  },
  {
    path: '/SecretaryDashboard',
    component: () => import('layouts/DashboardLayout.vue'),
    children: [
      { path: '', component: () => import('../pages/SecretaryDashboard.vue/MainDashboard.vue') },
     
       ]
  },
  {
    path: '/SecretaryDashboard/Services', component: () => import('../pages/SecretaryDashboard.vue/ServiceModule/ApplicationForm.vue') },
  { path: '/SecretaryDashboard/Account-Management', component: () => import('../pages/SecretaryDashboard.vue/AccountManagement/AccountManagement.vue') },

  // Always leave this as last one,
  // but you can also remove it
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
