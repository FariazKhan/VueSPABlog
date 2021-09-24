<template>

    <Head>
        <title>Manage users - The TechPost</title>
    </Head>

    <Admin>
        <template #pageTitle>
            Manage users
        </template>

        <template #content>
            <div class="w-auto ml-auto space-x-3 flex justify-items-end px-4 py-3">
                <Link :href="route('user.create')" class="px-2 py-2 bg-green-400 rounded text-white">Add an user</Link>
                <Link :href="route('trashedUsers')" class="px-2 py-2 bg-yellow-500 rounded text-white">View trashed users</Link>
            </div>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <div class="w-full h-auto px-3 py-2">
                <table class="table-auto w-full border border-gray-400 rounded">
                    <thead class="bg-yellow-100 mb-4 py-3">
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users.data" :key="index">
                            <td class="text-center">{{ index + 1 }}</td>
                            <td class="text-center">{{ user.name }}</td>
                            <td class="text-center">
                                <template v-for="(role, idx) in roles[index]" :key="idx">
                                    {{ role }}
                                </template>
                            </td>
                            <td class="flex flex-row justify-center items-center space-x-2">
                                <Link :href="route('user.edit', user.name)" class="px-3 py-2 rounded bg-yellow-500 text-white text-center"><i class="fas fa-pencil-alt"></i></Link>
                                <button @click="trashUser(user.name)" class="px-3 py-2 rounded bg-red-500 text-white text-center"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <Pagination class="mt-5" :links="users.links"/>
        </template>
    </Admin>
</template>

<script>
import Admin from '@/Layouts/Admin'
import { Link } from '@inertiajs/inertia-vue3'
import Pagination from '@/Components/Pagination'
import { Head } from '@inertiajs/inertia-vue3'
import FlashMessages from '@/Components/FlashMessages'
import { Inertia } from '@inertiajs/inertia'

export default {
    components: {
        Admin,
        Link,
        Pagination,
        Head,
        FlashMessages,
    },
    props: {
        users: Object,
        roles: Object
    },
    methods: {
        trashUser(name) {
            Inertia.delete(route('user.destroy', name))
        }
    }
}
</script>