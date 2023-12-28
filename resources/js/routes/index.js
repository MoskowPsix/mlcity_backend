import { createRouter, createWebHistory, createWebHashHistory  } from 'vue-router'
import { useAuthStore } from '../stores/AuthStore'
import { useLocalStorageStore } from '../stores/LocalStorageStore'
import { useLoaderStore } from '../stores/LoaderStore'
import { catchError} from 'rxjs/operators'
import { of, EMPTY } from 'rxjs'
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
      path: '/login/:token',
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
      path: '/user/sights',
      name: 'my-sights',
      component: () => import('../views/my-sights/MySights.vue'),
    },
    {
      path: '/events',
      name: 'events',
      component: () => import('../views/events/Event.vue'),
    },
    {
      path: '/user/events',
      name: 'my-events',
      component: () => import('../views/my_events/MyEvents.vue'),
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
      path: '/edits',
      name: 'edits',
      component: () => import('../views/history_content/HistoryContent.vue')
    },
    {
      path: '/edit/:id',
      name: 'edit',
      component: () => import('../views/history_content/show_history_content/HistoryContentShow.vue')
    }
  ]

})

router.beforeEach(async (to, from, next) => {
  useLoaderStore().openLoaderFullPage()
  axios.defaults.headers = {'Authorization': `Bearer ${localStorage.getItem('token')}`}
  await useLocalStorageStore().localStorageInit()
  await useAuthStore().getUserForToken().pipe(
    catchError(err => {
      localStorage.clear()
      useLocalStorageStore().localStorageInit()
      return of(EMPTY)
    })
  ).subscribe()
  
  useLoaderStore().closeLoaderFullPage()
  if(!useLocalStorageStore().token) {
    if (to.name === 'login') {
      return next()
    } else {
      return next({name: `login`})
    }
  }

  if (to.name === 'login' && useLocalStorageStore().token) {
    return next({name: 'my-event'})
  }
  if (to.name === 'login' && useLocalStorageStore().token) {
    return next({name: 'my-event'})
  }
  next()
})

export default router