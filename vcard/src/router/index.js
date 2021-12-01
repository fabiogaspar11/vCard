import { createRouter, createWebHistory } from 'vue-router'
import Register from '../views/Register.vue'
import Login from '../views/Login.vue'
import Home from '../views/Home.vue'
import Dashboard from '../views/Dashboard.vue'
import Transactions from '../views/transactions/Transactions.vue'
import TransactionDetails from '../views/transactions/TransactionDetails.vue'
import TransactionCreate from '../views/transactions/TransactionCreate.vue'
import TransactionEdit from '../views/transactions/TransactionEdit.vue'
import Categories from '../views/categories/Categories.vue'
import CategoriesCreate from '../views/categories/CategoriesCreate.vue'
import CategoriesEdit from '../views/categories/CategoriesEdit.vue'
import Userdetails from '../views/user/Userdetails.vue'
import Administrators from '../views/administrators/Administrators.vue'
import DashboardAdmin from '../views/DashboardAdmin.vue'
import AdministratorCreate from '../views/administrators/AdministratorCreate.vue'
import DefaultCategories from '../views/defaultCategories/DefaultCategories.vue'
import DefaultCategoriesCreate from '../views/defaultCategories/DefaultCategoriesCreate.vue'
import DefaultCategoriesEdit from '../views/defaultCategories/DefaultCategoriesEdit.vue'
import Vcards from '../views/vcards/Vcards.vue'
const routes = [
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
  },
  {
    path: '/dashboard',
    name: 'dashboard',
    component: Dashboard,
    props: true
  },
  {
    path: '/transactions',
    name: 'transactions',
    component: Transactions
  },
  {
    path: '/transactionDetails',
    name: 'transactionDetails',
    component: TransactionDetails,
    props: true
  },
  {
    path: '/userdetails',
    name: 'userdetails',
    component: Userdetails,
    props: true
  },
  {
    path: '/transaction',
    name: 'transaction',
    component: TransactionCreate
  },
  {
    path: '/transactionEdit',
    name: 'transactionEdit',
    component: TransactionEdit,
    props: true
  },
  {
    path: '/administrators',
    name: 'administrators',
    component: Administrators
  },
  {
    path: '/dashboardAdmin',
    name: 'dashboardAdmin',
    component: DashboardAdmin
  },
  {
    path: '/categories',
    name: 'categories',
    component: Categories,
  },
  {
    path: '/categoriesCreate',
    name: 'categoriesCreate',
    component: CategoriesCreate,
  },
  {
    path: '/categoriesEdit',
    name: 'categoriesEdit',
    component: CategoriesEdit,
    props: true
  },
  {
    path: '/administratorCreate',
    name: 'administratorCreate',
    component: AdministratorCreate
  },
  {
    path: '/defaultCategories',
    name: 'defaultCategories',
    component: DefaultCategories
  },
  {
    path: '/defaultCategoriesCreate',
    name: 'defaultCategoriesCreate',
    component: DefaultCategoriesCreate
  }
  ,
  {
    path: '/defaultCategoriesEdit',
    name: 'defaultCategoriesEdit',
    component: DefaultCategoriesEdit,
    props: true
  },
  {
    path: '/vcards',
    name: 'vcards',
    component: Vcards
  }
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

import store from '../store'

router.beforeEach((to, from, next) => {
  if (((to.name == 'home') || (to.name == 'login') || (to.name == 'register'))) {
    next()
    return
  }

  if (store.state.status == false){
    next({name : 'login'})
    return
  }
  next() 
});

export default router
