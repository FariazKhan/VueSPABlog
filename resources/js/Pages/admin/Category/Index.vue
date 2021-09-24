<template>

    <Head>
        <title>View Categories - The TechPost</title>
    </Head>

    <Admin>
        <template #pageTitle>
            Categories
        </template>

        <template #content>
            <div class="w-auto ml-auto space-x-3 flex justify-items-end px-4 py-3">
                <Link :href="route('category.create')" class="px-2 py-2 bg-green-400 rounded text-white">Add a category</Link>
                <Link :href="route('trashedCategories')" class="px-2 py-2 bg-yellow-500 rounded text-white">View trashed categories</Link>
            </div>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <div class="w-full h-auto px-3 py-2">
                <table class="table-auto w-full border border-gray-400 rounded">
                    <thead class="bg-yellow-100 mb-4 py-3">
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>Posts under category</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(category, index) in categories.data" :key="index">
                            <td class="text-center">{{ index + 1 }}</td>
                            <td class="text-center">{{ category.name }}</td>
                            <td class="text-center">{{ postsCount[index] }}</td>
                            <td class="flex flex-row justify-center items-center space-x-2">
                                <Link :href="route('category.edit', category.slug)" class="px-3 py-2 rounded bg-yellow-500 text-white text-center"><i class="fas fa-pencil-alt"></i></Link>
                                <button @click="trashCategory(category.slug)" class="px-3 py-2 rounded bg-red-500 text-white text-center"><i class="fas fa-trash"></i></button>
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
        postsCount: Array
    },
    methods: {
        trashCategory(slug) {
            Inertia.delete(route('category.destroy', slug))
        }
    }
}
</script>