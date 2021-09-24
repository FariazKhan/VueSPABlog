<template>

    <Head>
        <title>View Trashed Categories - The TechPost</title>
    </Head>

    <Admin>
        <template #pageTitle>
            Trahed Categories
        </template>

        <template #content>
            <div class="w-auto ml-auto space-x-3 flex justify-items-end px-4 py-3">
                <Link :href="route('category.create')" class="px-2 py-2 bg-green-400 rounded text-white">Add a category</Link>
                <Link :href="route('category.index')" class="px-2 py-2 bg-blue-300 rounded text-white">View all categories</Link>
            </div>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <div class="w-70 mx-auto text-center px-4 py-3 bg-yellow-100 border border-white rounded-lg">
                <p>If you trash a category, all posts under that category will be moved to Uncategorized category automatically.</p>
            </div>
            <div class="w-full h-auto px-3 py-2">
                <table class="table-auto w-full border border-gray-400 rounded">
                    <thead class="bg-yellow-100 mb-4 py-3">
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category, index) in categories.data" :key="index">
                            <td class="text-center">{{ index + 1 }}</td>
                            <td class="text-center">{{ category.name }}</td>
                            <td class="flex flex-row justify-center items-center space-x-2">
                                <button @click="restoreCategory(category.slug)" class="w-auto h-auto px-4 py-1 rounded-lg bg-green-500 opacity-60 text-white text-center text-sm"> <i class="fas fa-undo"></i> </button>
                                <button @click="removeCategory(category.slug)" class="w-auto h-auto px-4 py-1 rounded-lg bg-red-500 opacity-60 text-white text-center text-sm"> <i class="fas fa-trash-alt"></i> </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Pagination class="mt-5" :links="categories.links"/>
        </template>
    </Admin>
</template>

<script>
import Admin from '@/Layouts/Admin'
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '@/Components/Pagination'
import { Head } from '@inertiajs/inertia-vue3'
import FlashMessages from '@/Components/FlashMessages'
import { Inertia } from '@inertiajs/inertia'

export default {
    components: {
        Admin,
        Link,
        Pagination,
        Head,
        FlashMessages,
    },
    props: {
        categories: Object,
    },
    methods: {
        restoreCategory(slug) {
            Inertia.post(route('restoreCategory', slug))
        },
        removeCategory(slug) {
            if(confirm('Are you sure that you want to remove this category permanently?')) {
                Inertia.post(route('removeCategory', slug))
            }
        }
    }
}
</script>