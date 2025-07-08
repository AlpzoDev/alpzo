<script setup>
import Logo from "@/assets/light.png";
import {Link} from "@inertiajs/vue3";

defineProps({
    title: {
        type: String,
        default: 'Dashboard'
    },
    version: {
        type: String,
        required: true
    }
})
</script>

<template>
    <div class="w-full h-screen rounded-xl flex flex-col items-center dark:text-white p-4 dark:bg-gray-800 border border-gray-600">


        <div class="flex justify-between w-full items-start ">
            <Link href="/windows/main">
                <img :src="Logo" alt="logo" class="h-6">
            </Link>
            <h2 class="text-lg ">{{ title }}</h2>
            <h3 class="text-sm">{{ version }}</h3>
        </div>
        <div class="grow w-full flex-1">
            <slot/>
        </div>

    </div>
</template>
