<template>
    <!--Header-->
	<Header />
	
	<!--Container-->
	<div class="container px-4 md:px-0 max-w-6xl mx-auto -mt-32">
		
		<div class="mx-0 sm:mx-6">
			
			<!--Nav-->
			<Nav />
			
			<div class="bg-gray-200 w-full text-xl md:text-2xl text-gray-800 leading-normal rounded-t">
				
				<slot name="content"></slot>
				
			</div>
			
			
			<!--Subscribe-->	
			<Subscribe />
			<!-- /Subscribe-->
			
			
			<!--Author-->
			<Author />
			<!--/Author-->
			
		</div>
		
		
	</div>
	
	
	<Footer />
</template>

<script>
import Header from '@/Components/user/Header'
import Nav from '@/Components/user/Nav'
import Subscribe from '@/Components/user/Subscribe'
import Author from '@/Components/user/Author'
import Footer from '@/Components/user/Footer'

export default {
    components: {
        Header,
        Nav,
        Subscribe,
        Author,
        Footer
    }
}
</script>