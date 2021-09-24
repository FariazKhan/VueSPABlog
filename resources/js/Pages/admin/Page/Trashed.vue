<template>

    <Head>
        <title>View Pages - The TechPost</title>
    </Head>

    <Admin>
        <template #pageTitle>
            Pages
        </template>

        <template #content>
            <div class="w-auto ml-auto space-x-3 flex justify-items-end px-4 py-3">
                <Link :href="route('page.create')" class="px-2 py-2 bg-green-400 rounded text-white">Add a page</Link>
                <Link :href="route('trashedPages')" class="px-2 py-2 bg-yellow-500 rounded text-white">View trashed pages</Link>
            </div>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <div class="grid grid-cols-4 w-full h-auto px-3 py-2 gap-4">
                <div v-for="page in pages.data" :key="page.id" class="item flex flex-col bg-gray-300 border border-gray-300 rounded-lg px-3 py-2 justify-center items-center">
                    <h1>{{ page.title }}</h1>
                    <div class="flex flex-row flex-wrap justify-center items-stretch w-full px-3 py-2 space-x-3">
                        <button @click="restorePage(page.slug)" class="w-auto h-auto px-4 py-1 rounded-lg bg-yellow-500 opacity-60 text-white text-center text-sm"> <i class="fas fa-undo"></i> </button>
                        <button @click="removePage(page.slug)" class="w-auto h-auto px-4 py-1 rounded-lg bg-red-500 opacity-60 text-white text-center text-sm"> <i class="fas fa-trash-alt"></i> </button>
                    </div>
                </div>
            </div>
            <Pagination class="mt-5" :links="pages.links"/>
        </template>
    </Admin>
</template>

<script>
import Admin from '@/Layouts/Admin'
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '@/Components/Pagination'
import { Head } from '@inertiajs/inertia-vue3'
import FlashMessages from '@/Components/FlashMessages'
import Button from '@/Components/Button.vue'
import { Inertia } from '@inertiajs/inertia'

export default {
    components: {
        Admin,
        Link,
        Pagination,
        Head,
        FlashMessages,
        Button
    },
    props: {
        pages: Object
    },
    methods: {
        restorePage(slug) {
            Inertia.post(route('restorePage', slug))
        },
        removePage(slug) {
            if(confirm('Do you really want to remove this page permanently?')) {
                Inertia.post(route('removePage', slug))
            }
        }
    }
}
</script>