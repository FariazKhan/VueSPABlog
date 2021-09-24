<template>
    <Master>
        <template v-slot:content>
            <!--Lead Card-->
            <LeadCard :postTitle="Object.values(posts)[0].title" :postCategory="postCategories[0]" :postLink="route('post.show', Object.values(posts)[0].slug)" :postImage="Object.values(posts)[0].featured_image" :shortDescription="shortDescriptions[0]" :createdBy="authors[0]" :readTime="readTime[0]" />
            <!--/Lead Card-->
            
            
            <!--Posts Container-->
            <div class="flex flex-wrap justify-center items-center pt-12 -mx-6 px-4">
                <template v-for="(post, index) in posts" :key="post.title">
                    <template v-if="index > 0">
                        <PostCard v-if="isPostOfThirdCol(index)" :postTitle="post.title" :postCategory="postCategories[index]" :postLink="route('post.show', post.slug)" :postImage="post.featured_image" :shortDescription="shortDescriptions[index]" :createdBy="authors[index]" :readTime="readTime[index]" />
                        <PostCard2 v-else :postTitle="post.title" :postCategory="postCategories[index]" :postLink="route('post.show', post.slug)" :postImage="post.featured_image" :shortDescription="shortDescriptions[index]" :createdBy="authors[index]" :readTime="readTime[index]" />
                    </template>
                </template>
            </div>
            <!--/ Post Content-->
        </template>
    </Master>
</template>

<script>
import Master from '@/Layouts/Master'
import LeadCard from '@/Components/user/LeadCard'
import PostCard from '@/Components/user/PostCard'
import PostCard2 from '@/Components/user/PostCard2.vue'

export default {
    data() {
        return {
            mod: 0,
            threeColLastIndex: 0,
            canFitInThreeCol: false
        }
    },
    components: {
        Master,
        LeadCard,
        PostCard,
        PostCard2,
    },
    props: {
        posts: Object,
        authors: Array,
        readTime: Array,
        shortDescriptions: Array,
        postCategories: Array
    },
    mounted() {
        const postCount = Object.keys(this.posts).length
        if((postCount - 1) % 3 === 0) {
            this.canFitInThreeCol = true
        } else {
            this.mod = Math.round((postCount - 1) % 3)
            this.threeColLastIndex = (postCount - this.mod) - 1
        }
    },
    methods: {
        isPostOfThirdCol(index)
        {
            // (post - 1) % 3
            const postCount = Object.keys(this.posts).length
            const modul = Math.round((postCount - 1) % 3)
            const lastIndexOfThirdCol = (postCount - 1) - modul
            if(index > 0 && index <= lastIndexOfThirdCol) {
                return true
            } else {
                return false
            }
}
    }
}
</script>