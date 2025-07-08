<script setup>

import {FwbButton} from "flowbite-vue";

const props = defineProps({
    project: Object,
    globalPhpVersion: String
})

const project = props.project

const changeHttps = () => {
    axios.get(`/projects/${project.id}/https`).then(() => {
        project.is_secure = !project.is_secure
    })
}

const changeFavorite = () => {
    axios.get(`/projects/${project.id}/favorite`).then(() => {
        project.is_favorite  = !project.is_favorite
    })
}
</script>

<template>
    <div class="bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
        <div class="p-2 flex justify-between items-center">
            <h5 class="mb-2 text-xl pl-2 font-bold tracking-tight text-gray-900 dark:text-white flex gap-2 items-center flex-1">
                {{ project.name }}
            </h5>
            <div class="rounded cursor-pointer flex items-center  mr-2 " :class="project.is_favorite ? 'text-red-500' : 'text-slate-400'" @click="changeFavorite">
                <span class="font-medium block  icon-[mdi--heart] text-xl text-center p-1"></span>
            </div>
        </div>
        <div class="flex gap-4 p-3">
            <button title="Php Version" class="flex gap-2 items-center text-white">
                <span class=" icon-[mdi--language-php] text-xl font-bold text-center"></span>
                <span class="">{{ project.php_version.split('-')[0] ?? 'N/A' }}</span>
            </button>

            <div class="ml-auto flex items-center gap-x-5 ">
                <FwbButton color="light" size="xs" :href="'/projects/'+project.id+'/show-folder'" title="Open Project folder">
                        <span
                            class="icon-[mdi--folder] text-lg"></span>
                </FwbButton>
                <FwbButton outline size="xs"  :href="'/projects/'+project.id" title="View PHP Version" >
                        <span class="icon-[mdi--eye] text-lg"></span>
                </FwbButton>
            </div>
        </div>

    </div>

</template>
