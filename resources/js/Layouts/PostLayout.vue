<template>
    <!--Nav-->
	<Nav />
	
	
	<!--Title-->
	
    <div class="text-center pt-16 md:pt-32">
		<p class="text-sm md:text-base text-teal-500 font-bold">
            <slot name="creationDate"></slot>
            <br>
            <span class="text-gray-500 text-md">
                By <slot name="authorName"></slot>
            </span>
        </p>
		<h1 class="font-bold break-normal text-3xl md:text-5xl">
            <slot name="postTitle"></slot>
        </h1>
	</div>
	
	<!-- postfeaturedImage slot -->
	<slot name="postfeaturedImage">
    	<div class="container w-full max-w-6xl mx-auto bg-white bg-cover mt-8 rounded" :style="`background-image:url(/assets/images/post/${featuredImage}); height: 75vh; background-position: center center; background-size: cover;`"></div>
	</slot>

	
	<!--Container-->
	<div class="container max-w-5xl mx-auto -mt-32">
		
		<div class="mx-0 sm:mx-6">
			
			<div class="bg-white w-full p-8 md:p-24 text-xl md:text-2xl text-gray-800 leading-normal" style="font-family:Georgia,serif;">
	            
                <!-- postContent slot -->
				<slot name="postContent"></slot>
				
			</div>
			
			
			<Subscribe />
			
			
			<Author />
			
		</div>
		
		
	</div>
	
	
	
	<!-- Recent posts  -->
	<div class="bg-gray-200">
		<div class="container w-full max-w-6xl mx-auto px-2 py-8">
			<div class="flex flex-wrap -mx-2">
				<!-- <recentPosts slot -->
				<slot name="recentPosts"></slot>
			</div>
		</div>
	</div>
	<!-- Recent posts ends -->
	
	
	<Footer />
	
</template>

<style scoped>
.font-serif{font-family : 'Quicksand', sans-serif;}
.smooth {
    transition: box-shadow 0.3s ease-in-out;
}
</style>

<script>
    import Nav from '@/Components/user/Nav'
    import Subscribe from '@/Components/user/Subscribe'
    import Author from '@/Components/user/Author'
    import Footer from '@/Components/user/Footer'
    

    export default {
		data() {
			return {
			}
		},
		props: {
			featuredImage: ''
		},
        components: {
            Nav,
            Subscribe,
            Author,
            Footer,
        }
    }
</script>