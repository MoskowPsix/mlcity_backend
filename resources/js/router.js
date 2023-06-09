import { createRouter, createWebHistory } from 'vue-router'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'users',
      component: () => import('../js/components/users/Users.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('./components/Login.vue')
    },
    {
      path: '/events',
      name: 'events',
      component: () => import('./components/event/Event.vue')
    },
    {
      path: '/role',
      name: 'role',
      component: () => import('./components/role/Role.vue')
    }
  ]

})

router.beforeEach((to, from, next) => {
  if (to.name !== 'login' && localStorage.role !== 'Admin' ) next({ name: 'login' })
  else next()
})

export default router