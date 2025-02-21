<template>
    <div v-if="posts">
        <div v-for="post in posts">
            <post :post="post"></post>
        </div>
    </div>
</template>

<script>
import Post from "../../components/Post.vue";

export default {
    name: "Post.FollowingsPosts",

    components: {
        Post
    },

    data() {
        return {
            posts: [],
            page: 1,
            hasNext: false,
            isLoading: false
        }
    },

    mounted() {
        this.getFollowingsPosts()
        window.addEventListener('scroll', this.onScroll);
    },

    unmounted () {
        window.removeEventListener('scroll', this.onScroll);
    },

    methods: {
        getFollowingsPosts() {
            this.isLoading = true
            axios.get(`/api/posts/followings_posts?page=${this.page}`)
                .then(res => {
                    this.hasNext = res.data.has_next
                    this.posts.push(...res.data.posts)
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
        }
    }
}
</script>

<style scoped>

</style>
