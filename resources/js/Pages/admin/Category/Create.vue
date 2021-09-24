<template>
    <Admin>
        <template #pageTitle>
            Create a category
        </template>

        <template #content>
            <form @submit.prevent="submitForm" class="w-full px-3 py-4">
                <div class="w-full h-auto flex flex-row border space-x-2 border-gray-300 rounded px-2 py-2">
                    <div class="w-auto mx-auto px-4 py-4 flex flex-col">
                        <label for="name">Add a name for your category, slug will be auto-generated from it: </label>
                        <input id="name" type="text" v-model="formData.name" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the name...">
                        <p class="text-red-500 text-center mt-3" v-if="errors.name">{{ errors.name }}</p>
                    </div>
                </div>
                <div class="w-full mt-5 flex flex-row justify-center items-center">
                    <button class="bg-purple-400 text-white rounded border border-white shadow px-2 py-2" type="submit">Save category</button>
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

export default {
    data() {
        return {
            formData: {
                name: null,
            }
        }
    },
    props: {
        errors: Object
    },
    components: {
        Admin,
    },
    methods: {
        submitForm() {
            let data = new FormData()
            data.append('name', this.formData.name)
            Inertia.post(route('category.store'), data)
        },
    }
}
</script>