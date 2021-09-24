<template>
    <Admin>
        <template #pageTitle>
            Blog settings
        </template>

        <template #content>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <div class="w-10/12 mx-auto flex flex-col px-4 py-4 space-y-3 justify-center items-center border border-gray-200 rounded-md">
                <form @submit.prevent="saveBlogSettings">
                    <div class="form flex flex-row w-full space-x-7 items-center mx-auto">
                        <label for="blogName">Set the name of your blog:</label>
                        <input class="w-9/12 rounded focus:outline-none border-gray-200" type="text" id="blogName" v-model="formData.blogName" placeholder="Enter the blog name here">
                    </div>
                    <p class="text-red-500 text-center mt-3" v-if="errors.blogName">{{ errors.blogName }}</p>
                    <div class="form flex flex-row my-4 w-full space-x-7 items-center mx-auto">
                        <label for="welcomeText">Set the welcome text:</label>
                        <input class="w-9/12 rounded focus:outline-none border-gray-200" type="text" id="welcomeText" v-model="formData.welcomeText" placeholder="Enter the welcome text here">
                    </div>
                    <p class="text-red-500 text-center mt-3" v-if="errors.welcomeText">{{ errors.welcomeText }}</p>
                    <div class="form flex flex-row my-4 w-full space-x-7 items-center mx-auto">
                        <label for="footerText">Set the footer text:</label>
                        <input class="w-9/12 rounded focus:outline-none border-gray-200" type="text" id="footerText" v-model="formData.footerText" placeholder="Enter the footer text here">
                    </div>
                    <p class="text-red-500 text-center mt-3" v-if="errors.footerText">{{ errors.footerText }}</p>
                    <div class="w-full flex justify-center items-center">
                        <button class="px-3 py-2 bg-blue-400 text-white border border-white rounded-md">Save info</button>
                    </div>
                </form>
            </div>
            <div class="my-4 w-10/12 mx-auto flex flex-col px-4 py-4 space-y-3 justify-center items-center border border-gray-200 rounded-md">
                <form @submit.prevent="saveBlogLogo">
                    <p>The logo currently being used:</p>
                    <img class="w-40 h-40 mx-auto my-3 rounded-md" :src="`/assets/images/blog/${data.blog_logo}`" alt="">
                    <div class="form flex flex-row w-full space-x-7 items-center mx-auto">
                        <label for="blogLogo">Upload a logo for your blog</label>
                        <input type="file" id="blogLogo" @input="blogLogo = $event.target.files[0]">
                    </div>
                    <p class="text-red-500 text-center mt-3" v-if="errors.blogLogo">{{ errors.blogLogo }}</p>
                    <div class="mt-4 w-full flex justify-center items-center">
                        <button class="px-3 py-2 bg-blue-400 text-white border border-white rounded-md">Save blog logo</button>
                    </div>
                </form>

                <form @submit.prevent="saveCoverImage">
                    <p class="mt-6">The cover currently image being used:</p>
                    <img class="w-100 h-auto mx-auto my-3 rounded-md" :src="`/assets/images/blog/${data.cover_image}`" alt="">
                    <div class="form flex flex-row my-4 w-full space-x-7 items-center mx-auto">
                        <label for="coverImage">Upload a cover image for your blog homepage</label>
                        <input type="file" id="coverImage" @input="coverImage = $event.target.files[0]">
                    </div>
                    <p class="text-red-500 text-center mt-3" v-if="errors.coverImage">{{ errors.coverImage }}</p>
                    <div class="mt-4 w-full flex justify-center items-center">
                        <button class="px-3 py-2 bg-blue-400 text-white border border-white rounded-md">Save cover image</button>
                    </div>
                </form>
            </div>
        </template>
    </Admin>
</template>

<script>
import Admin from '@/Layouts/Admin'
import { Link } from '@inertiajs/inertia-vue3'
import { Head } from '@inertiajs/inertia-vue3'
import FlashMessages from '@/Components/FlashMessages'
import { Inertia } from '@inertiajs/inertia'

export default {
    data() {
        return {
            formData: {
                blogName: '',
                welcomeText: '',
                footerText: ''
            },
            blogLogo: '',
            coverImage: '',
        }
    },
    components: {
        Admin,
        Link,
        Head,
        FlashMessages
    },
    props: {
        errors: Object,
        data: Object
    },
    methods: {
        saveBlogSettings() {
            let data = new FormData
            data.append('blogName', this.formData.blogName)
            data.append('welcomeText', this.formData.welcomeText)
            data.append('footerText', this.formData.footerText)
            Inertia.post(route('saveBlogSettings'), data)
        },
        saveBlogLogo() {
            let data = new FormData
            data.append('blogLogo', this.blogLogo)
            Inertia.post(route('saveBlogLogo'), data)
        },
        saveCoverImage() {
            let data = new FormData
            data.append('coverImage', this.coverImage)
            Inertia.post(route('saveBlogCoverImage'), data)
        },
    },
    mounted() {
        this.formData.blogName = this.data.blog_name
        this.formData.welcomeText = this.data.welcome_text
        this.formData.footerText = this.data.footer_text
        this.blogLogo = this.data.blog_logo
        this.coverImage = this.data.cover_image
    }
}
</script>