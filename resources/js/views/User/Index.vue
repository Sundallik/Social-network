<template>
    <div v-if="users" class="px-8 py-4 bg-white border border-gray-200 rounded-xl shadow-sm">
        <ul class="divide-y divide-gray-200">
            <li v-for="user in users" class="py-4">
                <div class="flex items-center space-x-4 rtl:space-x-reverse">
                    <div class="relative w-12 h-12 overflow-hidden bg-gray-200 rounded-full shrink-0">
                        <svg class="absolute w-14 h-14 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <router-link :to="{ name: 'users.show', params: { id: user.id } }" class="text-lg font-bold text-gray-900 truncate">{{ user.name }}</router-link>
                        <p class="text-gray-500 truncate">{{ user.email }}</p>
                    </div>
                    <button v-if="!user.authorized_is_follower" @click.prevent="toggleFollow(user)" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2 text-center">Follow</button>
                    <button v-if="user.authorized_is_follower" @click.prevent="toggleFollow(user)" type="button" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center">Unfollow</button>
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "User.Index",

    data() {
        return {
            users: [],
            page: 1,
            hasNext: false,
            isLoading: false
        }
    },

    mounted() {
        this.getUsers()
        window.addEventListener('scroll', this.onScroll);
    },

    unmounted () {
        window.removeEventListener('scroll', this.onScroll);
    },

    methods: {
        getUsers() {
            this.isLoading = true
            axios.get(`/api/users?page=${this.page}`)
                .then(res => {
                    this.hasNext = res.data.has_next
                    this.users.push(...res.data.users)
                    this.page++
                    this.isLoading = false
                })
        },

        onScroll() {
            if (this.hasNext) {
                if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 300) {
                    if (!this.isLoading) {
                        this.getPosts()
                    }
                }
            }
        },

        toggleFollow(user) {
            axios.post('/api/users/toggle_following', { following_id: user.id })
                .then(res => {
                    user.authorized_is_follower = res.data.is_following
                })
        }
    }
}
</script>

<style scoped>

</style>
