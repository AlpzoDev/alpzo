<script setup>

import Default from "@/Layouts/default.vue";
import {Link} from '@inertiajs/vue3'
import Item from "@/Components/project/item.vue";
import {FwbButton, FwbInput, FwbSelect, FwbToggle} from "flowbite-vue";
import {ref, watch} from "vue";

const props = defineProps({
    projects: Array,
    globalPhpVersion: String,
    phpVersions: Array,
    hasProjects: Boolean,
    globalNodeVersion: String,
    nodeVersions: Array,
    paths: Array
})

const projects = props.projects

const phpVersion = ref('')
const nodeVersion = ref('')

const isFavorite = ref(false)
const listProjects = ref(projects)

const search = ref('')

const selectedPath = ref('')
watch(() => selectedPath.value, () => {
    listProjects.value = projects.filter(project => project.path === selectedPath.value + '\\'+ project.name.replace(' ', '-'))
})

watch(search, () => {
    if (search.value === '') {
        listProjects.value = projects
        return
    }
    listProjects.value = projects.filter(project => project.name.toLowerCase().includes(search.value.toLowerCase()))
})


watch(() => isFavorite.value, () => {
    if (isFavorite.value) {
        listProjects.value  = projects.filter(project => project.is_favorite === isFavorite.value)
    }
    else {
        listProjects.value = projects
    }
})

watch(nodeVersion, () => {
    listProjects.value = projects.filter(project => project.node_version === nodeVersion.value)
})

watch(phpVersion, () => {
    listProjects.value = projects.filter(project => project.php_version === phpVersion.value)
})

const reset = () => {
    phpVersion.value = ''
    nodeVersion.value = ''
    isFavorite.value = false
    selectedPath.value = ''
    search.value = ''
}


</script>
<template>
    <Default>
        <template #title>
            Projects
        </template>
        <template #headActions>
            <Link href="/projects/create" title="Add PHP Version" class="flex gap-4">
                <span class="font-medium text-white/90 block  icon-[mdi--plus] text-xl text-center p-1"></span>
                <span class="text-white dark:text-white font-medium block">Add Project</span>
            </Link>
        </template>
        <div class="w-full flex flex-col gap-4 mb-4 dark:text-white dark:bg-gray-800 bg-white rounded-lg shadow p-4">
            <div class="flex items-center gap-4 *:w-full">
                <FwbSelect label="PHP Version" :options="phpVersions" size="sm" v-model="phpVersion"/>
                <FwbSelect label="Node Version" :options="nodeVersions" size="sm" v-model="nodeVersion"/>
            </div>
            <div class="flex items-center gap-4 w-full *:w-full ">
                <FwbInput v-model="search" placeholder="Search Project"  size="sm" label="Search" maxlength="50"/>
                <FwbSelect label="Path" :options="paths" size="sm" v-model="selectedPath"/>
            </div>

            <div class="flex items-center gap-4">
                <FwbToggle label="Favorite Projects" v-model="isFavorite" reverse  />
            </div>
            <div class="flex items-center gap-4">
                <FwbButton @click="reset" size="sm">Reset</FwbButton>
            </div>
        </div>
        <div class="w-full grid sm:grid-cols-1  xl:grid-cols-2 2xl:grid-cols-3  gap-4" v-if="projects.length > 0">
            <item v-for="project in listProjects" :project="project" :key="project.name" :globalPhpVersion="globalPhpVersion"/>
        </div>
        <div class="w-full flex items-center justify-center text-white" v-else>
                Not Project
        </div>
    </Default>
</template>
