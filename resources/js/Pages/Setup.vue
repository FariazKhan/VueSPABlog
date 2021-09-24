<template>
    <div class="container min-w-full min-h-screen bg-gray-100">
        <div class="setup-db-card w-10/12 mx-auto px-3 py-2 bg-white">
            <h1 class="text-xl my-3 text-center">There are some things that you need to set up for your blog, before you can continue.</h1>
            <div class="card-header px-4 py-3 w-full border rounded-tl-md rounded-tr-md">
                Setup your database:
            </div>
            <div class="card-body flex flex-row px-3 py-3 w-full border-l border-r border-b rounded-b-lg">
                <div class="icon text-6xl text-center mr-5">
                    <i class="fas fa-database text-yellow-600"></i>
                </div>
                <div class="content">
                    <h3 class="text-center">You have {{ unmigrated_tables.length }} tables which are not migrated.</h3>
                    The tables are:
                    <ul class="w-100">
                        <li class="w-full bg-gray-100 border rounded text-center" v-for="(table, index) in unmigrated_tables" :key="index">{{ table }}</li>
                    </ul>
                    <hr class="w-full my-3">
                    <form @submit.prevent="submitForm">
                        <button class="px-2 py-1 text-center bg-blue-400 text-white rounded-sm mx-auto">Fix the issue (re-migrate the tables)</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia'
export default {
    props: {
        unmigrated_tables: Object
    },
    methods: {
        submitForm() {
            let data = new FormData
            data.append('tables', this.unmigrated_tables)
            Inertia.post(route('migrateTables'), data)
        }
    }
}
</script>