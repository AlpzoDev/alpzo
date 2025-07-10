<script setup lang="ts">
import Item from "@/Components/Global/Table/item.vue";
import Td from "@/Components/Global/Table/td.vue";
import {ref} from "vue";
import axios from "axios";
import {router} from "@inertiajs/vue3";
const props = defineProps({
    version: Object,
    checkNodeVersion: Function,
})
const isLoading = ref(false)
const installNodeVersion = (phpVersion) => {
    axios.post('/node-versions/install', phpVersion)
        .then((response) => {
            isLoading.value = response.data.install
        })
}


</script>

<template>
    <Item>
        <Td>
            {{version.date}}
        </Td>
        <Td>
            {{version.version}}
        </Td>

        <Td>
            <button :disabled="isLoading" v-if="!checkNodeVersion(version.version)"  @click="installNodeVersion(version)"
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded disabled:cursor-not-allowed disabled:opacity-50">
                {{isLoading ? 'Installing' : 'Install'}}
            </button>
            <div class="text-green-500" v-else>Installed</div>
        </Td>
    </Item>
</template>

<style scoped>

</style>
