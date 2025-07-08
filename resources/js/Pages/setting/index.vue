<script setup>

import Default from "@/Layouts/default.vue";
import {ref} from "vue";
import {FwbButton, FwbCard, FwbInput, FwbTab, FwbTabs, FwbToggle} from "flowbite-vue";
import Path from "@/Components/setting/path/paths.vue";
import {router, useForm} from "@inertiajs/vue3";
import axios from "axios";

const props =defineProps({
    phpVersions: Array,
    globalPhpVersion:String,
    nodeVersions: Array,
    globalNodeVersion:String,
    paths: Array,
    autoVirtualHost: Boolean,
    hostName: String,
    defaultPath: String,
    services: Array
})

const activeTab = ref('global')
const autoVirtualHost = ref(props.autoVirtualHost)

const form = useForm({
    hostName: props.hostName,
    allowDomain: false
})


const autoVirtualHostChange = () => {
    axios.post('/settings/auto-virtual-host').then((response) => {
      router.reload()
    })
}
</script>

<template>
    <default>
        <template #title>
            Settings
        </template>
        <fwb-tabs v-model="activeTab" class="w-full pt-5 ">
            <fwb-tab name="global" title="Global">
                <div class="w-full flex flex-col gap-4">

                    <fwb-card variant="image" class=" !w-full !max-w-none p-3 group  ">
                        <div class="p-2 flex items-center justify-between gap-2 w-full">
                            <div class="flex-1">
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    Auto Project Scan
                                </h5>
                                <p class="font-normal text-gray-700 dark:text-white">
                                    Automatic project scan for configuration generated.
                                </p>
                            </div>
                            <div class=" flex justify-center items-center p-2">
                                <FwbToggle v-model="autoVirtualHost" label="Enable" class="[&>span:first-of-type]:dark:group-hover:bg-indigo-500
                                [&>span:first-of-type]:dark:bg-indigo-500" @change="autoVirtualHostChange" />
                            </div>
                        </div>
                    </fwb-card>
                    <fwb-card variant="image" class=" !w-full !max-w-none p-3 ">
                        <div class="p-2 flex items-center justify-between gap-2 w-full">
                            <div class="flex-1">
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    Hostname template
                                </h5>
                                <p class="font-normal text-gray-700 dark:text-white">
                                    alpzo.{{form.hostName}}
                                </p>
                            </div>
                            <div class=" flex flex-col justify-center items-center p-2">
                                <form @submit.prevent="form.post('/settings/host-name')" class="flex flex-col gap-2">
                                    <div class="">
                                        <FwbInput v-model="form.hostName" label="Hostname" size="sm"/>
                                        <span  v-if=" form.errors.hostName" class="text-red-500 text-xs"> {{ form.errors.hostName }}</span>
                                    </div>
                                    <FwbButton class="mt-2" type="submit" size="xs">Save</FwbButton>
                                </form>
                            </div>
                        </div>
                    </fwb-card>
                </div>
            </fwb-tab>
            <fwb-tab name="paths" title="Paths">
                <Path :paths="paths"/>
            </fwb-tab>
        </fwb-tabs>
    </default>
</template>

