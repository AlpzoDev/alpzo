<script setup>
</script>

<template>
<div class="flex w-screen h-screen bg-gray-900">
    <div class="w-52 bg-gray-800">
        <slot name="sidebar" />
    </div>
    <div class="flex-1 flex flex-col">
        <div class="w-full h-12 bg-gray-800 border-l border-gray-700">
            <slot name="header" />
        </div>
        <div class="grow flex-1">
            <slot />
        </div>
        <div class="w-full h-12 bg-gray-800 bottom-0 border-l border-gray-700 flex items-center">
            <slot name="footer" />
        </div>
    </div>
</div>
</template>

