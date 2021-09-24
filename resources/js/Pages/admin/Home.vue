<template>
    <Admin>
        <template #pageTitle>
            Dashboard
        </template>

        <template #content>
            <div class="flex flex-row w-11/12 mx-auto mt-4 h-auto space-x-3 justify-center items-center flex-wrap rounded-lg shadow border border-gray-300 px-3 py-2">
                <div class="item bg-purple-500 text-white group cursor-pointer px-4 py-3 h-30 w-auto border border-gray-300 rounded-lg text-center flex flex-row flex-wrap">
                    <h1 class="text-4xl mx-3 group-hover:scale-110 transition"><i class="fas fa-mail-bulk"></i></h1> <p class="my-auto">{{ total_posts }} posts in total</p>
                </div>
                <div class="item px-4 py-3 h-30 w-auto bg-red-500 cursor-pointer  text-white border border-gray-300 rounded-lg text-center flex flex-row flex-wrap">
                    <h1 class="text-4xl mx-3"><i class="fas fa-users"></i></h1> <p class="my-auto">{{ total_users }} users in total</p>
                </div>
                <div class="item px-4 py-3 h-30 w-auto bg-yellow-500 cursor-pointer  text-white border border-gray-300 rounded-lg text-center flex flex-row flex-wrap">
                    <h1 class="text-4xl mx-3"><i class="fas fa-users"></i></h1> <p class="my-auto">{{ total_trashed_users }} muted users in total</p>
                </div>
            </div>
        </template>
    </Admin>
</template>

<style scoped>
@import url(https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/5.3.45/css/materialdesignicons.min.css);
</style>

<script>
import Admin from '@/Layouts/Admin'
import Link from '@inertiajs/inertia-vue3'

export default {
    components: {
        Admin,
        Link
    },
    props: {
        total_posts: Number,
        total_users: Number,
        total_trashed_users: Number
    }
}
</script>