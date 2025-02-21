<template>
    <div>
        <nav class="fixed top-0 left-0 z-40 w-screen transition-transform bg-neutral-200 border-gray-200">
            <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-2">
                <router-link :to="authUser ? { name: 'users.show', params: { id: authUser.id } } : { name: 'users.login' }">
                    <h1 class="font-black text-3xl my-3">Blog</h1>
                </router-link>
                <div v-if="authUser" class="flex space-x-4">
                    <router-link  :to="{ name: 'users.show', params: { id: authUser.id } }" class="flex items-center md:order-2 md:space-x-0 rtl:space-x-reverse hover:bg-neutral-300 rounded-lg px-4 py-2">
                        <p v-if="authUser" class="mr-4 font-bold">{{ authUser.name }}</p>
                        <div type="button" class="relative w-10 h-10 overflow-hidden mr-3 flex text-sm bg-gray-200 rounded-full md:me-0 focus:ring-4 focus:ring-gray-300">
                            <span class="sr-only">Open user menu</span>
                            <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                        </div>
                    </router-link>
                </div>
                <div v-if="!authUser" class="flex space-x-4">
                    <router-link :to="{ name: 'users.login' }">Login</router-link>
                    <router-link :to="{ name: 'users.registration' }">Registration</router-link>
                </div>
            </div>
        </nav>

        <div class="max-w-7xl mx-auto">
            <aside v-if="authUser" id="default-sidebar" class="fixed top-20 left-1/5 z-40 w-44 xl:w-64 h-screen transition-transform -translate-x-full lg:translate-x-0" aria-label="Sidebar">
                <div class="h-full px-3 py-4 overflow-y-auto">
                    <ul class="space-y-2 font-medium text-lg">
                        <li>
                            <router-link :to="{ name: 'posts.all' }" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-neutral-200" :active-class="'bg-neutral-200'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-gray-500 transition duration-75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.362 5.214A8.252 8.252 0 0 1 12 21 8.25 8.25 0 0 1 6.038 7.047 8.287 8.287 0 0 0 9 9.601a8.983 8.983 0 0 1 3.361-6.867 8.21 8.21 0 0 0 3 2.48Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18a3.75 3.75 0 0 0 .495-7.468 5.99 5.99 0 0 0-1.925 3.547 5.975 5.975 0 0 1-2.133-1.001A3.75 3.75 0 0 0 12 18Z" />
                                </svg>
                                <span class="ms-3">Feed</span>
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'posts.followings_posts' }" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-neutral-200" :active-class="'bg-neutral-200'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-gray-500 transition duration-75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                                </svg>

                                <span class="ms-3">Followings</span>
                            </router-link>
                        </li>
                        <li>
                            <router-link :to="{ name: 'users.index' }" class="flex items-center p-2 text-gray-900 rounded-lg hover:bg-neutral-200" :active-class="'bg-neutral-200'">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7 text-gray-500 transition duration-75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                <span class="ms-3">Users</span>
                            </router-link>
                        </li>
                    </ul>
                </div>
            </aside>

            <div class="max-w-2xl pb-8 pt-24 mx-auto">
                <router-view @scroll="console.log('scroll')" :key="$route.fullPath"></router-view>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "App",

    data() {
        return {
            accessToken: null,
            authUser: null
        }
    },

    mounted() {
        this.getAccessToken()
    },

    watch: {
        $route(to, from) {
            if (to.name === 'users.show' && from.name === 'users.show') {
                console.log(to)
            }
            this.getAccessToken()
        }
    },

    methods: {
        getAccessToken() {
            this.accessToken = localStorage.getItem('x_xsrf_token')
            if (this.accessToken) {
                if (!this.authUser) {
                    this.getAuthorizedUser()
                }
            } else {
                this.authUser = null
            }
        },

        getAuthorizedUser() {
            axios.get('/api/users/authorized')
                .then(res => {
                    this.authUser = res.data.data
                    localStorage.setItem('id', this.authUser.id)
                })
        }
    }
}
</script>

<style scoped>

</style>
