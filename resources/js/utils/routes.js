import LoginView from "../views/LoginView.vue";
import HomeView from "../views/HomeView.vue";
import EmptyPage from "../layouts/EmptyPage.vue";
import AdminView from "../views/admin/AdminView.vue";
import AdminHomePage from "../views/admin/AdminHomePage.vue";
import AllSubscriptions from "../views/admin/AllSubscriptions.vue";
import SingleSubscriptionPage from "../views/admin/SingleSubscriptionPage.vue";
import SingleClubPage from "../views/admin/SingleClubPage.vue";

import ManagerView from "../views/manager/ManagerView.vue";
import ManagerClubPage from "../views/manager/ManagerClubPage.vue";
import ManagerAllSubscriptions from "../views/manager/ManagerAllSubscriptions.vue";
import SingleManagerSubscriptionPage from "../views/manager/SingleManagerSubscriptionPage.vue";
import MyCustomers from "../views/manager/MyCustomers.vue";
import ShowAllCustomersRequests from "../views/manager/ShowAllCustomersRequests.vue";
import { TYPES, CONSTANCES } from "./utils";

import TrainerPage from "../views/trainer/TrainerPage.vue";
import TrainerHomePage from "../views/trainer/TrainerHomePage.vue";
const gaurd = function (to, from, next) {
    axios
        .get("/api/user")
        .then((response) => {
            // Token is valid, so continue
            next();
        })
        .catch((error) => {
            // There was an error so redirect
            window.location.href = "/login";
        });
};
const loginGuard = function (to, from, next) {
    let token = localStorage.getItem(CONSTANCES.TOKEN_NAME);
    if (token != null && token != undefined) {
        window.location.href = "/";
    } else next();
};
const adminGaurd = function (to, from, next) {
    axios
        .get("/api/user")
        .then((response) => {
            if (response.data.type == TYPES.ADMIN) next();
            else window.location.href = "/";
        })
        .catch((error) => {
            // There was an error so redirect
            window.location.href = "/login";
        });
};

const managerGaurd = function (to, from, next) {
    axios
        .get("/api/user")
        .then((response) => {
            if (response.data.type == TYPES.MANAGER) next();
            else window.location.href = "/";
        })
        .catch((error) => {
            // There was an error so redirect
            window.location.href = "/login";
        });
};
const trainerGaurd = function (to, from, next) {
    axios
        .get("/api/user")
        .then((response) => {
            if (response.data.type == TYPES.TRAINER) next();
            else window.location.href = "/";
        })
        .catch((error) => {
            // There was an error so redirect
            window.location.href = "/login";
        });
};

export const routes = [
    {
        path: "/",
        name: "home",
        component: HomeView,
        beforeEnter: gaurd,
    },
    {
        path: "/login",
        name: "login",
        component: LoginView,
        beforeEnter: loginGuard,
    },

    {
        path: "/admin/",
        component: AdminView,
        beforeEnter: adminGaurd,
        children: [
            {
                path: "/admin/clubs/",
                name: "admin-page",
                component: AdminHomePage,
            },
            {
                path: "/admin/club/:id",
                name: "single-club",
                component: SingleClubPage,
                props: true,
            },
            {
                path: "subscriptions",
                name: "subscriptions",
                component: AllSubscriptions,
            },
            {
                path: "/subscriptions/:id",
                name: "single-subscription",
                component: SingleSubscriptionPage,
                props: true,
            },
        ],
    },
    {
        path: "/manager/",
        component: ManagerView,
        beforeEnter: managerGaurd,
        children: [
            {
                path: "/",
                name: "manager-page",
                component: ManagerClubPage,
            },
            {
                path: "/manager/subscriptions",
                name: "manager-subscriptions-page",
                component: ManagerAllSubscriptions,
            },
            {
                path: "/manager/customers",
                name: "manager-customers-page",
                component: MyCustomers,
            },
            {
                path: "/manager/subscriptions/:id",
                name: "manager-subscription",
                component: SingleManagerSubscriptionPage,
                props: true,
            },
            {
                path: "/manager/requests",
                name: "manager-requests",
                component: ShowAllCustomersRequests,
            },
        ],
    },
    {
        path: "/trainer/",
        component: TrainerPage,
        beforeEnter: trainerGaurd,
        children: [
            {
                path: "/",
                name: "trainer-page",
                component: TrainerHomePage,
            },
        ],
    },
];
