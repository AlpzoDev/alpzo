<script setup>
defineProps({
    tabs: Array,
    activeTab: Number
})
</script>

<template>
    <ol class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0 rtl:space-x-reverse">
        <li class="flex items-center  space-x-2.5 rtl:space-x-reverse"
            v-for="tab in tabs"
            :class="activeTab === tab.no ? 'text-blue-600 dark:text-blue-500' : 'text-gray-500 dark:text-gray-400'">
        <span class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-blue-500">
            {{ tab.no }}
        </span>
            <span>
            <span class="font-medium leading-tight">{{ tab.title }}</span>
            <p class="text-sm">{{ tab.description }}</p>
        </span>
        </li>
    </ol>
</template>


