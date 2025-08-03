<script setup>

import Default from "@/Layouts/default.vue";
import Logo from "@/assets/light.png";
import Axios from "axios";

const alpzoPage = () => {
    Axios.post('/links/url', {
        url: 'https://alpzo.com'
    })
}
</script>

<template>
    <default>
        <template #title>
            About
        </template>

        <div class="max-w-md mx-auto">
            <img :src="Logo" alt="Alpzo Logo" class="h-16 mx-auto mb-4">
            <p class="dark:text-white"></p>
            <p class="dark:text-white">Alpzo is a free. PHP web framework that makes it easy to build web applications with PHP.</p>
            <div class="flex justify-end mt-4">
                <!--                <FwbButton color="alternative" @click="alpzoPage">Visit  the Alpzo  Website </FwbButton>-->
            </div>
        </div>

    </default>
</template>

