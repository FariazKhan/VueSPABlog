<template>
    <PostLayout :featuredImage="featured_image">

        <template #creationDate>
            - {{ created_at }} -
        </template>
        <template #authorName>
            {{ created_by }}
        </template>
        <template #postTitle>{{ post.title }}</template>
        <template #postfeaturedImage>
        </template>
        <template #postContent>
            <div v-html="post.body"></div>
        </template>
        <template #recentPosts>
            
        </template>


    </PostLayout>
</template>

<script>
import PostLayout from '@/Layouts/PostLayout'
import RecentPostCard from '@/Components/user/RecentPostCard'

export default {
    data() {
        return {
            featured_image: ''
        }
    },
    components: {
        PostLayout,
        RecentPostCard
    },
    props: {
        post: Object,
        created_by: String,
        created_at: String,
    },
    mounted () {
        this.featured_image = this.post.featured_image
    }
}
</script>