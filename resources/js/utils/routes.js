import LoginView from '../views/LoginView.vue'
import HomeView from '../views/HomeView.vue'
import EmptyPage from '../layouts/EmptyPage.vue'
import AdminView from '../views/admin/AdminView.vue'
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
    //404 page
    {
        path: '*',
        component: EmptyPage
    },
    //admin pages
    {
        path: '/admin/',
        component: AdminView,
        children: [{
                path: '',
                name: 'admin-page',
                component: EmptyPage
            },
            {
                path: 'create-new-manager',
                name: 'create-new-manager',
                component: EmptyPage
            }

        ]
    }
];
