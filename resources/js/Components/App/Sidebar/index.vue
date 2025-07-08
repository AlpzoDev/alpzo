<script setup>
import {Link} from '@inertiajs/vue3'
import {FwbButton} from "flowbite-vue";
import Axios from "axios";

const links = [
    {
        name: 'Dashboard',
        href: '/',
        icon: 'icon-[mdi--view-dashboard]',
    },
    {
        name: 'PHP Versions',
        href: '/php-versions',
        icon: 'icon-[mdi--language-php]',
    },
    {
        name: 'NodeJS Versions',
        href: '/node-versions',
        icon: 'icon-[mdi--nodejs]',
    },
    {
        name: 'Projects',
        href: '/projects',
        icon: 'icon-[mdi--folder-multiple]',
    },
    {
        name: 'Settings',
        href: '/settings',
        icon: 'icon-[mdi--cog]',
    },
    {
        name: 'About',
        href: '/about',
        icon: 'icon-[mdi--information]',
    },

]

const alpzoPage = () => {
    Axios.post('/links/url', {
        url: 'https://alpzo.com'
    })
}
</script>
<template>
        <aside id="logo-sidebar" class="fixed top-0 left-0 z-40 w-52 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700" aria-label="Sidebar">
            <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800 flex flex-col">
                <ul class="space-y-2 font-medium grow">
                    <li v-for="link in links">
                        <Link :href="link.href" class="flex items-center p-2 text-gray-900 rounded-lg dark:text-white hover:bg-gray-100 dark:hover:bg-gray-700 group">
                            <div :class="link.icon"  class="w-5 h-5 text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"></div>
                            <span class="ms-3">{{ link.name }}</span>
                        </Link>
                    </li>
                </ul>

                <div class="w-full text-sm dark:text-white flex flex-col  gap-2">
                    <span>  &#169; {{ new Date().getFullYear() }} </span>
                    <div class="flex flex-col">
                        <FwbButton size="sm" @click="alpzoPage" class="hover:underline !bg-transparent text-left p-0">
                            Alpzo
                        </FwbButton>
                        All Rights Reserved.
                    </div>

                </div>

            </div>
        </aside>
</template>
