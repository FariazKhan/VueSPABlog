<template>

<Head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel - The TechPost</title>

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">
    <link href="https://afeld.github.io/emoji-css/emoji.css" rel="stylesheet"> <!--Totally optional :) -->

</Head>
<div class="min-w-full min-h-screen bg-gray-800">
    <Nav :user="user" />


    <div class="flex flex-col md:flex-row">

        <div class="bg-gray-800 shadow-xl h-16 fixed bottom-0 mt-12 md:relative md:h-screen z-10 w-full md:w-48">

            <div class="md:mt-12 md:w-48 md:fixed md:left-0 md:top-0 content-center md:content-start text-left justify-between">
                <ul class="list-reset flex flex-row md:flex-col py-0 md:py-3 px-1 md:px-2 text-center md:text-left">
                    <SidebarItem routeName="showProfile" iconName="far fa-id-badge" label="Profile" />
                    <template v-for="(item, index) in menuItems" :key="index">
                        <SidebarItem :routeName="item.routeName" :iconName="item.iconName" :label="item.label" />
                    </template>
                </ul>
            </div>


        </div>

        <div class="main-content flex-1 bg-gray-100 mt-12 md:mt-2 md:pb-5">
            <div class="bg-gray-800 pt-2">
                <div class="rounded-tl-3xl bg-gradient-to-r from-blue-900 to-gray-800 p-4 shadow text-2xl text-white mt-12">
                    <h3 class="font-bold pl-2"><slot name="pageTitle"></slot></h3>
                </div>
            </div>

            <div class="flex flex-wrap">
                <slot name="content"></slot>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import Nav from '@/Components/admin/Nav'
import SidebarItem from '@/Components/admin/SidebarItem'
import { Head } from '@inertiajs/inertia-vue3'
import { usePage } from '@inertiajs/inertia-vue3'

export default {
    components: {
        Nav,
        SidebarItem,
        Head
    },
    computed: {
        user() {
            return usePage().props.value.auth.user
        },
        menuItems() {
            return usePage().props.value.menuItems
        }
    },
    mounted() {
        console.log(this.menuItems)
    }
}
</script>