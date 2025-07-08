<script setup>

import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    service: Object
})


</script>

<template>
    <div>
        <pre class="text-white">
            {{ service }}
        </pre>
    </div>
</template>

