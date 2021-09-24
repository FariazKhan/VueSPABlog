<template>
    <Admin>
        <template #pageTitle>
            Create a page
        </template>

        <template #content>
            <form @submit.prevent="submitForm" enctype="multipart/form-data" class="w-full px-3 py-4">
                <div class="w-full h-auto flex flex-row border space-x-2 border-gray-300 rounded px-2 py-2">
                    <div class="w-6/12 mx-auto flex flex-col">
                        <label for="title">Add a title for your page, slug will be auto-generated from it: </label>
                        <input id="title" type="text" v-model="formData.title" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the title...">
                        <p class="text-red-500 text-center mt-3" v-if="errors.title">{{ errors.title }}</p>
                    </div>
                </div>
                <div class="w-full h-auto grid grid-cols-1 border mt-4 space-x-2 border-gray-300 rounded px-2 py-2">
                    <ckeditor id="body" :editor="editor" v-model="editorData" @ready="onEditorReady" :config="editorConfig"></ckeditor>
                </div>
                <div class="w-full mt-5 flex flex-row justify-center items-center">
                    <button class="bg-purple-400 text-white rounded border border-white shadow px-2 py-2" type="submit">Save post</button>
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
import { useForm } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

export default {
    data() {
        return {
            editor: ClassicEditor,
            editorData: '<p>Enter the page body here...</p>',
            editorConfig: {
            },

            formData: {
                title: null,
                body: null,
            }
        }
    },
    props: {
        errors: Object,
        page: Object
    },
    components: {
        Admin,
    },
    methods: {
        submitForm() {

            let data = new FormData()
            data.append('_method', 'PUT')
            data.append('title', this.formData.title)
            data.append('body', this.editorData)
            Inertia.post(route('page.update', this.formData.slug), data)
        },
        onEditorReady() {
            this.editorData = this.formData.body
        }
    },
    mounted() {
        this.formData = this.page
    }
}
</script>