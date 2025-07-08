<script setup>

import Default from "@/Layouts/default.vue";
defineProps({
    childProcess: Array
})
</script>

<template>
<default>
    <template #title>
        Help
    </template>

    <pre class="text-white">
        {{ childProcess }}
    </pre>
</default>
</template>

<style scoped>

</style>
