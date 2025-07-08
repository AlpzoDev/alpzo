<script setup>
import Default from "@/Layouts/default.vue";
import {Link, router} from "@inertiajs/vue3";
import {ref} from "vue";
import axios from "axios";
import {FwbAlert, FwbButton, FwbP, FwbTab, FwbTabs, FwbTooltip} from "flowbite-vue";
import ProjectSettings from "@/Components/project/ProjectSettings.vue";

const props = defineProps({
    composer: {
        type: Object,
        default: null
    },
    packages: {
        type: Object,
        default: null
    },
    project: Object,
    isEmpty: Boolean,
    technologies: Object,
    isFolder: Boolean,
    projectTypes: Array,
    phpVersions: Array,
    nodeVersions: Array,
    globalPhpVersion: String,
    globalNodeVersion: String,
    hostName: String,
})

const project = ref(props.project)

const technologies = ref(props.technologies)

const activeTab = ref('General')

const changeHttps = async () => {

    await axios.get(`/projects/${project.value.id}/https`).then(() => {
        project.value.is_secure = !project.value.is_secure
        router.reload()
    })
}

const openGithub = async (key) => {
    await axios.get(`/links/github/${key.replace('/', '.')}`).then(() => {
    })
}

const startProject = async () => {
    await axios.get(`/projects/${project.id}/start`).then(() => {
    })
}

const openLink = async () => {
    await axios.post(`/links/url`, {
        url: project.value.is_secure ? 'https://' + project.value.url : 'http://' + project.value.url,
    })
}

const addGit = async () => {
    await axios.post(`/projects/${props.project.id}/terminal`, {
        command: 'git init',
        php: props.globalPhpVersion,
        node: props.globalNodeVersion
    }).then((response) => {
        technologies.value.isGit = true
        alert(response.data.output)
    })
}

const sslGenerate = async () => {
    await axios.get(`/projects/${props.project.id}/ssl`).then((response) => {
        alert(response.data)
    })
}

const changeFavorite = async () => {
    await axios.get(`/projects/${props.project.id}/favorite`).then((response) => {
        alert(response.data)
    })
}

const nginxConf = async () => {
    await axios.get(`/projects/${props.project.id}/nginx-conf`)
}

</script>

