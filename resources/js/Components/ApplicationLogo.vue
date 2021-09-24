<template>
    <div class="mx-auto">
        <img class="rounded-md" :src="`/assets/images/blog/${blogInfo.blogLogo}`" alt="">
    </div>
</template>

<script>
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    
    computed: {
        blogInfo() {
            return usePage().props.value.blogInfo
        }
    }
}
</script>
