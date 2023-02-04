<script setup>
import {onMounted, reactive} from 'vue'
import axios from "axios";
import router from "../router";

const form = reactive({
    name: '',
    email: '',
    password: ''
})

let errorBag = reactive({});
onMounted(() => {
    if (authUser) return router.push('/dashboard')
})
const submit = () => {
    axios.post('/api/register', form).then(response => {
        router.push('login')
    }).catch(error => {
        errorBag.value = error.response.data.errors
    })
}

</script>

<template>
    <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Register a new account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                {{ ' ' }}
                <router-link to="/login" class="font-medium text-indigo-600 hover:text-indigo-500">Login</router-link>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" @submit.prevent="submit">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <div class="mt-1">
                            <input id="name" name="name" v-model="form.name" type="text" autocomplete="name" :class="errorBag.value?.name ? 'border-red-300' : 'border-gray-300'" class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" />
                        </div>
                        <p class="mt-2 text-sm text-red-600" id="email-error" v-if="errorBag.value?.name">{{errorBag.value.name[0]}}</p>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" v-model="form.email" autocomplete="email" :class="errorBag.value?.email ? 'border-red-300' : 'border-gray-300'" class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" />
                            <p class="mt-2 text-sm text-red-600" id="email-error" v-if="errorBag.value?.email">{{errorBag.value.email[0]}}</p>
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" v-model="form.password" autocomplete="current-password" :class="errorBag.value?.email ? 'border-red-300' : 'border-gray-300'" class="block w-full appearance-none rounded-md border px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm" />
                            <p class="mt-2 text-sm text-red-600" id="email-error" v-if="errorBag.value?.password">{{errorBag.value.password[0]}}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <label for="remember-me" class="ml-2 block text-sm text-gray-900">Remember me</label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-indigo-600 hover:text-indigo-500">Forgot your password?</a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="flex w-full justify-center rounded-md border border-transparent bg-indigo-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Sign in</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
