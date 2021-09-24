<template>
    <nav class="bg-gray-800 pt-2 md:pt-1 pb-1 px-1 mt-0 h-auto fixed w-full z-20 top-0">

        <div class="flex flex-wrap items-center">
            <div class="flex flex-shrink md:w-1/3 justify-center md:justify-start text-white">
                <img :src="`/assets/images/blog/${blogInfo.blogLogo}`" class="w-10 h-10 rounded-lg" alt="">
            </div>

            <div class="flex flex-1 md:w-1/3 justify-center md:justify-start text-white px-2">
                <span class="relative w-full">
                    <input type="search" placeholder="Search" class="w-full bg-gray-900 text-white transition border border-transparent focus:outline-none focus:border-gray-400 rounded py-3 px-2 pl-10 appearance-none leading-normal">
                    <div class="absolute search-icon" style="top: 1rem; left: .8rem;">
                        <svg class="fill-current pointer-events-none text-white w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M12.9 14.32a8 8 0 1 1 1.41-1.41l5.35 5.33-1.42 1.42-5.33-5.34zM8 14A6 6 0 1 0 8 2a6 6 0 0 0 0 12z"></path>
                        </svg>
                    </div>
                </span>
            </div>

            <div class="flex w-full pt-2 content-center justify-between md:w-1/3 md:justify-end">
                <ul class="list-reset flex justify-between flex-1 md:flex-none items-center">
                    <li class="flex-1 md:flex-none md:mr-3">
                        <div class="relative inline-block">
                            <div class="drop-button text-white focus:outline-none flex flex-row flex-wrap space-x-2 justify-center items-center">
                                <div class="block">
                                    <img class="w-10 h-10 rounded-full" :src="`/assets/images/user/${user.image}`" />
                                </div> 
                                <div class="block cursor-pointer" @click.prevent="toggleDropDownMenu">
                                    Hi, {{ user.name }} <svg class="h-3 fill-current inline" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" /></svg>
                                </div>
                            </div>
                            <transition name="dropDownTransition">
                                <div v-if="showDropdown" class="dropdownlist absolute bg-gray-800 text-white right-0 mt-3 p-3 overflow-auto z-30">
                                    <div class="border border-gray-800"></div>
                                    <Link @click="logOutUser" class="p-2 hover:bg-gray-800 text-white text-sm no-underline hover:no-underline block"><i class="fas fa-sign-out-alt fa-fw"></i> Log Out</Link>
                                </div>
                            </transition>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </nav>
</template>

<style scoped>
    .dropDownTransition-enter-active {
       transition: all .3s ease;
    }
    .dropDownTransition-leave-active {
        transition: all .2s ease;
    }
    .dropDownTransition-enter { 
        transform: translateY(-50px);
        opacity: 0;
    }
    .dropDownTransition-leave-to {
        transform: translateY(-50px);
        opacity: 0; 
    }
</style>

<script>
import { Link, usePage } from '@inertiajs/inertia-vue3'
import { Inertia } from '@inertiajs/inertia'

export default {
    data() {
        return {
            showDropdown: false
        }
    },
    components: {
        Link
    },
    props: {
        user: Object
    },
    methods: {
        logOutUser() {
            Inertia.post(route('logout'))
        },
        toggleDropDownMenu() {
            this.showDropdown = !this.showDropdown
        }
    },
    computed: {
        blogInfo() {
            return usePage().props.value.blogInfo
        }
    }
}
</script>