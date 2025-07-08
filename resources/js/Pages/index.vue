<script setup>
import Default from "@/Layouts/default.vue";
import {Link, router} from '@inertiajs/vue3'
import axios from "axios";
import {ref} from "vue";
import {FwbButton} from "flowbite-vue";

const props = defineProps({
    globalPhpVersion: String,
    globalNodeVersion: String,
    services: Object,
    allServices: Array,
    projects: Array,
})

const _allServices  = ref(props.allServices)
const  changeServiceStatus = async (name) => {
   await axios.get(`/services/${name}/restart`).then(res => {
        router.reload()
    }).catch((error) => {
        alert(error)
    })
}

const serviceStop = async  (name) => {
    await axios.get(`/services/${name}/stop`).then(res => {
        router.reload()
    }).catch((error) => {
        alert(error)
    })
}
const serviceStart = async  (name) => {

    await axios.get(`/services/${name}/start`).then(res => {
        router.reload()
    }).catch((error) => {
        alert(error)
    })
}
</script>

<template>
    <default>
        <template #title>
            Dashboard
        </template>

        <div class="w-full grid grid-cols-2 gap-4 mb-2">
            <Link href="/php-versions" class="w-full bg-white rounded-lg shadow p-4 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Global PHP Version</h2>
                    <span class="flex w-3 h-3 me-3 bg-green-500 rounded-full"></span>
                </div>
                <p class="text-gray-500 dark:text-gray-400">{{ globalPhpVersion }}</p>
            </Link>

            <Link href="/node-versions" class="w-full bg-white rounded-lg shadow p-4 dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Global Node Version</h2>
                    <span class="flex w-3 h-3 me-3 bg-green-500 rounded-full"></span>
                </div>
                <p class="text-gray-500 dark:text-gray-400">{{ globalNodeVersion }}</p>
            </Link>

        </div>

        <div class="w-full flex flex-col gap-4 mt-4 items-center">
            <div class="w-full flex justify-between">
                <h2 class=" text-lg font-semibold text-gray-900 dark:text-white">Services</h2>
                <FwbButton @click="router.visit('/services/all/stop')" outline size="xs"
                           :disabled="allServices.length <= 0"
                           class="flex gap-2 items-center mr-4 hover:!bg-indigo-500 hover:!text-white !text-indigo-500 !border-indigo-500">
                    <span class="mr-2">All Services Stop</span>
                    <span class="icon-[mdi--close-circle-outline]"></span>
                </FwbButton>
            </div>
            <div class="w-full bg-white rounded-lg shadow p-4 dark:bg-gray-800" v-for="service in _allServices"
                 :key="service.name">
                <div class="flex items-center justify-between">
                    <h2 class=" text-lg font-semibold text-gray-900 dark:text-white">{{ service.name }}</h2>
                    <div class=" flex items-center gap-2">
                        <FwbButton color="green" size="xs" @click="serviceStart(service.name)"
                                   class="flex gap-2 items-center mr-4" v-if="service.status !== services[service.name]?.status">
                            <span class="mr-2">Start</span>
                            <span class="icon-[mdi--close-circle-outline]"></span>
                        </FwbButton>
                        <FwbButton color="red" outline size="xs" @click="serviceStop(service.name)"
                                   class="flex gap-2 items-center mr-4" v-if="service.status === services[service.name]?.status">
                            <span class="mr-2">Stop</span>
                            <span class="icon-[mdi--close-circle-outline]"></span>
                        </FwbButton>
                        <FwbButton color="alternative" size="xs" @click="changeServiceStatus(service.name)"
                                   class="flex gap-2 items-center mr-4" v-if="service.status === services[service.name]?.status">
                            <span class="mr-2">Refresh</span>
                            <span class="icon-[mdi--refresh]"></span>
                        </FwbButton>
                         <span class="flex w-3 h-3 me-3 bg-green-500 rounded-full"
                               :class="  service.status === services[service.name]?.status ? 'bg-green-500' : 'bg-red-500'"
                         ></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-full flex flex-col  gap-4 mt-4">
            <div class="w-full flex justify-between">
                <h2 class=" text-lg font-semibold text-gray-900 dark:text-white block">Favorite Projects</h2>
                <FwbButton @click="router.visit('/projects/create')" outline size="xs"
                           class="flex gap-2 items-center mr-4 hover:!bg-indigo-500 hover:!text-white !text-indigo-500 !border-indigo-500">
                    <span class="mr-2">Create Project</span>
                    <span class="icon-[mdi--card-plus]"></span>
                </FwbButton>
            </div>
            <Link :href="'/projects/'+project.id" class="w-full bg-white rounded-lg shadow p-4 dark:bg-gray-800"
                  v-for="project in projects"
                  :key="project.id">
                <div class="flex items-center justify-between">
                    <h2 class=" text-lg font-semibold text-gray-900 dark:text-white">{{ project.name }}</h2>
                    <div class=" flex items-center gap-2">
                        <FwbButton color="green" size="xs" outline :href="'/projects/'+project.id">
                            Visit
                            <i class="icon-[mdi--arrow-top-right]"></i>
                        </FwbButton>
                    </div>
                </div>
            </Link>
        </div>
    </default>
</template>
