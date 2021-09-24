<template>
    <Admin>
        <template #pageTitle>
            Create an user
        </template>

        <template #content>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <form @submit.prevent="submitForm" class="w-full px-3 py-4">
                <div class="w-full h-auto flex flex-col justify-center items-center border space-x-2 border-gray-300 rounded px-2 py-2">
                    <div class="w-full md:w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="name">Add a name for the user: </label>
                        <input id="name" type="text" v-model="formData.name" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the name">
                        <p class="text-red-500 text-center mt-3" v-if="errors.name">{{ errors.name }}</p>
                    </div>
                    <div class="w-full md:w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="email">Add a email for your category, slug will be auto-generated from it: </label>
                        <input id="email" type="email" v-model="formData.email" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the email">
                        <p class="text-red-500 text-center mt-3" v-if="errors.email">{{ errors.email }}</p>
                    </div>
                    <div class="w-full md:w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="password">Enter the password of the user: </label>
                        <input id="password" type="password" v-model="formData.password" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the password">
                        <p class="text-red-500 text-center mt-3" v-if="errors.password">{{ errors.password }}</p>
                    </div>
                    <div class="w-full md:w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="confPassword">Confirm the password: </label>
                        <input id="confPassword" type="password" v-model="formData.confPassword" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Confirm the password">
                        <p class="text-red-500 text-center mt-3" v-if="errors.confPassword">{{ errors.confPassword }}</p>
                    </div>
                    <div class="w-full md:w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="role">Assign a role for the user: </label>
                        <select id="role" v-model="formData.role">
                            <option value="null" disabled>Select a role</option>
                            <option v-for="(role, index) in roles" :key="index" :value="role">{{ role }}</option>
                        </select>
                        <p class="text-red-500 text-center mt-3" v-if="errors.role">{{ errors.role }}</p>
                    </div>
                </div>
                <div class="w-full mt-5 flex flex-row justify-center items-center">
                    <button class="bg-purple-400 text-white rounded border border-white shadow px-2 py-2" type="submit">Save user</button>
                </div>
            </form>
        </template>
    </Admin>
</template>

<style scoped>
.ck-edito {
    min-width: 100%;
    width: 100%;
}
</style>

<script>
import Admin from '@/Layouts/Admin'
import { Inertia } from '@inertiajs/inertia'
import FlashMessages from '@/Components/FlashMessages'

export default {
    data() {
        return {
            formData: {
                name: null,
                email: null,
                password: null,
                confPassword: null,
                role: null
            }
        }
    },
    props: {
        errors: Object,
        roles: Object
    },
    components: {
        Admin,
        FlashMessages,
    },
    methods: {
        submitForm() {
            let data = new FormData()
            data.append('name', this.formData.name)
            data.append('email', this.formData.email)
            data.append('password', this.formData.password)
            data.append('confPassword', this.formData.confPassword)
            data.append('role', this.formData.role)
            Inertia.post(route('user.store'), data)
        },
    }
}
</script>