<script setup>
import {Link} from "@inertiajs/vue3";
import Logo from "@/assets/light.png";
</script>

<template>
    <div class="w-full h-full flex items-center justify-center text-center dark:text-white dark:bg-gray-800 min-h-screen">
        <div class="flex flex-col">
            <img :src="Logo" alt="logo" class="h-20 mx-auto mb-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Access Denied: Explorer</h2>
            <Link href="/windows/main" class="mt-4 text-sm text-gray-500 dark:text-gray-400 hover:underline">Back to App</Link>
        </div>
    </div>
</template>
