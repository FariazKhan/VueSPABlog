<template>

    <Head>
        <title>View Trashed Users - The TechPost</title>
    </Head>

    <Admin>
        <template #pageTitle>
            Trahed Users
        </template>

        <template #content>
            <div class="w-auto ml-auto space-x-3 flex justify-items-end px-4 py-3">
                <Link :href="route('user.create')" class="px-2 py-2 bg-green-400 rounded text-white">Add an user</Link>
                <Link :href="route('user.index')" class="px-2 py-2 bg-yellow-500 rounded text-white">View all users</Link>
            </div>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <div class="w-70 mx-auto text-center px-4 py-3 bg-yellow-100 border border-white rounded-lg">
                <p>Trashed users are completely 'muted', means they cannot even log in.</p>
            </div>
            <div class="w-full h-auto px-3 py-2">
                <table class="table-auto w-full border border-gray-400 rounded">
                    <thead class="bg-yellow-100 mb-4 py-3">
                        <tr>
                            <th>Sl.</th>
                            <th>Name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(user, index) in users.data" :key="index">
                            <td class="text-center">{{ index + 1 }}</td>
                            <td class="text-center">{{ user.name }}</td>
                            <td class="flex flex-row justify-center items-center space-x-2">
                                <button @click="restoreUser(user.name)" class="w-auto h-auto px-4 py-1 rounded-lg bg-green-500 opacity-60 text-white text-center text-sm"> <i class="fas fa-undo"></i> </button>
                                <button @click="removeUser(user.name)" class="w-auto h-auto px-4 py-1 rounded-lg bg-red-500 opacity-60 text-white text-center text-sm"> <i class="fas fa-trash-alt"></i> </button>
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
    },
    methods: {
        restoreUser(name) {
            Inertia.post(route('restoreUser', name))
        },
        removeUser(name) {
            if(confirm('Are you sure that you want to remove this user permanently?')) {
                Inertia.post(route('removeUser', name))
            }
        }
    }
}
</script>