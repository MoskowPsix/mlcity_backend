import { createRouter, createWebHistory, createWebHashHistory  } from 'vue-router'
import { useBarStore } from './stores/barStore'


const router = createRouter({
  history: createWebHashHistory ("/"),
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
      path: '/sights',
      name: 'sights',
      component: () => import('./components/sights/Sights.vue')
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
    },

    {
      path: '/logs',
      name: 'logs',
      component: () => import('./components/logs/Logs.vue')
    }
  ]

})

router.beforeEach((to, from, next) => {
  if (to.name !== 'login' && localStorage.role !== 'Admin' ) { 
    next({  name: 'login' })
  } else { 
    useBarStore().showBar()
    next() 
  }
})

export default router