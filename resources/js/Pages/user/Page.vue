<template>
    <PageLayout :featuredImage="featured_image">

        <template #postTitle>{{ page.title }}</template>
        <template #postContent>
            <div v-html="page.body"></div>
        </template>

    </PageLayout>
</template>

<script>
import PageLayout from '@/Layouts/PageLayout'

export default {
    components: {
        PageLayout,
    },
    props: {
        page: Object,
    }
}
</script>