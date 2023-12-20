import { createRouter, createWebHistory, createWebHashHistory  } from 'vue-router'
import { useAuthStore } from '../stores/AuthStore'
import { useLocalStorageStore } from '../stores/LocalStorageStore'
import { useLoaderStore } from '../stores/LoaderStore'
import axios from 'axios'


//const authStore = useAuthStore()
//const localStorageStore = useLocalStorageStore()
const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'users',
      component: () => import('../views/users/Users.vue')
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('../views/login/Login.vue')
    },
    {
      path: '/sights',
      name: 'sights',
      component: () => import('../views/sights/Sights.vue')
    },
    {
      path: '/sight/:id',
      name: 'sight',
      component: () => import('../views/sights/sight_show/SightShow.vue'),
    },
    {
      path: '/events',
      name: 'events',
      component: () => import('../views/events/Event.vue'),
    },
    {
      path: '/event/:id',
      name: 'event',
      component: () => import('../views/events/event_show/EventShow.vue'),
    },
    {
      path: '/types',
      name: 'types',
      component: () => import('../views/types/Types.vue')
    },
    {
      path: '/role',
      name: 'role',
      component: () => import('../views/roles/Roles.vue')
    },

    {
      path: '/logs',
      name: 'logs',
      component: () => import('../views/logs/Logs.vue')
    },
    {
      path: '/edit',
      name: 'edit',
      component: () => import('../views/history_content/HistoryContent.vue')
    }
  ]

})

// router.beforeEach((to, from, next) => {
//   if (to.name !== 'login' && localStorage.role !== 'Admin' && localStorage.role !== 'root' ) {
//     useBarStore().closeBar()
//     next({  name: 'login' })
//   } else { 
//     useBarStore().showBar()
//     next() 
//   }
// })
router.beforeEach(async (to, from, next) => {
  useLoaderStore().openLoaderFullPage()
  axios.defaults.headers = {'Authorization': `Bearer ${localStorage.getItem('token')}`}
  await useLocalStorageStore().localStorageInit()
  await useAuthStore().getUserForToken()
  .then(response => {

  })
  .catch(async err => {
    localStorage.clear()
    useLocalStorageStore().localStorageInit()
  })
  useLoaderStore().closeLoaderFullPage()
  if(!useLocalStorageStore().token) {
    if (to.name === 'login') {
      return next()
    } else {
      return next({name: 'login'})
    }
  }

  if (to.name === 'login' && useLocalStorageStore().token) {
    return next({name: 'users'})
  }
  next()
})

export default router