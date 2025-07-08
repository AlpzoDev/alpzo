<script setup>

import Menubar from "@/Layouts/menubar.vue";

import {FwbCard} from "flowbite-vue"

defineProps({
    globalPhpVersion: String,
    globalNodeVersion: String,
    version: String
})
</script>

<template>
    <Menubar title="" :version="version">
        <div class="flex  flex-col w-full h-full pt-4 gap-4 mt-4">
            <div class="w-full flex justify-between gap-4">

                <FwbCard class="w-full p-2">
                    <div class="flex px-2 justify-between">
                        <i class="icon-[mdi--language-php] text-2xl"></i>
                        <p class="text-gray-500 dark:text-gray-400">v{{ globalPhpVersion.split("-")[0] }}</p>
                    </div>
                </FwbCard>
                <FwbCard class="w-full p">
                    <div class="flex p-2 justify-between gap-2">
                        <i class="icon-[mdi--nodejs] text-2xl"></i>
                        <p class="text-gray-500 dark:text-gray-400">{{ globalNodeVersion.split("-")[1] }}</p>
                    </div>
                </FwbCard>
            </div>

        </div>
    </Menubar>
</template>

<style scoped>

</style>
