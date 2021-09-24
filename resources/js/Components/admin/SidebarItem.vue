<template>
    <li class="mr-3 flex-1">
        <Link :href="route(routeName)" class="block py-1 md:py-3 pl-1 align-middle text-white no-underline hover:text-white border-b-2 border-gray-800 hover:border-pink-500">
            <i :class="`${iconName} pr-0 md:pr-3`"></i>
            <span class="pb-1 md:pb-0 text-xs md:text-base text-gray-600 md:text-gray-400 block md:inline-block">
                {{ label }}
            </span>
        </Link>
    </li>
</template>

<script>
import { Link } from '@inertiajs/inertia-vue3'

export default {
    props: {
        routeName: String,
        iconName: String,
        label: String,
    },
    components: {
        Link
    }
}
</script>