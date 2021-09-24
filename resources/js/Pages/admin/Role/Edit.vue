<template>
    <Admin>
        <template #pageTitle>
            Create a role
        </template>

        <template #content>
            <div class="w-8/12 mx-auto my-4">
                <FlashMessages />
            </div>
            <form @submit.prevent="submitForm" class="w-full px-3 py-4">
                <div class="w-full h-auto flex flex-col justify-center items-center border space-x-2 border-gray-300 rounded px-2 py-2">
                    <div class="w-7/12 mx-auto px-4 py-4 flex flex-col">
                        <label for="name">Add a name for the role: </label>
                        <input id="name" type="text" v-model="formData.name" class="w-100 shadow-md focus:ring-indigo-500 focus:border-indigo-500 mt-1 block border-gray-300 rounded-md" placeholder="Enter the name">
                        <p class="text-red-500 text-center mt-3" v-if="errors.name">{{ errors.name }}</p>
                    </div>
                    
                    <div class="w-7/12 mx-auto px-4 py-4">
                        <p class="mb-4">Assign a group of permissions:</p>
                        <div class="flex justify-start items-center flex-row space-x-2" v-for="(group, index) in permissionGroupNames" :key="index">
                            <input :value="group.group_name" v-model="formData.groups" :id="`group${index}`" type="checkbox">
                            <label :for="`group${index}`">{{ group.group_name }}</label>
                        </div>
                    </div>
                    <hr class="w-8/12" />
                    <div class="w-7/12 mx-auto px-4 py-4">
                        <p class="mb-4">Or assign permissions individually, if needed: (Previously assigned permissions are selected)</p>
                        <div class="flex justify-start items-center flex-row space-x-2" v-for="(permission, index) in allPermissions" :key="index">
                            <input v-model="formData.permissions" :value="permission.name" :id="`permission${index}`" type="checkbox">
                            <label :for="`permission${index}`">{{ permission.formal_name }}</label>
                        </div>
                    </div>
                </div>
                <div class="w-full mt-5 flex flex-row justify-center items-center">
                    <button class="bg-purple-400 text-white rounded border border-white shadow px-2 py-2" type="submit">Save role</button>
                </div>
            </form>
        </template>
    </Admin>
</template>

<style scoped>
.ck-edito {
    min-width: 100%;
    width: 100%;
}
</style>

<script>
import Admin from '@/Layouts/Admin'
import { Inertia } from '@inertiajs/inertia'
import FlashMessages from '@/Components/FlashMessages'

export default {
    data() {
        return {
            formData: {
                name: '',
                permissions: [],
                groups: []
            },
        }
    },
    props: {
        errors: Object,
        permissionGroupNames: Object,
        allPermissions: Object,
        roleName: String,
        permissions: Object 
    },
    components: {
        Admin,
        FlashMessages
    },
    methods: {
        submitForm() {
            let data = new FormData()
            data.append('_method', 'PUT')
            data.append('name', this.formData.name)
            data.append('permissions', JSON.stringify(this.formData.permissions))
            data.append('groups', JSON.stringify(this.formData.groups))
            Inertia.post(route('role.update', this.roleName), data)
        },
        setPermissions(permissions) {
           this.permissions = permissions
        },
        // checkPermissionsByGroupName(group_name) {
        //     const permissionGroups = {
        //         'Category' : [
        //             'category.view',
        //             'category.create',
        //             'category.edit',
        //             'category.update',
        //             'category.delete',
        //         ],
        //         'Post' : [
        //             'post.view',
        //             'post.create',
        //             'post.edit',
        //             'post.update',
        //             'post.delete',
        //         ],
        //         'Page' : [
        //             'page.view',
        //             'page.create',
        //             'page.edit',
        //             'page.update',
        //             'page.delete',
        //         ],
        //     }
        //     const context = this
        //     const perms = this.permissions
        //     permissionGroups[group_name].forEach(function(category){
        //         context.setPermissions({...perms, category})
        //     })
        // }
    },
    mounted() {
        this.formData.name = this.roleName
        this.formData.permissions = this.permissions
    }
}
</script>