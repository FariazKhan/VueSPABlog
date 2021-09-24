<template>
    <Admin>
        <template #pageTitle>
            Create a post
        </template>

        <template #content>
            <form @submit.prevent="submitForm" enctype="multipart/form-data" class="w-full px-3 py-4">
                <div class="w-full h-auto flex flex-row flex-wrap border space-x-2 border-gray-300 rounded px-2 py-2">
                    <div class="md:w-6/12 my-3 flex flex-col">
                        <label for="title">Add a title for your post, slug will be auto-generated from it: </label>
                        <input id="title" type="text" v-model="formData.title" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the title...">
                        <p class="text-red-500 text-center mt-3" v-if="errors.title">{{ errors.title }}</p>
                    </div>
                    <div class="md:w-6/12 flex flex-col">
                        <label for="short_description">Add a short description for your post:</label>
                        <input id="short_description" v-model="formData.short_description" type="text" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the short description...">
                        <p class="text-red-500 text-center mt-3" v-if="errors.short_description">{{ errors.short_description }}</p>
                    </div>
                </div>

                <div class="mx-auto my-5 w-7/12">
                    <p>Previously selected featured image:</p>
                    <img class="w-full h-auto object-cover rounded shadow" :src="`/assets/images/post/${post.featured_image}`" alt="">
                </div>

                <div class="w-full h-auto flex flex-row flex-wrap justify-center border mt-4 space-x-2 border-gray-300 rounded px-2 py-4">
                    <div class="md:w-6/12 my-3 flex flex-col">
                        <label for="postTitle">Add a fetaued image for your post:</label>
                        <input id="featured_image" type="file" placeholder="Select a featured image..." @input="formData.featured_image = $event.target.files[0]">
                        <progress v-if="formData.progress" :value="formData.progress.percentage" max="100">{{ formData.progress.percentage }}%</progress>
                        <p class="text-red-500 block text-center mt-3" v-if="errors.featured_image">{{ errors.featured_image }}</p>
                    </div>
                    <div class="md:w-6/12 flex flex-col">
                        <label for="postCategory">Select the category for the post:</label>
                        <select v-model="formData.postCategory" id="postCategory">
                            <option value="null" disabled>Select a category</option>
                            <option v-for="(category, index) in categories" :key="index" :value="category.slug">{{ category.name }}</option>
                        </select>
                        <p class="text-red-500 text-center mt-3" v-if="errors.postCategory">{{ errors.postCategory }}</p>
                    </div>
                </div>
                <div class="w-full h-auto grid grid-cols-1 border mt-4 space-x-2 border-gray-300 rounded px-2 py-2">
                    <ckeditor id="body" :editor="editor" v-model="editorData" @ready="onEditorReady" @input="setBodyValue" :config="editorConfig"></ckeditor>
                </div>
                <div class="mb-14 sm:mb-5 w-full mt-5 flex flex-row justify-center items-center">
                    <button class="bg-purple-400 text-white rounded border border-white shadow px-2 py-2" type="submit">Update post</button>
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
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'
import { Inertia } from '@inertiajs/inertia'

export default {
    data() {
        return {
            editor: ClassicEditor,
            editorData: '<p>Enter the post body here...</p>',
            editorConfig: {
            },

            formData: {
                _method: 'put',
                title: null,
                short_description: null,
                featured_image: null,
                postCategory: null,
                body: null,
            }
        }
    },
    props: {
        errors: Object,
        post: Object,
        categories: Object,
        currentCategory: String
    },
    components: {
        Admin,
    },
    methods: {
        setBodyValue(value) {
            this.editorData = value
        },
        submitForm() {
            let existingObj = this;

            const config = {
                headers: {
                    'content-type': 'multipart/form-data'
                }
            }

            let data = new FormData()
            data.append('_method', 'put')
            data.append('title', this.formData.title)
            data.append('short_description', this.formData.short_description)
            data.append('featured_image', this.formData.featured_image)
            data.append('postCategory', this.formData.postCategory)
            data.append('body', this.editorData)
            Inertia.post(route('post.update', this.post.slug), data)
        },
        onEditorReady() {
            this.setBodyValue(this.post.body)
        }
    },
    mounted() {
        this.formData = {...this.post}
        this.formData.postCategory = this.currentCategory
    }
}
</script>