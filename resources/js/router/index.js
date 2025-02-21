import {createRouter, createWebHistory} from 'vue-router'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),

    routes: [
        {
            path: '/users/login',
            component: () => import('../views/User/Login.vue'),
            name: 'users.login'
        },
        {
            path: '/users/registration',
            component: () => import('../views/User/Registration.vue'),
            name: 'users.registration'
        },
        {
            path: '/users/all',
            component: () => import('../views/User/Index.vue'),
            name: 'users.index'
        },
        {
            path: '/users/:id',
            component: () => import('../views/User/Show.vue'),
            name: 'users.show'
        },
        {
            path: '/posts/all',
            component: () => import('../views/Post/Index.vue'),
            name: 'posts.all'
        },
        {
            path: '/posts/followings_posts',
            component: () => import('../views/Post/FollowingsPosts.vue'),
            name: 'posts.followings_posts'
        },
        {
            path: '/:catchAll(.*)',
            component: () => import('../views/User/Login.vue'),
            name: '404'
        }

    ]
})

router.beforeEach((to, from, next) => {
    const accessToken = localStorage.getItem('x_xsrf_token')

    if (!accessToken) {
        if (to.name === 'users.login' || to.name === 'users.registration') {
            return next()
        } else {
            return next({ name: 'users.login' })
        }
    }

    if ((to.name === 'users.login' || to.name === 'users.registration' || to.name === '404') && accessToken) {
        return next({ name: 'users.show', params: { id: localStorage.getItem('id') } })
    }

    next();
})

export default router
