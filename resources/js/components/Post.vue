<template>
    <div class="p-8 bg-white border border-gray-200 rounded-xl shadow-sm mb-4">
        <div class="flex items-center mb-1">
            <div class="relative w-10 h-10 overflow-hidden bg-gray-200 rounded-full mr-3">
                <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <div>
                    <router-link :to="{ name: 'users.show', params: { id: post.user.id } }" class="font-bold text-lg">{{ post.user.name }}</router-link>
                    <p class="text-sm font-light text-gray-400">{{ post.created_at }}</p>
                </div>
            </div>
        </div>
        <h5 class="text-2xl font-black tracking-tight text-gray-900 mb-1">{{ post.title }}</h5>
        <p class="text-lg">{{ post.content }}</p>

        <div v-if="post.image" class="mt-2">
            <img :src="post.image.url" alt="" class="rounded-xl max-h-64">
        </div>

        <div v-if="post.parent" class="p-8 bg-white border border-gray-200 rounded-xl shadow-sm mt-4">
            <div class="flex items-center mb-1">
                <div class="relative w-10 h-10 overflow-hidden bg-gray-200 rounded-full mr-3">
                    <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                <div>
                    <div>
                        <router-link :to="{ name: 'users.show', params: { id: post.parent.user.id } }" class="font-bold text-lg">{{ post.parent.user.name }}</router-link>
                        <p class="text-sm font-light text-gray-400">{{ post.parent.created_at }}</p>
                    </div>
                </div>
            </div>
            <h5 class="text-2xl font-black tracking-tight text-gray-900 mb-1">{{ post.parent.title }}</h5>
            <p class="text-lg">{{ post.parent.content }}</p>
            <div v-if="post.parent.image" class="mt-2">
                <img :src="post.parent.image.url" alt="" class="rounded-xl max-h-64">
            </div>
        </div>

        <div class="flex items-center space-x-4 mt-4">
            <div class="flex items-center">
                <a @click.prevent="toggleLike(post)" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="['size-6 hover:fill-red-500 hover:stroke-red-500', post.authorized_is_liked ? 'stroke-red-500 fill-red-500' : 'stroke-gray-500 fill-white']">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/>
                    </svg>
                </a>
                <p v-if="post.likes_count > 0" class="ml-1 text-gray-500">{{ post.likes_count }}</p>
            </div>
            <div class="flex items-center2">
                <a @click.prevent="toggleRepost" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-gray-500 hover:stroke-black">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.49 12 3.75-3.751m0 0-3.75-3.75m3.75 3.75H3.74V19.5"/>
                    </svg>
                </a>
                <p v-if="post.reposts_count > 0" class="ml-1 text-gray-500">{{ post.reposts_count }}</p>
            </div>
            <div class="flex items-center">
                <a @click.prevent="toggleComments" href="#">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-gray-500 hover:stroke-black">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 20.25c4.97 0 9-3.694 9-8.25s-4.03-8.25-9-8.25S3 7.444 3 12c0 2.104.859 4.023 2.273 5.48.432.447.74 1.04.586 1.641a4.483 4.483 0 0 1-.923 1.785A5.969 5.969 0 0 0 6 21c1.282 0 2.47-.402 3.445-1.087.81.22 1.668.337 2.555.337Z"/>
                    </svg>
                </a>
                <p v-if="post.comments_count > 0" class="ml-1 text-gray-500">{{ post.comments_count }}</p>
            </div>
        </div>

        <form v-if="repostIsOpened" class="mt-4 border-t border-gray-200">
            <h2 class="my-4 font-black text-lg">Repost</h2>
            <input v-model="title" type="text" id="base-input" placeholder="Title" required class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
            <textarea v-model="content" id="message" rows="4" class="block mt-2 p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Your answer..."></textarea>
            <div class="text-right">
                <button @click.prevent="storeRepost" type="submit" class="mt-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
            </div>
        </form>

        <form v-if="commentsIsOpened" class="mt-4 border-t border-gray-200">
            <h2 class="my-4 font-black text-lg">Comments</h2>
            <a v-if="hasNext" @click.prevent="getComments" href="#" class="block text-center text-blue-500">Load previous</a>
            <div v-if="comments">
                <ul class="divide-y divide-gray-200">
                    <li v-for="comment in comments" class="pb-3 sm:pb-4">
                        <div class="flex items-center space-x-2 rtl:space-x-reverse mt-4 mb-2">
                            <div class="relative w-10 h-10 overflow-hidden bg-gray-200 rounded-full shrink-0">
                                <svg class="absolute w-12 h-12 text-gray-400 -left-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                            </div>
                            <div>
                                <router-link :to="{ name: 'users.show', params: { id: comment.user.id } }" class="text-lg font-bold text-gray-900 truncate">{{ comment.user.name }}</router-link>
                                <p class="text-sm text-gray-500 truncate">{{ comment.created_at }}</p>
                            </div>
                        </div>
                        <p class="text-lg mb-2">
                            <span v-if="comment.parent">
                               <router-link :to="{ name: 'users.show', params: { id: comment.parent.user.id } }" class="text-blue-500">{{ comment.parent.user.name }}</router-link>,
                            </span>
                            {{ comment.text }}</p>
                        <a @click.prevent="parentComment = comment" href="#" class="text-blue-500">Answer</a>
                    </li>
                </ul>
            </div>

            <div>
                <div v-if="parentComment" class="flex justify-between">
                    <p class="text-gray-500">answer for <span class="font-bold">{{ parentComment.user.name }}</span></p>
                    <a @click.prevent="parentComment = null" href="#" class="text-gray-500">Cancel</a>
                </div>
                <textarea v-model="text" id="message" rows="1" class="block mt-2 p-2.5 w-full text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Your comment..."></textarea>
                <div class="text-right">
                    <button @click.prevent="storeComment" type="submit" class="mt-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-full sm:w-auto px-5 py-2.5 text-center">Submit</button>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "Post",

    data() {
        return {
            repostIsOpened: false,
            commentsIsOpened: false,
            comments: [],
            page: 1,
            hasNext: false,
            title: '',
            content: '',
            text: '',
            parentComment: null
        }
    },

    props: [
        'post'
    ],

    methods: {
        toggleLike(post) {
            axios.post(`/api/posts/${this.post.id}/toggle_like`)
                .then(res => {
                    console.log(res.data)
                    post.likes_count = res.data.likes_count
                    post.authorized_is_liked = res.data.is_liked
                })
        },

        storeRepost() {
            axios.post('/api/posts', {title: this.title, content: this.content, parent_id: this.post.id})
                .then(res => {
                    this.title = ''
                    this.content = ''
                    this.repostIsOpened = false

                    this.post.reposts_count++

                    if (this.$parent.$options.name === 'Show' && this.$parent.$data.isAuthorized) {
                        this.$parent.$data.posts.unshift(res.data.data)
                    }
                    if (this.$parent.$options.name === 'Post.Index') {
                        this.$parent.$data.posts.unshift(res.data.data)
                    }
                })
        },

        toggleRepost() {
            this.commentsIsOpened = false
            this.repostIsOpened = !this.repostIsOpened
        },

        toggleComments() {
            this.repostIsOpened = false
            this.commentsIsOpened = !this.commentsIsOpened

            if (this.commentsIsOpened) {
                this.getComments()
            }
        },

        getComments() {
            axios.get(`/api/posts/${this.post.id}/comments?page=${this.page}`)
                .then(res => {
                    this.hasNext = res.data.has_next
                    this.comments.unshift(...res.data.comments.slice().reverse())
                    this.page++
                })
        },

        storeComment() {
            const parent_id = this.parentComment ? this.parentComment.id : null
            axios.post(`/api/posts/${this.post.id}/comments`, { text: this.text, parent_id: parent_id})
                .then(res => {
                    this.comments.push(res.data.data)
                    this.post.comments_count++
                    this.text = ''
                    this.parentComment = null
                })
        }
    }
}
</script>

<style scoped>

</style>
