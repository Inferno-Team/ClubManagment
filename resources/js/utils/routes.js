import LoginView from '../views/LoginView.vue'
import HomeView from '../views/HomeView.vue'
import EmptyPage from '../layouts/EmptyPage.vue'
import AdminView from '../views/admin/AdminView.vue'
import AdminHomePage from '../views/admin/AdminHomePage.vue'
import {
    CONSTANCES
} from './utils'
export const routes = [

    {
        path: "/",
        name: "home",
        component: HomeView,
    },
    {
        path: "/login",
        name: "login",
        component: LoginView
    },

    {
        path: '/admin/',
        component: AdminView,
        children: [{
                path: '',
                name: 'admin-page',
                component: AdminHomePage
            },
            {
                path: 'create-new-manager',
                name: 'create-new-manager',
                component: EmptyPage
            }

        ]
    }
];
