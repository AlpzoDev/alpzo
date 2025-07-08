<script setup>
defineProps({
    title: String,
    description: String,
    columns: Array,
})
</script>

<template>
    <div class="relative overflow-x-auto">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 bg-gray-50 dark:bg-gray-700 dark:text-white"
            v-if="columns.length>0">
            <tr>
                <th scope="col" class="px-6 py-4" v-for="column in columns">
                    {{column.name}}
                </th>
            </tr>
            </thead>
            <tbody>
            <slot />
            </tbody>
        </table>
    </div>
</template>
