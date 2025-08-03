<script setup>

import Menubar from "@/Layouts/menubar.vue";

import {FwbButton, FwbCard, FwbTooltip} from "flowbite-vue"
import {ref} from "vue";

const props =defineProps({
    globalPhpVersion: String,
    globalNodeVersion: String,
    projects:Array
})

const favoriteProjects = ref([])
favoriteProjects.value = props.projects

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
</script>

<template>
    <Menubar title="">
        <div class="flex  flex-col w-full h-full pt-4 gap-4 mt-4">
            <div class="w-full flex justify-between gap-4">

                <FwbCard class="w-full">
                    <div class="flex p-2 justify-between gap-2 items-center">
                        <i class="icon-[mdi--language-php] text-3xl inline-block"></i>
                        <p class="text-gray-500 dark:text-gray-400">{{globalPhpVersion!=='choose'?'v':''}}{{ globalPhpVersion?.split("-")[0] }}</p>
                    </div>
                </FwbCard>
                <FwbCard class="w-full">
                    <div class="flex p-2 justify-between gap-2">
                        <i class="icon-[mdi--nodejs] text-2xl inline-block"></i>
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
    </Menubar>
</template>

<style scoped>

</style>
