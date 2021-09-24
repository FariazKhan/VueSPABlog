<template>
    <Admin>
        <template #pageTitle>
            Create an user
        </template>

        <template #content>
            <div class="w-full md:w-7/12 mx-auto py-4">
                <FlashMessages />
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
                        <label for="role">Assign a role for the user: </label>
                        <select id="role" v-model="formData.role">
                            <option value="null" disabled>Select a role</option>
                            <option v-for="(role, index) in roles" :key="index" :value="role">{{ role }}</option>
                        </select>
                        <p class="text-red-500 text-center mt-3" v-if="errors.role">{{ errors.role }}</p>
                    </div>
                </div>
                <div class="w-full mt-5 flex flex-row justify-center items-center">
                    <button class="bg-purple-400 text-white rounded border border-white shadow px-2 py-2" type="submit">Update user</button>
                </div>
            </form>
            <form @submit.prevent="submitPasswordForm" class="w-full px-3 py-4">
                <div class="w-full h-auto flex flex-col justify-center items-center border space-x-2 border-gray-300 rounded px-2 py-2">
                <p class="text-center">Update the password of the user, if needed:</p>
                    <div class="w-full md:w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="password">Enter the password of the user: </label>
                        <input id="password" type="password" v-model="passwordFormData.password" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the password">
                        <p class="text-red-500 text-center mt-3" v-if="errors.password">{{ errors.password }}</p>
                    </div>
                    <div class="w-full md:w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="confPassword">Confirm the password: </label>
                        <input id="confPassword" type="password" v-model="passwordFormData.confPassword" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Confirm the password">
                        <p class="text-red-500 text-center mt-3" v-if="errors.confPassword">{{ errors.confPassword }}</p>
                    </div>
                    <div class="w-full md:w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="adminPassword">Enter your password: </label>
                        <input id="adminPassword" type="password" v-model="passwordFormData.adminPassword" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter your password">
                        <p class="text-red-500 text-center mt-3" v-if="errors.adminPassword">{{ errors.adminPassword }}</p>
                    </div>
                </div>
                <div class="w-full mt-5 flex flex-row justify-center items-center">
                    <button class="bg-purple-400 text-white rounded border border-white shadow px-2 py-2" type="submit">Update password</button>
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
import FlashMessages from '@/Components/FlashMessages.vue'

export default {
    data() {
        return {
            formData: {
                name: null,
                email: null,
                role: null
            },
            passwordFormData: {
                password: null,
                confPassword: null,
                adminPassword: null
            }
        }
    },
    props: {
        errors: Object,
        user: Object,
        roles: Object,
        role: Object,
    },
    components: {
        Admin,
        FlashMessages
    },
    methods: {
        submitForm() {
            let data = new FormData()
            data.append('_method', 'PUT')
            data.append('name', this.formData.name)
            data.append('email', this.formData.email)
            data.append('password', this.formData.password)
            data.append('confPassword', this.formData.confPassword)
            data.append('role', this.formData.role)
            Inertia.post(route('user.update', this.user.name), data)
        },
        submitPasswordForm() {
            let data = new FormData()
            data.append('password', this.passwordFormData.password)
            data.append('confPassword', this.passwordFormData.confPassword)
            data.append('adminPassword', this.passwordFormData.adminPassword)
            Inertia.post(route('updateUserPassword', this.user.name), data)
        },
    },
    mounted() {
        this.formData.name = this.user.name
        this.formData.email = this.user.email
        this.formData.role = this.role[0]
    }
}
</script>