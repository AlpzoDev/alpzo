<script setup>

import {
    FwbButton,
    FwbFileInput,
    FwbModal,
    FwbP,
    FwbTable,
    FwbTableBody,
    FwbTableHead,
    FwbTableHeadCell,
    FwbInput, FwbTextarea
} from "flowbite-vue";
import {ref} from "vue";
import PathItem from "@/Components/setting/path/item.vue";
import {router, useForm} from "@inertiajs/vue3";

const props = defineProps({
    paths: Array
})

const form = useForm({
    name: '',
    path: '',
    description: '',
    is_default: false
})

const isCreate = ref(false)
const openCreate = () => {
    isCreate.value = true
}
const closeCreate = () => {
    isCreate.value = false
    form.reset()
}
</script>

<template>
    <div class="w-full flex flex-col  gap-4">
        <div class="w-full p-3 flex items-center justify-between">
            <h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Path List</h2>
            <FwbButton color="alternative" @click="openCreate">Add Path</FwbButton>
            <FwbModal v-if="isCreate" @close="closeCreate">
                <template #header>
                    <FwbP>Add Path</FwbP>
                </template>
                <template #body>
                    <form @submit.prevent="form.post('/settings/path/create', {onSuccess: closeCreate})">
                        <div class="flex flex-col gap-4 w-full">

                            <div>
                                <FwbInput v-model="form.name" label="Name"/>
                                <span class="text-red-500 text-xs" v-if="form.errors.name">{{ form.errors.name }}</span>
                            </div>
                            <div>
                                <FwbTextarea v-model="form.description" label="Description"  />
                                <span class="text-red-500 text-xs" v-if="form.errors.description">{{ form.errors.description }}</span>
                            </div>
                            <FwbButton type="submit">Submit</FwbButton>
                        </div>
                    </form>
                </template>
            </FwbModal>
        </div>
        <fwb-table class="w-full">
            <fwb-table-head>
                <fwb-table-head-cell>Name</fwb-table-head-cell>
                <fwb-table-head-cell class="w-full">Path</fwb-table-head-cell>
                <fwb-table-head-cell>Default</fwb-table-head-cell>
                <fwb-table-head-cell>Actions</fwb-table-head-cell>
            </fwb-table-head>
            <fwb-table-body>
                <PathItem v-for="(path, index) in paths" :path="path" :key="index" />
            </fwb-table-body>
        </fwb-table>
    </div>
</template>
