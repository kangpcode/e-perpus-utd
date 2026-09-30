import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import CatalogView from '../views/CatalogView.vue'
import BookshelfView from '../views/BookshelfView.vue'
import RoleDashboardView from '../views/RoleDashboardView.vue'
import ClayPlaygroundView from '../views/ClayPlaygroundView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HomeView,
    meta: { title: 'DIGIPUS — Perpustakaan Digital Digitech University' }
  },
  {
    path: '/katalog',
    name: 'catalog',
    component: CatalogView,
    meta: { title: 'Katalog Koleksi — DIGIPUS Digitech University' }
  },
  {
    path: '/rak-saya',
    name: 'bookshelf',
    component: BookshelfView,
    meta: { title: 'Rak Virtual & Sirkulasi — DIGIPUS' }
  },
  {
    path: '/roles',
    name: 'roles',
    component: RoleDashboardView,
    meta: { title: 'Multi-Role Architecture — DIGIPUS' }
  },
  {
    path: '/clay-playground',
    name: 'clay-playground',
    component: ClayPlaygroundView,
    meta: { title: 'Claymorphism Design System Lab — DIGIPUS' }
  },
  {
    path: '/:pathMatch(.*)*',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() {
    return { top: 0, behavior: 'smooth' }
  }
})

router.afterEach((to) => {
  if (to.meta.title) {
    document.title = to.meta.title
  }
})

export default router
