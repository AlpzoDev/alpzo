<script setup>

import {
    FwbButton,
    FwbFileInput,
    FwbInput,
    FwbModal,
    FwbTableCell,
    FwbTableRow,
    FwbTextarea,
    FwbToggle
} from "flowbite-vue";
import {ref} from "vue";
import {useForm} from "@inertiajs/vue3";

const props = defineProps({
    path: Object
})
const isOpen = ref(false)
const ManageOpen = ref(false)

const form = useForm({
    name: props.path.name,
    description: props.path.description,
    isDefault: props.path.isDefault
})

const openModal = () => {
    isOpen.value = true
}
const closeModal = () => {
    isOpen.value = false
}

const manageOpen = () => {
    ManageOpen.value = true
}

const closeManage = () => {
    ManageOpen.value = false
}


</script>
<template>
    <fwb-table-row>
        <fwb-table-cell>{{ path.name }}</fwb-table-cell>
        <fwb-table-cell>{{ path.path }}</fwb-table-cell>
        <fwb-table-cell >
            <FwbToggle v-model="path.is_default" size="sm" :disabled="path.is_default" @change="form.post('/settings/path/'+path.id+'/default', {onSuccess: closeManage})"/>
        </fwb-table-cell>
        <fwb-table-cell class="flex last:!text-left gap-2">
            <FwbButton color="light" size="sm" @click="manageOpen">Edit</FwbButton>
            <fwb-button color="red" outline size="sm" :disabled="path.is_default" @click="openModal">Delete</fwb-button>
            <FwbModal v-if="isOpen" @close="closeModal">
                <template #header>
                    Are you sure?
                </template>
                <template #body>
                    Are you sure you want to delete this item?
                </template>
                <template #footer>
                    <button class="text-gray-600 mr-7">Cancel</button>
                    <button @click="form.delete('/settings/path/'+path.id+'/delete', {onSuccess: closeModal})" class="text-red-600">Delete</button>
                </template>
            </FwbModal>
            <FwbModal v-if="ManageOpen" @close="closeManage">
                <template #header>
                    Edit Path
                </template>
                <template #body>
                    <form @submit.prevent="form.post('/settings/path/'+path.id+'/update', {onSuccess: closeManage})">
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
        </fwb-table-cell>
    </fwb-table-row>
</template>
