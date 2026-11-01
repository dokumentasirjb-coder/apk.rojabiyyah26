import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth';

import HomeView from '../views/public/HomeView.vue';
import JadwalKerjaView from '../views/public/JadwalKerjaView.vue';
import PembagianProyektorView from '../views/public/PembagianProyektorView.vue';
import LoginView from '../views/auth/LoginView.vue';
import RegisterMemberView from '../views/public/RegisterMemberView.vue';
import RegisterAccountView from '../views/public/RegisterAccountView.vue';
import VerifyLoanView from '../views/public/VerifyLoanView.vue';

import DashboardView from '../views/dashboard/DashboardView.vue';
import MembersView from '../views/members/MembersView.vue';
import DivisionsView from '../views/divisions/DivisionsView.vue';
import InventoryView from '../views/inventory/InventoryView.vue';
import LoansView from '../views/loans/LoansView.vue';
import RegionsView from '../views/regions/RegionsView.vue';
import ReportsView from '../views/reports/ReportsView.vue';
import CashView from '../views/cash/CashView.vue';
import SlidersView from '../views/sliders/SlidersView.vue';
import LandingManageView from '../views/admin/LandingManageView.vue';
import SettingsView from '../views/settings/SettingsView.vue';
import DangerZoneView from '../views/danger-zone/DangerZoneView.vue';
import AuditLogsView from '../views/audit/AuditLogsView.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: HomeView,
  },
  {
    path: '/jadwal-kerja',
    name: 'JadwalKerja',
    component: JadwalKerjaView,
  },
  {
    path: '/pembagian-proyektor',
    name: 'PembagianProyektor',
    component: PembagianProyektorView,
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
    meta: { guestOnly: true },
  },
  {
    path: '/register-member',
    name: 'RegisterMember',
    component: RegisterMemberView,
  },
  {
    path: '/register',
    redirect: '/register-member',
  },
  {
    path: '/register-account',
    name: 'RegisterAccount',
    component: RegisterAccountView,
  },
  {
    path: '/verify-loan/:code',
    alias: '/verify/:code',
    name: 'VerifyLoan',
    component: VerifyLoanView,
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardView,
    meta: { requiresAuth: true },
  },
  {
    path: '/members',
    name: 'Members',
    component: MembersView,
    meta: { requiresAuth: true },
  },
  {
    path: '/divisions',
    name: 'Divisions',
    component: DivisionsView,
    meta: { requiresAuth: true, roles: ['super_admin', 'admin'] },
  },
  {
    path: '/inventory',
    name: 'Inventory',
    component: InventoryView,
    meta: { requiresAuth: true },
  },
  {
    path: '/loans',
    name: 'Loans',
    component: LoansView,
    meta: { requiresAuth: true },
  },
  {
    path: '/regions',
    name: 'Regions',
    component: RegionsView,
    meta: { requiresAuth: true },
  },
  {
    path: '/reports',
    redirect: '/dashboard',
  },
  {
    path: '/cash',
    name: 'Cash',
    component: CashView,
    meta: { requiresAuth: true, roles: ['super_admin', 'bendahara', 'admin'] },
  },
  {
    path: '/sliders',
    name: 'Sliders',
    component: SlidersView,
    meta: { requiresAuth: true, roles: ['super_admin', 'admin'] },
  },
  {
    path: '/landing-manager',
    name: 'LandingManager',
    component: LandingManageView,
    meta: { requiresAuth: true, roles: ['super_admin', 'admin'] },
  },
  {
    path: '/settings',
    name: 'Settings',
    component: SettingsView,
    meta: { requiresAuth: true },
  },
  {
    path: '/danger-zone',
    name: 'DangerZone',
    component: DangerZoneView,
    meta: { requiresAuth: true, roles: ['super_admin'] },
  },
  {
    path: '/audit-logs',
    name: 'AuditLogs',
    component: AuditLogsView,
    meta: { requiresAuth: true, roles: ['super_admin', 'admin'] },
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/',
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore();

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return next({ name: 'Login', query: { redirect: to.fullPath } });
  }

  if (to.meta.guestOnly && authStore.isAuthenticated) {
    return next({ name: 'Dashboard' });
  }

  if (to.meta.roles && !to.meta.roles.includes(authStore.user?.role)) {
    return next({ name: 'Dashboard' });
  }

  next();
});

export default router;

