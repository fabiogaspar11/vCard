import { createRouter, createWebHistory } from 'vue-router'
import Register from '../views/Register.vue'
import Login from '../views/Login.vue'
import Home from '../views/Home.vue'
import Dashboard from '../views/Dashboard.vue'
import Transactions from '../views/transactions/Transactions.vue'
import TransactionDetails from '../views/transactions/TransactionDetails.vue'
import TransactionCreate from '../views/transactions/TransactionCreate.vue'
import TransactionEdit from '../views/transactions/TransactionEdit.vue'
import Userdetails from '../views/user/Userdetails.vue'

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

  if (store.state.status == false) {
    next({ name: 'login' })
    return
  }
  next()
});

export default router
