<script setup>

import Terminel from "@/Layouts/terminel.vue";
import {ref} from "vue";



const props = defineProps({
    projects: Array
})
const prompts = ref([])
const prompt = ref('')


</script>

<template>

<Terminel>
    <template #sidebar>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white h-12 flex items-center justify-center border-b border-gray-700">Projects</h2>
        <ul class="max-w-md space-y-1 text-gray-500 list-disc list-inside dark:text-gray-400">
            <li class="flex items-center text-sm p-2 hover:bg-gray-500 hover:text-white cursor-pointer gap-2 " v-for="project in projects">
               <span class="icon-[mdi--folder]"></span>
                {{ project.name }}
            </li>
        </ul>
    </template>
    <template #header></template>

    <template #footer>
        <textarea v-model="prompt" class="w-full min-h-full border-none outline-0 max-h-full border border-gray-700 bg-gray-800 text-white pl-2 pt-2 resize-none text-sm" placeholder="Enter your prompt"></textarea>
    </template>
</Terminel>

</template>
