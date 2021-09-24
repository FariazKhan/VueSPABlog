<template>
    <div class="flex h-full bg-white rounded overflow-hidden shadow-lg px-3 py-2">
        <Link :href="postLink" class="flex flex-wrap no-underline hover:no-underline">
            <div class="w-full md:w-2/3 rounded-t">	
                <img :src="`/assets/images/post/${postImage}`" class="h-full w-full shadow">
            </div>
            
            <div class="w-full md:w-1/3 flex flex-col flex-grow flex-shrink">
                <div class="flex-1 bg-white rounded-t rounded-b-none overflow-hidden shadow-lg">
                    <p class="w-full text-gray-600 text-xs md:text-sm pt-6 px-6">{{ postCategory.name }}</p>
                    <div class="w-full font-bold text-xl text-gray-900 px-6">{{ postTitle }}</div>
                    <p class="text-gray-800 font-serif text-base px-6 mb-5">
                        {{ shortDescription }}
                    </p>
                </div>
                
                <div class="flex-none mt-auto bg-white rounded-b rounded-t-none overflow-hidden shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <img class="w-8 h-8 rounded-full mr-4 avatar" :data-tippy-content="createdBy.name" :src="`/assets/images/user/${createdBy.image}`" alt="Avatar of Author">
                        <p class="text-gray-600 text-xs md:text-sm">{{ readTime }}</p>
                    </div>
                </div>
            </div>
            
        </Link>
    </div>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    props: {
        postLink: '',
        postImage: '',
        postTitle: '',
        shortDescription: '',
        readTime: '',
        createdBy: Object,
        postCategory: Object

    },
    components: {
        Link
    }
}
</script>