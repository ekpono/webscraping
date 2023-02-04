import { createWebHistory, createRouter } from "vue-router";
import Login from "./Pages/Login.vue";
import Register from "./Pages/Register.vue";
import Dashboard from "./Pages/Dashboard.vue";
import NotFound from './Pages/NotFound.vue'
const routes = [
    {
        path: "/login",
        name: "Login",
        component: Login,
        meta: {
            title: 'Web Scrape - Login',
        }
    },
    {
        path: "/register",
        name: "Contact",
        component: Register,
        meta: {
            title: 'Web Scrape - Register',
        }
    },

    {
        path: "/dashboard",
        name: "Dashboard",
        component: Dashboard,
        meta: {
            title: 'Web Scrape - Dashboard',
            requiredAuth: true
        }
    },
    {
        path: "/:catchAll(.*)",
        name: "404",
        component: NotFound,
        meta: {
            title: 'Web Scrape - Not Found',
        }
    },

];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const title = to.meta.title

    const titleFromParams = to.params.pageTitle;

    if (title) {
        document.title = title
    }
    if (titleFromParams) {
        document.title = `${titleFromParams} - ${document.title}`;
    }
    next()
})

export default router;
