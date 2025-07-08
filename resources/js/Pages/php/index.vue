<script setup>

import Default from "@/Layouts/default.vue";
import {Link, router} from '@inertiajs/vue3'
import Table from "@/Components/Global/Table/index.vue";
import Item from "@/Components/Global/Table/item.vue";
import Td from "@/Components/Global/Table/td.vue";
import {FwbButton, FwbCard, FwbDropdown} from "flowbite-vue";

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
const props = defineProps({
    phpVersions: Array,
    globalPhpVersion: String
})

const deletePhpVersion = (name) => {
    router.get('/php-versions/' + name + '/delete')
}

const changePHPVersion = (name) => {
    if (name === props.globalPhpVersion) {
        return
    }
    router.get('/php-versions/' + name + '/change')
}

const activeProcessCount = () => {
   let activeProcessCount = 0

   props.phpVersions.forEach((phpVersion) => {
       if (phpVersion.isActive) {
           activeProcessCount++
       }
   })
  return activeProcessCount
}

</script>

<template>
    <default>

        <template #title>
            PHP Versions
        </template>
        <template #headActions>
            <Link href="/php-versions/create" title="Add PHP Version" class="flex gap-4">
                <span class="font-medium text-white/90 block  icon-[mdi--plus] text-xl text-center p-1"></span>
                <span class="text-white dark:text-white font-medium block">Add PHP Version</span>
            </Link>
        </template>

        <FwbCard class="mb-4 !max-w-none p-1" variant="image">
            <div class="font-bold text-lg mx-auto text-center text-white">All PHP Versions</div>
            <div class="flex items-center m-2 gap-2 justify-center max-w-sm mx-auto">
                <FwbButton color="green" outline size="xs" @click="router.get('/php-versions/all/start')"
                           :disabled="activeProcessCount()=== phpVersions.length"
                           class="flex gap-2 items-center mr-4">
                    <span class="mr-2">Start</span>
                    <span class="icon-[mdi--play-circle]"></span>
                </FwbButton>
                <FwbButton color="red" outline size="xs" @click="router.get('/php-versions/all/stop')"
                           :disabled="activeProcessCount()=== 0"
                           class="flex gap-2 items-center mr-4">
                    <span class="mr-2">Stop</span>
                    <span class="icon-[mdi--close-circle]"></span>
                </FwbButton>
                <FwbButton color="alternative" size="xs" @click="router.get('/php-versions/all/restart')"
                           :disabled="activeProcessCount()=== 0"
                             class="flex gap-2 items-center mr-4">
                    <span class="mr-2">Restart</span>
                    <span class="icon-[mdi--refresh-circle]"></span>
                </FwbButton>
            </div>
        </FwbCard>

        <Table :title="title" :description="description" :columns="columns">
            <item v-for="row in phpVersions">
                <Td class="w-full">
                    <Link :href="'/php-versions/'+row.name" title="Vİew PHP Version"
                          class="flex gap-2 items-center text-white">
                         <span class="flex w-3 h-3 me-3 rounded-full"
                               :class="row.isActive ? 'bg-green-600' : 'bg-red-600'"
                         ></span>
                        {{ row.name }}
                    </Link>
                </Td>
                <Td>
                    <FwbDropdown label="Actions" placement="left" >
                        <template #trigger>
                            <FwbButton color="alternative" size="sm">
                                <span class="icon-[mdi--menu] text-lg text-center "></span>
                            </FwbButton>
                        </template>
                        <div class="flex space-x-1.5 items-center p-2 gap-x-2 border dark:border-gray-700 w-auto brightness-90 rounded-lg ">

                            <Link :href="'/php-versions/'+row.name+'/start'" v-if="!row.isActive" title="Start PHP Version"
                                  class="flex gap-2 items-center justify-center text-green-400 border border-green-400 p-1 rounded-lg">
                                <span class="font-medium text-green-400/90  icon-[mdi--play-circle] text-center "></span>
                            </Link>

                            <Link :href="'/php-versions/'+row.name+'/restart'"  v-if="row.isActive" title="Php version refresh"
                                  class="flex gap-2 items-center justify-center text-gray-400 border border-gray-400 p-1 rounded-lg">
                                <span class="font-medium text-gray-400/90  icon-[mdi--refresh] text-center "></span>
                            </Link>

                            <Link :href="'/php-versions/'+row.name+'/stop'" v-if="row.isActive" title="Php version stop process"
                                  class="flex gap-2 items-center justify-center text-red-600 border border-red-600 p-1 rounded-lg">
                                <span class="font-medium text-red-600/90  icon-[mdi--stop-circle] text-center "></span>
                            </Link>

                            <Link title="Change PHP Version" @click="changePHPVersion(row.name)"
                                  class="flex items-center border border-blue-600 p-1 rounded-lg">
                            <span v-if="globalPhpVersion === row.name"
                                  class="font-medium text-blue-600 icon-[mdi--check-circle] text-center
                                         border border-blue-600"></span>
                                <span v-else
                                      class="font-medium text-blue-500  icon-[mdi--exchange] text-center"></span>
                            </Link>

                            <Link :href="'/php-versions/'+row.name+'/show-folder'" title="Open php folder"
                                  class="flex gap-2 items-center justify-center text-gray-400 border border-gray-400 p-1 rounded-lg">
                            <span class="font-medium text-gray-400/90  icon-[mdi--folder] text-center "></span>
                            </Link>
                            <button title="Delete PHP Version"
                                    class="disabled:cursor-not-allowed disabled:opacity-50 disabled:text-gray-400 border border-red-600 rounded-lg p-1 justify-center flex items-center"
                                    @click="deletePhpVersion(row.name)" :disabled="globalPhpVersion === row.name">
                            <span
                                class="font-medium text-red-600  icon-[mdi--delete] text-center"></span>
                            </button>
                        </div>
                    </FwbDropdown>

                </Td>
            </item>
        </Table>

    </default>
</template>
