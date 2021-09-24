<template>

    <Head>
        <title>View Posts - The TechPost</title>
    </Head>

    <Admin>
        <template #pageTitle>
            Posts
        </template>

        <template #content>
            <div class="w-auto ml-auto space-x-3 flex justify-items-end px-4 py-3">
                <Link :href="route('post.create')" class="px-2 py-2 bg-green-400 rounded text-white">Add a post</Link>
                <Link :href="route('trashedPosts')" class="px-2 py-2 bg-yellow-500 rounded text-white">View trashed posts</Link>
            </div>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 w-full h-auto px-3 py-2 gap-4">
                <div v-for="post in posts.data" :key="post.id" class="item flex flex-col bg-gray-300 border border-gray-300 rounded-lg px-3 py-2 justify-center items-center">
                    <img :src="'assets/images/post/' + post.featured_image" class="w-full h-auto object-cover hover:scale-110 rounded-lg" alt="">
                    <h1>{{ post.title }}</h1>
                    <p>{{ post.short_decription }}</p>
                    <div class="flex flex-row flex-wrap justify-center items-center w-full px-2 py-2 space-x-1">
                        <Link :href="route('post.show', post.slug)" class="w-auto h-auto px-3 py-1 rounded-lg bg-gray-800 opacity-60 text-white text-center text-sm"> <i class="fas fa-eye"></i> </Link>
                        <Link :href="route('post.edit', post.slug)" class="w-auto h-auto px-3 py-1 rounded-lg bg-yellow-500 opacity-60 text-white text-center text-sm"> <i class="fas fa-pencil-alt"></i> </Link>
                        <button @click="trashPost(post.slug)" class="w-auto h-auto px-3 py-1 rounded-lg bg-red-500 opacity-60 text-white text-center text-sm"> <i class="fas fa-trash-alt"></i> </button>
                    </div>
                </div>
            </div>
            <Pagination class="mt-5" :links="posts.links"/>
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
        posts: Object
    },
    methods: {
        trashPost(postSlug) {
            Inertia.delete(route('post.destroy', postSlug))
        }
    }
}
</script>