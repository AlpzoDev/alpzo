<script setup>
import Default from "@/Layouts/default.vue";
import Modal from "@/Components/Global/Modal/index.vue";
import {ref} from "vue";
import axios from "axios";
import Item from "@/Components/project/item.vue";
import {FwbButton, FwbCard, FwbSpinner} from "flowbite-vue";

const props = defineProps({
    phpVersion: String,
    activePHPExtensions: Array,
    phpIni: String,
    isIniBackup: Boolean,
    projects: Array,
    isActive: Boolean
})
const isIniBackup = ref(props.isIniBackup)
const  phpIni = ref(props.phpIni)

const isActive = ref(props.isActive)

const phpIniReset = () => {
    axios.post('/php-versions/' + props.phpVersion + '/reset-ini').then((response) => {
        phpIni.value = response.data.phpIni
    }).catch(err=>alert(err))
}

const updatePHPIni = () => {
    axios.post('/php-versions/' + props.phpVersion + '/update-ini', {phpIni: phpIni.value}).then((response) => {
        phpIni.value = response.data.phpIni
        isIniBackup.value = true
    }).catch(err=>alert(err))
}

const phpIniBackup = () => {
    axios.post('/php-versions/' + props.phpVersion + '/backup-ini').then((response) => {
        phpIni.value = response.data.phpIni
    }).catch(err=>alert(err))
}

const serviceStart = () => {
    axios.get('/php-versions/' + props.phpVersion + '/start')
        .then((response) => {
            isActive.value = true
        })
        .catch(err=>alert(err))
}

const serviceStop = () => {
    axios.get('/php-versions/' + props.phpVersion + '/stop')
        .then((response) => {
            isActive.value = false
        })
        .catch(err=>alert(err))
}

const ServiceRestart = () => {
    axios.get('/php-versions/' + props.phpVersion + '/restart')
        .then((response) => {
            isActive.value = true
        })
        .catch(err=>alert(err))
}

</script>

<template>
<default >
    <template #title>
        PHP Version Details
    </template>

    <fwb-card variant="image" class=" !w-full !max-w-none px-4 mb-4">
        <div class="p-2 flex items-center justify-between gap-2 w-full pb-2">
            <div class="flex-1">
                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                 PHP -  {{ phpVersion }}
                </h5>
            </div>
            <span class="flex w-3 h-3 me-3 rounded-full bg-green-500"
                  v-if="isActive"
            ></span>
            <span class="flex w-3 h-3 me-3 rounded-full bg-red-500"
                  v-else
            ></span>
        </div>
        <hr class="my-4">
        <div class="p-2 flex items-center justify-between gap-2 w-full">
            <FwbButton color="green" size="xs" @click="serviceStart()"
                       class="flex gap-2 items-center mr-4" v-if="!isActive">
                <span class="mr-2">Start</span>
                <span class="icon-[mdi--close-circle-outline]"></span>
            </FwbButton>
            <FwbButton color="alternative" size="xs" @click="ServiceRestart()"
                       class="flex gap-2 items-center mr-4" v-if="isActive">
                <span class="mr-2">Refresh</span>
                <span class="icon-[mdi--refresh]"></span>
            </FwbButton>
            <FwbButton color="red" outline size="xs" @click="serviceStop()"
                       class="flex gap-2 items-center mr-4" v-if="isActive">
                <span class="mr-2">Stop</span>
                <span class="icon-[mdi--close-circle-outline]"></span>
            </FwbButton>
        </div>

    </fwb-card>

    <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Active Extensions:</h2>
    <ul class=" space-y-1 text-gray-500 list-inside dark:text-gray-400 grid grid-cols-4">
        <li class="flex items-center " v-for="extension in activePHPExtensions">
            <svg class="w-3.5 h-3.5 me-2 flex-shrink-0"
                 :class="activePHPExtensions.includes(extension) ? 'text-green-500 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'"
                 aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
            </svg>
            {{ extension }}
        </li>
    </ul>

    <Modal title="PHP.ini" :fullScreen="true">
        <textarea class="w-full h-full p-4 text-gray-100 bg-gray-800 min-w-full" rows="15" v-model="phpIni"></textarea>
        <div class="w-full flex justify-between items-center">
            <button type="button" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded" @click="phpIniReset">Default</button>
            <button type="button" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-1 px-2 rounded disabled:cursor-not-allowed
            disabled:opacity-50"
                    @click="phpIniBackup" :disabled="!isIniBackup">Backup</button>
            <button type="button" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded" @click="updatePHPIni">Update</button>
        </div>
    </Modal>
    <h2 class="mb-2 mt-4 text-lg font-semibold text-gray-900 dark:text-white">Projects:</h2>
    <div class="w-full grid grid-cols-1 gap-4 mt-4 lg:grid-cols-2 xl:grid-cols-3">
        <Item v-for="project in projects" :project="project" :globalPhpVersion="phpVersion" :key="project.id" v-if="projects.length > 0" />
        <div class="w-full flex items-center  text-white" v-else>
            No projects found
        </div>
    </div>
</default>
</template>
