<template>
    <!--Nav-->
	<Nav />
	
	
	<!--Title-->
	
    <div class="text-center pt-16 md:pt-32">
		<h1 class="font-bold break-normal text-3xl md:text-5xl">
            <slot name="postTitle"></slot>
        </h1>
	</div>
	
	<!--Container-->
	<div class="container max-w-5xl mx-auto">
		
		<div class="mx-0 sm:mx-6">
			
			<div class="bg-white w-full p-8 md:p-24 text-xl md:text-2xl text-gray-800 leading-normal" style="font-family:Georgia,serif;">
	            
                <!-- postContent slot -->
				<slot name="postContent"></slot>
				
			</div>
			
			
			<Subscribe />
			
			
			<Author />
			
		</div>
		
		
	</div>
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
        components: {
            Nav,
            Subscribe,
            Author,
            Footer,
        }
    }
</script>