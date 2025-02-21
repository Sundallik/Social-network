<template>
    <div v-if="user">
        <div class="p-8 bg-white border border-gray-200 rounded-xl shadow-sm">
            <div class="pb-8 flex items-center justify-between border-b-2">
                <div class="flex">
                    <div class="relative w-14 h-14 overflow-hidden bg-gray-200 rounded-full mr-3">
                        <svg class="absolute w-16 h-16 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div>
                        <div>
                            <span class="font-black text-3xl">{{ user.name }}</span>
                            <span class="font-light text-gray-500 ml-2">since {{ user.created_at }}</span>
                        </div>
                        <div class="font-light text-gray-500">{{ user.email }}</div>
                    </div>
                </div>

                <div v-if="isAuthorized">
                    <button @click.prevent="logout" type="submit" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-1 text-center me-2 mb-2">Logout</button>
                </div>
                <div v-if="!isAuthorized">
                    <button v-if="!user.authorized_is_follower" @click.prevent="toggleFollow(user)" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2 text-center">Follow</button>
                    <button v-if="user.authorized_is_follower" @click.prevent="toggleFollow(user)" type="button" class="text-blue-700 hover:text-white border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2 text-center">Unfollow</button>
                </div>
            </div>

            <div class="flex justify-around mt-8 text-lg">
                <div class="text-center">
                    <p>Followers</p>
                    <p>{{ user.followers_count }}</p>
                </div>
                <div class="text-center">
                    <p>Subscribes</p>
                    <p>{{ user.subscribes_count }}</p>
                </div>
                <div class="text-center">
                    <p>Likes</p>
                    <p>{{ user.likes_count }}</p>
                </div>
                <div class="text-center">
                    <p>Posts</p>
                    <p>{{ user.posts_count }}</p>
                </div>
            </div>
        </div>

        <form v-if="isAuthorized" class="p-8 bg-white border border-gray-200 rounded-xl shadow-sm mt-4">
            <h2 class="mb-4 text-lg font-bold">New post</h2>
            <input v-model="title" type="text" id="base-input" placeholder="Title" required class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <textarea v-model="content"  id="message" rows="4" class="block p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 mt-4" placeholder="Post text..."></textarea>
            <div class="flex items-center mt-4">
                <input @change="storeImage" ref="file" type="file" class="hidden">
                <button @click.prevent="selectFile" type="button" class="focus:outline-none text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg px-5 py-2">Add image</button>
                <button v-if="image" @click.prevent="clearFileInput" type="button" class="text-red-700 hover:text-white border border-red-700 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg px-5 py-2 text-center ml-2">Cancel</button>
            </div>
            <div v-if="image" class="mt-4">
                <img :src="image.url" alt="" class="rounded-xl h-48">
            </div>
            <div class="mt-4">
                <button @click.prevent="storePost" type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-auto px-5 py-2.5 text-center">Submit</button>
            </div>
        </form>

        <div v-if="posts" v-for="post in posts" :key="post.id" class="mt-4">
            <post :post="post"></post>
        </div>
    </div>
</template>

<script>
import Post from "../../components/Post.vue";

export default {
    name: "Show",

    components: {
        Post
    },

    data() {
        return {
            userId: this.$route.params.id,
            user: {},
            isAuthorized: false,

            posts: [],
            page: 1,
            hasNext: false,
            isLoading: false,

            title: '',
            content: '',
            image: null
        }
    },

    mounted() {
        this.checkId()
        this.getUser()
        this.getPosts()
        window.addEventListener('scroll', this.onScroll);
    },

    unmounted () {
        window.removeEventListener('scroll', this.onScroll);
    },

    methods: {
        checkId() {
            console.log(this.userId)
            this.isAuthorized = this.userId === localStorage.getItem('id')
        },

        getUser() {
            axios.get(`/api/users/${this.userId}`)
                .then(res => {
                    this.user = res.data.data
                })
        },

        getPosts() {
            this.isLoading = true
            axios.get(`/api/users/${this.userId}/posts?page=${this.page}`)
                .then(res => {
                    this.hasNext = res.data.has_next
                    this.posts.push(...res.data.posts)
                    this.page++
                    this.isLoading = false
                })
        },

        storePost() {
            const imageId = this.image ? this.image.id : null
            axios.post('/api/posts', { title: this.title, content: this.content, image_id: imageId })
                .then(res => {
                    this.title = ''
                    this.content = ''
                    this.image = null
                    this.posts.unshift(res.data.data)
                })
        },

        selectFile() {
            this.fileInput = this.$refs.file;
            this.fileInput.click();
        },

        storeImage(e) {
            const file = e.target.files[0]
            const formData = new FormData()
            formData.append('image', file)

            axios.post('/api/post_image', formData)
                .then(res => {
                    this.image = res.data.data
                })
        },

        clearFileInput() {
            axios.delete('/api/post_image')
                .then(res => {
                    this.image = null
                    this.fileInput = this.$refs.file
                    this.fileInput.value = null
                })
        },

        logout() {
            axios.post('/logout')
                .then(res => {
                    localStorage.removeItem('x_xsrf_token')
                    localStorage.removeItem('id')
                    this.$router.push({ name: 'users.login' })
                })
        },

        toggleFollow(user) {
            axios.post('/api/users/toggle_following', { following_id: user.id })
                .then(res => {
                    user.authorized_is_follower = res.data.is_following
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
        }
    }
}
</script>

<style scoped>

</style>
