<script setup>
import {Link} from '@inertiajs/vue3'
import Logo from "@/assets/light.png";
</script>

<template>
        <nav class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
            <div class="px-3 py-3 lg:px-5 lg:pl-3">
                <div class="flex items-center justify-between">
                    <div class="flex items-center justify-start rtl:justify-end">
                        <Link href="/" class="flex ms-2 md:me-24">
                            <img :src="Logo" class="h-8 me-3" alt="Alpzo Logo" />
                        </Link>
                        <div class="dark:text-white ms-2 font-bold">
                            <slot name="title"></slot>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <slot name="headActions"></slot>
                    </div>
                </div>
            </div>
        </nav>
</template>
