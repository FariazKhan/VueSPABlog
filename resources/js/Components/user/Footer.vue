<template>
    <footer class="bg-gray-900">	
		<div class="container max-w-6xl mx-auto flex items-center px-2 py-8">
			<div class="w-full mx-auto flex flex-wrap items-center">
				<div class="flex w-full md:w-1/2 justify-center md:justify-start text-white font-extrabold">
					<a class="mr-3 text-gray-900 no-underline hover:text-gray-900 hover:no-underline" href="#">
						<span class="text-base text-gray-200">{{ blogInfo.blogName }}</span>
					</a>
					<span class="text-gray-200" v-html="'| ' + blogInfo.footerText"></span>
				</div>
				<div class="flex w-full pt-2 content-center justify-between md:w-1/2 md:justify-end">
					<ul class="list-reset flex justify-center flex-1 md:flex-none items-center">
						<li class="mr-2">
							<Link class="inline-block cursor-pointer text-gray-600 no-underline hover:text-gray-200 hover:underline py-2 px-2" :href="route('homePage')">Home</Link>
						</li>
						<li v-for="(page, index) in pages" :key="index">
							<Link class="cursor-pointer inline-block text-gray-600 no-underline hover:text-gray-200 hover:underline py-2 px-3" :href="route('page.show', page.slug)">{{ page.title }}</Link>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</footer>
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
        },
        blogInfo() {
            return usePage().props.value.blogInfo
        }
    }
}
</script>