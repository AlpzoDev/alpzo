<script setup lang="ts">
import Item from "@/Components/Global/Table/item.vue";
import Td from "@/Components/Global/Table/td.vue";
import axios from "axios";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    phpVersion: Object,
    checkPhpVersion: Function
})

const isLoading = ref(false)
const installPhpVersion = async (phpVersion) => {
    isLoading.value = true
   await axios.post('/php-versions/install', phpVersion)
        .then((response) => {
            isLoading.value = response.data.install
        })
        .catch(err=>alert(err))
}

Native.on('Native\\Laravel\\Events\\ChildProcess\\MessageReceived', (event) => {
    if (event.alias==='install-php-version-'+props.phpVersion.name) {
        console.log(event.alias + ' received')
        isLoading.value = true
    }
})

Native.on('Native\\Laravel\\Events\\ChildProcess\\ProcessExited', (event) => {
    if (event.alias==='install-php-version-'+props.phpVersion.name) {
        console.log(event.alias + ' exited')
        isLoading.value = false
        router.reload()
    }
})
Native.on("Native\\Laravel\\Events\\ChildProcess\\ProcessSpawned", (event) => {
    if (event.alias === 'install-php-version-'+props.phpVersion.name) {
        console.log(event.alias + ' spawned')
        isLoading.value = true
    }
})
</script>

<template>
    <Item>
        <Td>
            {{ phpVersion.date }}
        </Td>
        <Td>
            {{ phpVersion.name }}
        </Td>
        <Td>
            {{ phpVersion.version }}
        </Td>

        <Td>
            <button :disabled="isLoading" v-if="!checkPhpVersion(phpVersion)" @click="installPhpVersion(phpVersion)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded
                    disabled:cursor-not-allowed disabled:opacity-50">
                {{isLoading ? 'Installing...' : 'Install'}}
            </button>
            <div class="text-green-500 py-1" v-else>Installed</div>
        </Td>
    </Item>

</template>

