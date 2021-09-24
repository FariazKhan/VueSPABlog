<template>
    <nav class="mt-0 w-full">
        <div class="container mx-auto flex items-center">
            
            <div class="flex w-1/2 pl-4 text-sm">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                    <li class="mr-2">
                        <Link class="inline-block cursor-pointer text-gray-600 no-underline hover:text-gray-200 hover:underline py-2 px-2" :href="route('homePage')">Home</Link>
                    </li>
                    <li v-for="(page, index) in pages" :key="index" class="mr-2">
                        <Link class="inline-block cursor-pointer text-gray-600 no-underline hover:text-gray-200 hover:underline py-2 px-2" :href="route('page.show', page.slug)">{{ page.title }}</Link>
                    </li>
                </ul>
            </div>
            
        </div>
    </nav>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3'
import { Link } from '@inertiajs/inertia-vue3'

export default {
    components: {
        Link
    },
    computed: {
        pages() {
            return usePage().props.value.pages
        }
    }
}
</script>