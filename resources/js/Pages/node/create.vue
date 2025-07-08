<script setup>

import Default from "@/Layouts/default.vue";
import Table from "@/Components/Global/Table/index.vue";
import Item from "@/Components/Global/Table/item.vue";
import Td from "@/Components/Global/Table/td.vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
import {computed, reactive} from "vue";

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
  return   props.allNodeVersions.filter((php) => php.name.toLowerCase().includes(search.search.toLowerCase()));
})

const installPhpVersion = (phpVersion) => {
    axios.post('/node-versions/install', phpVersion)
        .then((response) => {
            router.reload();
        })
}

const checkPhpVersion = (phpVersion) => {
    return props.nodeVersions.filter((node) => node.name  ==='node-'+phpVersion+'-win-x64').length > 0;
}
</script>

<template>
<default>
    <template #title>
        All Nodejs Versions
    </template>

    <Table :columns="columns">
        <Item v-for="version in allNodeVersions" :key="version.version">
            <Td>
                {{version.date}}
            </Td>
            <Td>
                {{version.version}}
            </Td>

            <Td>
                <button v-if="!checkPhpVersion(version.version)"  @click="installPhpVersion(version)"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">
                    Install
                </button>
                <div class="text-green-500" v-else>Installed</div>
            </Td>
        </Item>
    </Table>
</default>
</template>
