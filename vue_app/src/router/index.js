import Vue from 'vue'
import VueRouter from 'vue-router'

import LoginPage from '@/components/Pages/Auth/LoginPage'
import CollectionPage from '@/components/Pages/CollectionPage'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: CollectionPage
  },
  {
    path: '/collection',
    name: 'Collection',
    component: CollectionPage
  },
  {
    path: '/about',
    name: 'About',
    // route level code-splitting
    // this generates a separate chunk (about.[hash].js) for this route
    // which is lazy-loaded when the route is visited.
    component: () => import(/* webpackChunkName: "about" */ '../views/About.vue')
  },
  // Auth Pages
  {
    path: "/login",
    name: "Login",
    component: LoginPage
  }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
