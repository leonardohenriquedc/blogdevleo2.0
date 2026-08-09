import { createRouter, createWebHistory } from 'vue-router';
import BlogView from '../views/BlogView.vue';
import HomeView from '../views/HomeView.vue';
import { setDocumentTitle } from '../services/seo.js';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView,
        },
        {
            // The article page. The URL matches the backend API endpoint
            // (/get/{name}), but the visit happens on the client side via the
            // router — no full page reload.
            path: '/get/:name',
            name: 'blog',
            component: BlogView,
            props: true,
        },
        {
            // Fallback: unknown slugs render the article reader anyway and
            // show the "not found" state when the backend returns a 404.
            path: '/:name',
            name: 'blog-fallback',
            component: BlogView,
            props: true,
        },
    ],
    scrollBehavior(_to, _from, savedPosition) {
        if (savedPosition) {
            return savedPosition;
        }

        return { top: 0 };
    },
});

router.afterEach((to) => {
    switch (to.name) {
        case 'home':
            setDocumentTitle('Desenvolvimento e Tecnologia');
            break;
        default:
            // The blog view sets a more precise title once the post loads.
            break;
    }
});

export default router;
