

import Vue from 'vue'
import VueRouter from 'vue-router'
import store from './store'

Vue.use(VueRouter)

import DashboardLayout from './components/Layout/dashboardLayout.vue';
import LoginLayout from './components/Layout/loginLayout.vue';
import Logout from './components/logoutLayout.vue';
import ChangePassword from './components/dashboard/changePassword.vue';
import UserManager from './components/dashboard/userManager.vue'
import QuizManager from './components/dashboard/quizManager.vue'
import OptionManager from './components/dashboard/optionManager.vue'
import Quiz from './components/dashboard/attemptQuiz.vue'
const routes = [
    {
        name: 'LoginLayout',
        path: '/login',
        component: LoginLayout,
    },
    {
        name: 'Logout',
        path: '/logout',
        component: Logout,
    },
    {
        name: 'DashboardContent',
        path: '/',
        component: DashboardLayout,
        meta: { requiresAuth: true },
        children: [
            {
                name: 'changePassword',
                path: 'changepassword',
                component: ChangePassword
            },
            {
                name: 'UserManager',
                path: 'usermanager',
                component: UserManager
            },
            {
                name: 'QuizManager',
                path: 'quizmanager',
                component: QuizManager
            },
            {
                name: 'OptionManager',
                path: 'quizmanager/:id',
                component: OptionManager
            },
            {
                name: 'Quiz',
                path: 'quiz',
                component: Quiz
            }
        ]
    },
];
const router = new VueRouter({mode: 'history', routes: routes});
router.beforeEach((to, from, next) => {

    // check if the route requires authentication and user is not logged in
    if (to.matched.some(route => route.meta.requiresAuth) && !store.state.isLoggedIn) {
        // redirect to login page
        next({ name: 'LoginLayout' })
        return
    }

    // if logged in redirect to dashboard
    if(to.name === 'LoginLayout' && store.state.isLoggedIn) {
        next({ name: 'DashboardContent' })
        return
    }
    // if logged in redirect to dashboard
    if(to.name === 'DashboardContent' && !store.state.isLoggedIn) {
        next({ name: 'LoginLayout' })
        return
    }
    next()
})
export default router