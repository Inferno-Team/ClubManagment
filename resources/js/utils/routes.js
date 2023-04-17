import LoginView from '../views/LoginView.vue'
import HomeView from '../views/HomeView.vue'
import EmptyPage from '../layouts/EmptyPage.vue'
import AdminView from '../views/admin/AdminView.vue'
import AdminHomePage from '../views/admin/AdminHomePage.vue'
import AllSubscriptions from '../views/admin/AllSubscriptions.vue'
import SingleSubscriptionPage from '../views/admin/SingleSubscriptionPage.vue'
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
                path: '/club/:id',
                name: 'single-club',
                component: EmptyPage,
                props: true
            },
            {
                path: 'subscriptions',
                name: 'subscriptions',
                component: AllSubscriptions
            },
            {
                path: 'subscriptions/:id',
                name: 'single-subscription',
                component: SingleSubscriptionPage,
                props: true,

            }

        ]
    }
];
