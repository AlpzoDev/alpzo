<script setup>
import Navbar from "@/Components/App/Navbar/index.vue";
import Sidebar from "@/Components/App/Sidebar/index.vue";
import {ref} from "vue";

const toat = ref([])
</script>
<template>

        <Navbar>
            <template #title>
                <slot name="title"></slot>
            </template>
            <template #headActions>
                <slot name="headActions"></slot>
            </template>
        </Navbar>
        <Sidebar/>
        <div class="p-4 sm:ml-52 flex flex-col min-h-screen bg-gray-900">
            <div class="p-4 mt-14 grow">
                <slot/>
            </div>
        </div>



</template>
