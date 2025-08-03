<script setup>

import Menubar from "@/Layouts/menubar.vue";

import {FwbButton, FwbCard, FwbTooltip, FwbModal, FwbBadge, FwbSpinner} from "flowbite-vue"
import {ref} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";

const props =defineProps({
    globalPhpVersion: String,
    globalNodeVersion: String,
    projects: Array,
    installedPhpVersions: Array,
    phpVersions: Array
})

const favoriteProjects = ref([])
favoriteProjects.value = props.projects

// PHP Modal State
const showPhpModal = ref(false)
const isChangingPhp = ref(false)
const selectedPhpVersion = ref(null)

Native.on("App\\Events\\Project\\ProjectFavoriteEvent",function (project){
    console.log(project)
    favoriteProjects.value.push(project)
})
Native.on("App\\Events\\Project\\ProjectFavoriteRemoveEvent",function (project){
    console.log(project)
    favoriteProjects.value.filter(project)

})

const visit = (project)=>{
    axios.post(`/links/url`, {
       url: 'http://'+project.url
    })
}

const showFolder = (showFolder) =>{
    axios.get('/projects/'+showFolder.id+'/show-folder').then((response) => {

    }).catch((error) => {
        alert(error)
    })
}

// PHP Sürüm Yönetimi Fonksiyonları
const openPhpModal = () => {
    showPhpModal.value = true
}

const closePhpModal = () => {
    showPhpModal.value = false
    isChangingPhp.value = false
    selectedPhpVersion.value = null
}

const changePhpVersion = async (version) => {
    isChangingPhp.value = true
    selectedPhpVersion.value = version.version

    try {
        await axios.post('/php-versions/set-default', { version: version.version })
        router.reload()
        closePhpModal()
    } catch (err) {
        alert('PHP sürümü değiştirilemedi: ' + err.message)
    } finally {
        isChangingPhp.value = false
        selectedPhpVersion.value = null
    }
}

const isActivePhpVersion = (version) => {
    return props.globalPhpVersion === version.version
}

const openPhpManager = () => {
    router.visit('/php-versions')
}
</script>

<template>
    <Menubar title="">
        <div class="flex  flex-col w-full h-full pt-4 gap-4 mt-4">
            <div class="w-full flex justify-between gap-4">

                <!-- PHP Version Card - Clickable -->
                <FwbCard class="w-full cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors" @click="openPhpModal">
                    <div class="flex p-2 justify-between gap-2 items-center">
                        <i class="icon-[mdi--language-php] text-3xl inline-block text-blue-500"></i>
                        <div class="text-right">
                            <p class="text-gray-900 dark:text-white font-medium">
                                {{globalPhpVersion!=='choose'?'v':''}}{{ globalPhpVersion?.split("-")[0] || 'Seç' }}
                            </p>
                            <p class="text-xs text-gray-400">Tıkla değiştir</p>
                        </div>
                    </div>
                </FwbCard>

                <!-- Node Version Card -->
                <FwbCard class="w-full">
                    <div class="flex p-2 justify-between gap-2 items-center">
                        <i class="icon-[mdi--nodejs] text-2xl inline-block text-green-500"></i>
                        <p class="text-gray-500 dark:text-gray-400">{{ globalNodeVersion?.split("-")[1]??"choose" }}</p>
                    </div>
                </FwbCard>
            </div>
            <div class="w-full items-center flex ">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white pl-2">Favorite Projects</h2>
            </div>
            <div class="w-full flex flex-col gap-2">
                <div class="w-full flex flex-col gap-2">

                    <FwbCard  v-for="project in favoriteProjects" :key="project.id"  class="w-full">
                        <div class="flex p-2 justify-between gap-2">
                            <h2 class="text-lg font-semibold text-gray-900 pl-1 dark:text-white line-clamp-1">{{project.name}}</h2>
                            <div class="flex items-center gap-2">
                                <FwbButton @click="visit(project)" color="green" size="xs" outline tooltip="Visit">
                                    <i class="icon-[mdi--arrow-top-right]"></i>
                                </FwbButton>
                                <fwb-button @click="showFolder(project)" color="dark" size="xs" outline>
                                <i class="icon-[mdi--folder]"></i>
                            </fwb-button>
                            </div>
                        </div>
                    </FwbCard>
                </div>
            </div>

        </div>

        <!-- PHP Sürüm Seçici Modal -->
        <FwbModal v-if="showPhpModal" @close="closePhpModal" size="lg">
            <template #header>
                <div class="flex items-center gap-3">
                    <i class="icon-[mdi--language-php] text-2xl text-blue-500"></i>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                        PHP Sürümü Seç
                    </h3>
                </div>
            </template>

            <template #body>
                <div class="space-y-4">
                    <!-- Aktif Sürüm Bilgisi -->
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <div class="flex items-center gap-2 mb-2">
                            <i class="icon-[mdi--information] text-blue-500"></i>
                            <span class="font-medium text-blue-900 dark:text-blue-100">Şu an aktif:</span>
                        </div>
                        <p class="text-blue-700 dark:text-blue-300">
                            PHP {{ globalPhpVersion || 'Hiçbiri' }}
                        </p>
                    </div>

                    <!-- Yüklü PHP Sürümleri -->
                    <div v-if="installedPhpVersions?.length > 0">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                            <i class="icon-[mdi--download] text-green-500"></i>
                            Yüklü PHP Sürümleri
                        </h4>
                        <div class="space-y-2">
                            <div
                                v-for="version in installedPhpVersions"
                                :key="version.version"
                                class="flex items-center justify-between p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                                :class="{ 'ring-2 ring-blue-500 bg-blue-50 dark:bg-blue-900/20': isActivePhpVersion(version) }"
                                @click="changePhpVersion(version)"
                            >
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold text-sm">
                                        PHP
                                    </div>
                                    <div>
                                        <div class="font-medium text-gray-900 dark:text-white">
                                            PHP {{ version.version }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ version.date || version.name }}
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2">
                                    <FwbSpinner v-if="isChangingPhp && selectedPhpVersion === version.version" size="4" />
                                    <FwbBadge v-else-if="isActivePhpVersion(version)" color="green" size="sm">
                                        <i class="icon-[mdi--check] mr-1"></i>
                                        Aktif
                                    </FwbBadge>
                                    <i v-else class="icon-[mdi--chevron-right] text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Eğer hiç yüklü sürüm yoksa -->
                    <div v-else class="text-center py-8">
                        <i class="icon-[mdi--package-variant-closed] text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">
                            Yüklü PHP Sürümü Yok
                        </h4>
                    </div>
                </div>
            </template>

            <template #footer>
                <div class="flex justify-between w-full">
                    <FwbButton @click="openPhpManager" color="alternative" outline>
                        <i class="icon-[mdi--cog] mr-2"></i>
                        PHP Yöneticisi
                    </FwbButton>
                    <FwbButton @click="closePhpModal" color="alternative">
                        Kapat
                    </FwbButton>
                </div>
            </template>
        </FwbModal>
    </Menubar>
</template>

<style scoped>

</style>
