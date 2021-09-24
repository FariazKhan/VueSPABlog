<template>

    <Head>
        <title>View Posts - The TechPost</title>
    </Head>

    <Admin>
        <template #pageTitle>
            Posts
        </template>

        <template #content>
            <div class="container w-full mx-auto my-5 px-2 py-2">
                <div class="w-6/12 mx-auto">
                    <FlashMessages />
                </div>
                <div class="w-full flex flex-row justify-center items-start flex-wrap space-x-3">
                    <!-- Left Side -->
                    <div class="w-full md:w-3/12">
                        <!-- Profile Card -->
                        <div class="bg-white p-3 border-t-4 border-green-400">
                            <div class="image overflow-hidden">
                                <img class="h-52 w-52 mx-auto rounded-full" :src="`/assets/images/user/${this.user.image}`" alt="The profile image"/>
                            </div>
                            <h1 class="text-gray-900 text-center font-bold text-xl leading-8 my-1">
                                {{ name }}
                            </h1>
                            <h3 class="text-gray-600 text-center font-lg text-semibold leading-6">
                                {{ roleName }}
                            </h3>
                            <p class=" text-sm text-center text-gray-500 hover:text-gray-600 leading-6">
                                {{ aboutMe }}
                            </p>
                            <ul class="bg-gray-100 text-gray-600 hover:text-gray-700 hover:shadow py-2 px-3 mt-3 divide-y rounded shadow-sm">
                                <li class="flex items-center py-3">
                                    <span>User since</span>
                                    <span class="ml-auto">{{ userSince }}</span>
                                </li>
                            </ul>
                        </div>
                        <!-- End of profile card -->
                        <div class="my-4"></div>
                    </div>
                    <!-- Right Side -->
                    <div class="w-full md:w-8/12 h-auto py-3">
                        <div class="bg-white p-3 shadow-sm rounded-sm">
                            <div class="w-full flex flex-col justify-center items-start px-2 py-2 rounded border border-gray-300">
                                <form @submit.prevent="saveProfileInfo">
                                <div class="flex flex-col w-full">
                                    <div class="flex flex-row w-full">
                                        <label for="name" class="px-3 py-2 flex justify-center items-center font-semibold">
                                            Name
                                        </label>
                                        <div class="px-3 py-2 w-full">
                                            <input id="name" type="text" v-model="formData.name" class="w-100 rounded shadow focus:outline-none border border-gray-300" placeholder="Enter your name here">
                                            <p class="text-red-500 text-center mt-3" v-if="errors.name">{{ errors.name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-row w-full">
                                        <label for="email" class="px-3 py-2 flex justify-center items-center font-semibold">
                                            Email
                                        </label>
                                        <div class="px-3 py-2 w-full">
                                            <input id="email" type="text" v-model="formData.email" class="w-100 rounded shadow focus:outline-none border border-gray-300" placeholder="Enter your email address here">
                                            <p class="text-red-500 text-center mt-3" v-if="errors.email">{{ errors.email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-row w-full">
                                        <label for="about" class="px-3 py-2 flex justify-center items-center font-semibold">
                                            About you
                                        </label>
                                        <div class="px-3 py-2 w-full">
                                            <input id="about" type="text" v-model="formData.about" class="w-100 rounded shadow focus:outline-none border border-gray-300" placeholder="Write about you, in 256 words">
                                            <p class="text-red-500 text-center mt-3" v-if="errors.about">{{ errors.about }}</p>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="w-auto mt-4 px-2 py-2 border border-white rounded shadow bg-green-500 text-white text-center">Save info</button>
                                </form>
                            </div>
                            <div class="w-full mt-5 flex flex-col justify-center items-start px-2 py-2 rounded border border-gray-300">
                                <form @submit.prevent="saveProfilePassword">
                                <div class="flex flex-row flex-wrap w-full">
                                    <div class="flex w-full">
                                        <label for="password" class="px-3 py-2 flex justify-center items-center font-semibold">
                                            Current password
                                        </label>
                                        <div class="px-3 py-2 w-full">
                                            <input type="password" v-model="formData.password" class="w-full rounded shadow focus:outline-none border border-gray-300" placeholder="Enter your current password">
                                            <p class="text-red-500 text-center mt-3" v-if="errors.password">{{ errors.password }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-row w-full">
                                        <label for="newPassword" class="px-3 py-2 flex justify-center items-center font-semibold">
                                            New password
                                        </label>
                                        <div class="px-3 py-2 w-full">
                                            <input type="password" v-model="formData.newPassword" class="w-full rounded shadow focus:outline-none border border-gray-300" placeholder="Enter a new password">
                                            <p class="text-red-500 text-center mt-3" v-if="errors.newPassword">{{ errors.newPassword }}</p>
                                        </div>
                                    </div>
                                    <div class="flex flex-row w-full">
                                        <label for="confirmPassword" class="px-3 py-2 flex justify-center items-center font-semibold">
                                            Confirm new password
                                        </label>
                                        <div class="px-3 py-2 w-full">
                                            <input type="password" v-model="formData.confirmPassword" class="w-full rounded shadow focus:outline-none border border-gray-300" placeholder="Confirm the new password">
                                            <p class="text-red-500 text-center mt-3" v-if="errors.confirmPassword">{{ errors.confirmPassword }}</p>
                                        </div>
                                    </div>
                                </div>
                                <button class="w-auto mt-4 px-2 py-2 border border-white rounded shadow bg-green-500 text-white text-center">Save info</button>
                                </form>
                            </div>
                            <div class="w-full mt-5 flex flex-col justify-center items-start px-2 py-2 rounded border border-gray-300">
                                <form class="px-2 py-2" @submit.prevent="saveProfileImage">
                                    <label class="mx-4" for="profileImage">Upload a new profile image</label>
                                    <input type="file" id="profileImage" @input="profileImage = $event.target.files[0]">
                                    <p class="text-red-500 block text-center mt-3" v-if="errors.profileImage">{{ errors.profileImage }}</p>
                                    <button class="w-auto mt-4 px-2 py-2 border border-white rounded shadow bg-green-500 text-white text-center">Save info</button>

                                </form>
                            </div>
                        </div>
                        <!-- End of about section -->
                    </div>
                </div>
            </div>
        </template>
    </Admin>
</template>

<style scoped>
    :root {
        --main-color: #4a76a8;
    }

    .bg-main-color {
        background-color: var(--main-color);
    }

    .text-main-color {
        color: var(--main-color);
    }

    .border-main-color {
        border-color: var(--main-color);
    }
</style>

<script>
import Admin from '@/Layouts/Admin'
import { Link } from '@inertiajs/inertia-vue3'
import { Head } from '@inertiajs/inertia-vue3'
import FlashMessages from '@/Components/FlashMessages'
import { Inertia } from '@inertiajs/inertia'
import Label from '@/Components/Label.vue'

export default {
    data() {
        return {
            formData: {
                name: null,
                email: null,
                about: null,
                password: null,
                newPassword: null,
                confirmPassword: null,
            },
            profileImage: null,
            name: null,
            aboutMe: null,
        }
    },
    components: {
        Admin,
        Link,
        Head,
        FlashMessages,
        Label,
    },
    props: {
        user: Object,
        userSince: String,
        errors: Object,
        roleName: String
    },
    methods: {
        saveProfileInfo() {
            const data = new FormData
            data.append('name', this.formData.name)
            data.append('email', this.formData.email)
            data.append('about', this.formData.about)
            Inertia.post(route('saveProfileInfo'), data)
        },
        saveProfilePassword() {
            const data = new FormData
            data.append('password', this.formData.password)
            data.append('newPassword', this.formData.newPassword)
            data.append('confirmPassword', this.formData.confirmPassword)
            Inertia.post(route('saveProfilePassword'), data)
        },
        saveProfileImage() {
            const data = new FormData
            data.append('profileImage', this.profileImage)
            Inertia.post(route('saveProfileImage'), data)
        }
    },
    mounted() {
        this.formData = this.user
        this.name = this.user.name
        this.aboutMe = this.user.about
        this.profileImage = this.user.image
    }
}
</script>