<template>
    <default>
        <template #title>
            Project Show
        </template>
        <template #headActions>
            <FwbButton color="red" outline :href="'/projects/'+project.id +'/delete'" size="sm"
                       class="flex items-center">
                <span class="icon-[mdi--trash-can] mr-2"></span>
                <span>Delete</span>
            </FwbButton>
            <fwb-tooltip placement="bottom">
                <template #trigger>
                    <fwb-button size="sm" outline class="ml-2">
                        <i class="icon-[mdi--information]"></i>
                    </fwb-button>
                </template>
                <template #content>
                    <div class="w-full flex flex-col gap-2">
                        <FwbAlert border type="danger">
                            Delete project; files and nginx configuration deleted
                        </FwbAlert>
                    </div>
                </template>
            </fwb-tooltip>


        </template>
        <div v-if="isFolder">
            <div
                class="w-full mb-4 bg-white rounded-lg shadow p-4 dark:bg-gray-800 text-gray-900 dark:text-white flex justify-between items-center ">
                <div class="flex gap-2">
                    <Link :href="'/projects/'+project.id+'/show-folder'" title="Open Project folder"
                          class="flex gap-2 items-center text-white border border-white rounded-full p-2 px-4">
                        <span
                            class=" text-white/90  icon-[mdi--folder] text-sm text-center p-1"></span>
                        <span class="hidden lg:block">Open Folder</span>
                    </Link>
                </div>
                <div class="">{{ project.name }}</div>
                <div class="text-sm text-white flex gap-4">

                    <button title="Open Link" type="button"
                            class="flex gap-2 items-center text-white border border-white rounded-full p-2 px-4"
                            @click="openLink">
                        <span
                            class="text-white/90  icon-[mdi--link] text-sm text-center p-1"></span>
                        <span class="hidden lg:block">Open Link</span>
                    </button>

                </div>
            </div>

            <FwbTabs class="mt-4 " v-model="activeTab" variant="underline">
                <FwbTab title="General" name="General">
                    <div class="flex w-full gap-4 mb-4">
                        <FwbButton @click="nginxConf">
                            Nginx
                        </FwbButton>
                    </div>
                    <FwbAlert class="mb-4" type="warning" border v-if="!technologies.isGit">
                        <FwbP size="sm">
                            <span class="icon-[mdi--alert] mr-2"></span>
                            <span> Not version control  </span>
                        </FwbP>
                        <FwbButton color="alternative" @click="addGit">Version Control</FwbButton>
                    </FwbAlert>
                    <FwbAlert class="mb-4" type="warning" border v-if="isEmpty">
                        <FwbP size="sm">
                            <span class="icon-[mdi--alert] mr-2"></span>
                            <span> Project is empty  </span>
                        </FwbP>
                    </FwbAlert>

                    <FwbAlert class="mb-4" type="warning" border v-if="phpVersions.some(version => version.name === project.php_version && !version.isActive )">
                        <FwbP size="sm">
                            <span class="icon-[mdi--alert] mr-2"></span>
                            <span> Not active PHP version  </span>
                        </FwbP>
                        <FwbButton color="alternative" @click="router.get('/php-versions/'+project.php_version+'/start')">Start PHP Version</FwbButton>
                    </FwbAlert>

                </FwbTab>
                <FwbTab title="Composer" :disabled="!technologies.isComposer" name="Composer">
                    <div
                        class="w-full bg-white rounded-lg shadow p-4 dark:bg-gray-800 text-xl font-bold text-gray-900 dark:text-white">
                        <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Require:</h2>
                        <ul class="max-w-md w-full space-y-1 text-gray-500 list-inside dark:text-gray-400 ">
                            <li class="flex items-center text-sm " v-for="(composer,key) in composer.require">
                                <svg class="w-3.5 h-3.5 me-2 text-green-500 dark:text-green-400 flex-shrink-0"
                                     aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                </svg>
                                <div class="">{{ key }} : {{ composer }}</div>
                                <div class="flex gap-2 ml-auto">
                                    <button class="text-xs font-medium text-center leading-none cursor-pointer
                        rounded-full p-2 px-3 flex gap-2 bg-slate-400 text-slate-800"
                                            @click="openGithub(key)">
                                        <span class="icon-[mdi--github]"></span>
                                        <span>Github</span>
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <hr class="my-4">
                        <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Require Dev:</h2>
                        <ul class="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400 ">
                            <li class="flex items-center text-sm " v-for="(composer,key) in composer['require-dev']">
                                <svg class="w-3.5 h-3.5 me-2 text-green-500 dark:text-green-400 flex-shrink-0"
                                     aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                </svg>
                                <div class=""> {{ key }} : {{ composer }}</div>
                                <div class="flex gap-2 ml-auto">
                                    <button class="text-xs font-medium text-center leading-none cursor-pointer
                        rounded-full p-2 px-3 flex gap-2 bg-slate-400 text-slate-800"
                                            @click="openGithub(key)">
                                        <span class="icon-[mdi--github]"></span>
                                        <span>Github</span>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </FwbTab>
                <FwbTab title="Packages" :disabled="!technologies.isNode" name="Packages">
                    <div
                        class="w-full bg-white rounded-lg shadow p-4 dark:bg-gray-800 text-xl font-bold text-gray-900 dark:text-white">
                        <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Dependencies:</h2>
                        <ul class="max-w-md w-full space-y-1 text-gray-500 list-inside dark:text-gray-400 ">
                            <li class="flex items-center text-sm " v-for="(packageInfo,key) in packages.dependencies">
                                <svg class="w-3.5 h-3.5 me-2 text-green-500 dark:text-green-400 flex-shrink-0"
                                     aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                </svg>
                                <div class="">{{ key }} : {{ packageInfo }}</div>
                                <div class="flex gap-2 ml-auto">
                                    <button class="text-xs font-medium text-center leading-none cursor-pointer
                        rounded-full p-2 px-3 flex gap-2 bg-slate-400 text-slate-800"
                                            @click="openGithub(key)">
                                        <span class="icon-[mdi--github]"></span>
                                        <span>Github</span>
                                    </button>
                                </div>
                            </li>
                        </ul>
                        <hr class="my-4">
                        <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Dev Dependencies:</h2>
                        <ul class="max-w-md space-y-1 text-gray-500 list-inside dark:text-gray-400 ">
                            <li class="flex items-center text-sm " v-for="(packageInfo,key) in packages.devDependencies">
                                <svg class="w-3.5 h-3.5 me-2 text-green-500 dark:text-green-400 flex-shrink-0"
                                     aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                     viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5Zm3.707 8.207-4 4a1 1 0 0 1-1.414 0l-2-2a1 1 0 0 1 1.414-1.414L9 10.586l3.293-3.293a1 1 0 0 1 1.414 1.414Z"/>
                                </svg>
                                <div class=""> {{ key }} : {{ packageInfo }}</div>
                                <div class="flex gap-2 ml-auto">
                                    <button class="text-xs font-medium text-center leading-none cursor-pointer
                        rounded-full p-2 px-3 flex gap-2 bg-slate-400 text-slate-800"
                                            @click="openGithub(key)">
                                        <span class="icon-[mdi--github]"></span>
                                        <span>Github</span>
                                    </button>
                                </div>
                            </li>
                        </ul>
                    </div>
                </FwbTab>
                <FwbTab title="Settings" name="Settings">
                    <ProjectSettings :project="project" :projectTypes="projectTypes" :hostname="hostName"
                                     :phpVersions="phpVersions" :nodeVersions="nodeVersions"
                                     :globalPhpVersion="globalPhpVersion" :globalNodeVersion="globalNodeVersion"
                    />
                </FwbTab>
            </FwbTabs>
        </div>
        <div
            class="w-full bg-white rounded-lg shadow p-4 dark:bg-gray-800 text-xl font-bold text-gray-900 dark:text-white"
            v-if="!isFolder">
            {{ project.path }} is a folder and not a project
            <div class="flex gap-2 mt-4">
                <FwbButton color="red" :href="'/projects/'+project.id +'/delete'" size="xs" class="flex items-center">
                    <span class="icon-[mdi--trash-can] mr-2"></span>
                    <span>Delete</span>
                </FwbButton>
            </div>
        </div>
    </default>
</template>
