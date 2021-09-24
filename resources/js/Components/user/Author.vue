<template>
    <div v-if="Object.keys(this.blogAuthor).length > 0" class="w-full font-sans p-8 md:p-24">
        <div class="w-full flex flex-wrap items-center p-6 border border-gray-300 rounded-md">
            <h1 class="mb-4 text-lg" style="flex: 0 0 100%;">About the blog author:</h1>
            <img class="w-10 h-10 rounded-full mr-4" :src="`/assets/images/user/${blogAuthor.image}`" alt="Avatar of Author">
            <div class="flex-1">
                <p class="text-base font-bold md:text-xl leading-none">{{ blogAuthor.name }}</p>
                <p class="text-gray-600 text-xs md:text-base">{{ blogAuthor.about }}</p>
            </div>
        </div>
    </div>
    <div v-else class="w-full font-sans p-8 md:p-24">

    </div>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3'
export default {
    computed: {
        blogAuthor() {
            return usePage().props.value.blogAuthor
        }
    }
}
</script>