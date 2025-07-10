<script setup>

import Default from "@/Layouts/default.vue";
import Table from "@/Components/Global/Table/index.vue";
import Item from "@/Components/Global/Table/item.vue";
import Td from "@/Components/Global/Table/td.vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import {computed, reactive} from "vue";
import VersionItem from "@/Components/node/versionItem.vue";

const columns = [
    {
        name: 'Date',
        key: 'date',
    },
    {
        name: 'Version',
        key: 'version',
    },

    {
        name: 'Actions'
    }
];

const  props = defineProps({
    allNodeVersions: Array,
    nodeVersions: Array,
    globalNodeVersion: String
})
const search = reactive({
    search: ''
});

const list= computed(() => {
  return   props.allNodeVersions?.filter((php) => php?.name?.includes(search?.search));
})

const CheckNodeVersion = (phpVersion) => {
    return props.nodeVersions.filter((node) => node.name  ==='node-'+phpVersion+'-win-x64').length > 0;
}
</script>

<template>
<default>
    <template #title>
        All Nodejs Versions
    </template>

    <Table :columns="columns">
       <version-item v-for="version in allNodeVersions" :key="version.version"
                     :version="version"
                     :checkNodeVersion="CheckNodeVersion"
       />
    </Table>
</default>
</template>
