<script setup>

import Default from "@/Layouts/default.vue";
import {Link} from '@inertiajs/vue3'
import Table from "@/Components/Global/Table/index.vue";
import Item from "@/Components/Global/Table/item.vue";
import Td from "@/Components/Global/Table/td.vue";
import {router} from "@inertiajs/vue3";
import {FwbButton, FwbDropdown} from "flowbite-vue";
const title = 'PHP Versions';
const description = 'PHP Versions';
const columns = [
    {
        name: 'Name',
        key: 'name',
    },
    {
        name: 'Actions'
    }
]
defineProps({
    nodeVersions: Array,
    globalNodeVersion: String
})

const deleteVersion = (name) => {
    router.get('/node-versions/' + name+'/delete')
}

const changeNodeVersion = (name) => {
    router.get('/node-versions/' + name+'/change')
}

</script>

<template>
    <default>

        <template #title>
            Nodejs Versions
        </template>
        <template #headActions>
            <Link href="/node-versions/create" title="Add PHP Version" class="flex gap-4">
                <span class="font-medium text-white/90 block  icon-[mdi--plus] text-xl text-center p-1"></span>
                <span class="text-white dark:text-white font-medium block">Add Node Version</span>
            </Link>
        </template>

        <Table :title="title" :description="description" :columns="columns">

            <item v-for="row in nodeVersions">
                <Td class="w-full ">
                    {{ row.name }}
                </Td>
                <Td>
                    <FwbDropdown label="Actions" placement="left" align-to-end>
                        <template #trigger>
                            <FwbButton color="alternative" size="sm">
                                <span class="icon-[mdi--dots-vertical]"></span>
                            </FwbButton>
                        </template>
                        <div class="flex space-x-1.5 items-center p-2 gap-x-2 border dark:border-gray-200 w-auto brightness-90 rounded-md ">

                            <Link title="Change Node Version" @click="changeNodeVersion(row.name)"
                                  class="flex items-center border border-blue-600 p-1 rounded-lg">
                            <span v-if="globalNodeVersion === row.name"
                                  class="font-medium text-blue-600 icon-[mdi--check-circle] text-center
                                         border border-blue-600"></span>
                                <span v-else
                                      class="font-medium text-blue-500  icon-[mdi--exchange] text-center"></span>
                            </Link>

                            <Link :href="'/node-versions/'+row.name+'/show-folder'" title="Open Node folder"
                                  class="flex gap-2 items-center justify-center text-gray-400 border border-gray-400 p-1 rounded-lg">
                                <span class="font-medium text-gray-400/90  icon-[mdi--folder] text-center "></span>
                            </Link>
                            <button title="Delete Node Version"
                                    class="disabled:cursor-not-allowed disabled:opacity-50 disabled:text-gray-400 border border-red-600 rounded-lg p-1 justify-center flex items-center"
                                    @click="deleteVersion(row.name)" :disabled="globalNodeVersion === row.name">
                            <span
                                class="font-medium text-red-600  icon-[mdi--delete] text-center"></span>
                            </button>
                        </div>
                    </FwbDropdown>
                </Td>
            </item>
            <item v-if="nodeVersions.length === 0">
                <Td colspan="2" class="text-center text-red-600 font-bold ">
                    No Nodejs Versions
                </Td>
            </item>
        </Table>

    </default>
</template>
