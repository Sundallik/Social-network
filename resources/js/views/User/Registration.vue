<template>
    <div class="p-16 bg-white border border-gray-200 rounded-xl shadow-sm mb-4 max-w-lg mx-auto mt-32">
        <h1 class="text-center font-black text-2xl mb-8">Sign up</h1>
        <form>
            <div class="mb-5">
                <label for="name" class="block mb-2 font-medium text-gray-900">Name</label>
                <input v-model="name" type="text" id="name" placeholder="Your name" required class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/>
                <div v-if="errors.name">
                    <p v-for="error in errors.name" class="text-red-500">{{ error }}</p>
                </div>
            </div>
            <div class="mb-5">
                <label for="email" class="block mb-2 font-medium text-gray-900">E-mail</label>
                <input v-model="email" type="email" id="email" placeholder="Your e-mail" required class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/>
                <div v-if="errors.email">
                    <p v-for="error in errors.email" class="text-red-500">{{ error }}</p>
                </div>
            </div>
            <div class="mb-5">
                <label for="password" class="block mb-2 font-medium text-gray-900">Password</label>
                <input v-model="password" type="password" id="password" placeholder="Your password" required class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/>
                <div v-if="errors.password">
                    <p v-for="error in errors.password" class="text-red-500">{{ error }}</p>
                </div>
            </div>
            <div class="mb-5">
                <label for="password_confirmation" class="block mb-2 font-medium text-gray-900">Confirm password</label>
                <input v-model="password_confirmation" type="password" id="password_confirmation" placeholder="Your password" required class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5"/>
                <div v-if="errors.password_confirmation">
                    <p v-for="error in errors.password_confirmation" class="text-red-500">{{ error }}</p>
                </div>
            </div>
            <button @click.prevent="register" type="submit" class="text-white mx-auto bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg w-full sm:w-auto px-5 py-2.5 text-center">Register</button>
        </form>
    </div>
</template>

<script>
export default {
    name: "Register",

    data() {
        return {
            name: '',
            email: '',
            password: '',
            password_confirmation: '',
            errors: []
        }
    },

    methods: {
        register() {
            axios.get('/sanctum/csrf-cookie').then(res => {
                axios.post('/register', {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation
                })
                .then(res => {
                    localStorage.setItem('x_xsrf_token', 'authorized')
                    this.$router.push({ name: 'posts.all' })
                })
                .catch(err => {
                    this.password = ''
                    this.password_confirmation = ''
                    this.errors = err.response.data.errors
                })
            })
        }
    }
}
</script>

<style scoped>

</style>
