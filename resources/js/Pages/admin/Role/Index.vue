<template>

    <Head>
        <title>Manage roles - The TechPost</title>
    </Head>

    <Admin>
        <template #pageTitle>
            Manage roles
        </template>

        <template #content>
            <div class="w-auto ml-auto space-x-3 flex justify-items-end px-4 py-3">
                <Link :href="route('role.create')" class="px-2 py-2 bg-green-400 rounded text-white">Add a role</Link>
                <Link :href="route('trashedRoles')" class="px-2 py-2 bg-yellow-500 rounded text-white">View trashed roles</Link>
            </div>
            <div class="w-full flex justify-center items-center px-4 py-3">
                <FlashMessages></FlashMessages>
            </div>
            <div class="w-full h-auto px-3 py-2">
                <div class="flex flex-col max-w-full">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Name
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Role holders
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Permissions
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <tr v-for="(role, index) in roles.data" :key="index">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ role.name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ roleHolders[role.name] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap flex flex-row space-x-2 flex-wrap space-y-2">
                                                <span v-for="(permission, index) in permissions[role.name]" :key="index" class="px-1 py-2 text-center text-xs rounded border border-gray-300">
                                                    {{ permission }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 space-x-2">
                                                <Link :href="route('role.edit', role.name)" class="px-2 py-2 rounded bg-yellow-500 text-white text-center"><i class="fas fa-pencil-alt"></i></Link>
                                                <button @click="trashRole(role.name)" class="px-2 py-2 rounded bg-red-500 text-white text-center"><i class="fas fa-trash"></i></button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- New table -->

            <Pagination class="mt-5" :links="roles.links"/>
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
        roles: Object,
        roleHolders: Object,
        permissions: Object
    },
    methods: {
        trashRole(name) {
            Inertia.delete(route('role.destroy', name))
        }
    }
}
</script>