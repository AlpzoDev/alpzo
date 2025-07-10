<script setup>

import Default from "@/Layouts/default.vue";
import Table from "@/Components/Global/Table/index.vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import VersionItem from "@/Components/php/VersionItem.vue";


const columns = [
    {
        name: 'Date',
        key: 'date',
    },
    {
        name: 'Name',
        key: 'name',
    },
    {
        name: 'Version',
        key: 'version',
    },

    {
        name: 'Actions'
    }
];

const props = defineProps({
    allPHPVersions: Array,
    phpVersions: Array,
    globalPhpVersion: String
})


const filter = ref({
    data: '',
    allVersions: []
});

function searchPHPVersions() {

    if (filter.value.data === '') {
        filter.value.allVersions = props.allPHPVersions
    } else {
        filter.value.allVersions = props.allPHPVersions.filter((phpVersion) => phpVersion.name.toLowerCase().includes(filter.value.data));
    }
}

watch(() => filter.value.data, () => {
    searchPHPVersions();
}, {deep: true, immediate: true});

const checkPhpVersion = (phpVersion) => {
    return props.phpVersions.filter((php) => php.name === phpVersion).length > 0;
}
const isLoading = ref(false);
</script>

<template>
    <default>
        <template #title>
            All PHP Versions
        </template>

        <div class="relative mb-4">
            <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                     xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                </svg>
            </div>
            <input type="search" v-model.trim="filter.data"
                   class="block w-full p-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                   placeholder="Search ..." required/>
        </div>


        <Table :columns="columns">
            <version-item v-for="phpVersion in filter.allVersions" :key="phpVersion.name"
                          :phpVersion="phpVersion"
                          :checkPhpVersion="checkPhpVersion"
            />
        </Table>
    </default>
</template>
