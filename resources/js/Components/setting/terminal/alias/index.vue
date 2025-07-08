<script setup>

import {
    FwbButton, FwbInput,
    FwbModal,
    FwbP,
    FwbTable,
    FwbTableBody,
    FwbTableCell, FwbTableHead,
    FwbTableHeadCell,
    FwbTableRow, FwbTextarea, FwbToggle
} from "flowbite-vue";
import {onMounted, ref} from "vue";
import axios from "axios";
import {router, useForm} from "@inertiajs/vue3";
const  alias = ref([])
const isOpen = ref(false)
const isManage = ref(false)

onMounted(() => {
    axios.get('/settings/terminal/aliases').then(response => {
        alias.value = response.data.data
    })
})

const manageLink = ref('')

const form = useForm({
    name: '',
    command: '',
    description: '',
    is_global: false
})

const openManage = (item) => {
    manageLink.value ='settings/terminal/aliases/'+ item
    form.name = item.name
    form.command = item.command
    form.description = item.description
    isManage.value = true
}

const saveAlias = () => {
    form.put(manageLink.value, {
        onSuccess: () => isOpen.value = false,
        data: form
    })
}

</script>

<template>
    <div class="w-full flex justify-between mb-4">
        <FwbP>Alias List </FwbP>
        <FwbButton variant="alternative" size="sm" @click="isOpen = true">Add Alias</FwbButton>
    </div>
    <FwbModal v-if="isOpen" @close="isOpen = false">
        <template #header>
            <FwbP>Add Alias</FwbP>
        </template>
        <template #body>
        <form @submit.prevent="form.post('/settings/terminal/aliases', {onSuccess: () => isOpen = false})">
            <div class="flex flex-col gap-4 w-full">
                <div>
                    <FwbInput v-model="form.name" label="Name" size="sm"/>
                    <span class="text-red-500 text-xs" v-if="form.errors.name">{{ form.errors.name }}</span>
                </div>
                <div>
                    <FwbInput v-model="form.command" label="Command" size="sm"/>
                    <span class="text-red-500 text-xs" v-if="form.errors.command">{{ form.errors.command }}</span>
                </div>
                <div>
                    <FwbTextarea v-model="form.description" label="Description" size="sm"  />
                    <span class="text-red-500 text-xs" v-if="form.errors.description">{{ form.errors.description }}</span>
                </div>
                <div>
                    <FwbToggle v-model="form.is_global" label="Description" size="sm"  />
                    <span class="text-red-500 text-xs" v-if="form.errors.is_global">{{ form.errors.is_global }}</span>
                </div>
                <FwbButton type="submit">Submit</FwbButton>
            </div>
        </form>
        </template>
    </FwbModal>

    <FwbModal v-if="isManage" @close="isManage = false">
        <template #header>
            <FwbP>Edit Alias</FwbP>
        </template>
        <template #body>
            <form @submit.prevent="saveAlias">
                <div class="flex flex-col gap-4 w-full">
                    <div>
                        <FwbInput v-model="form.name" label="Name" size="sm"/>
                        <span class="text-red-500 text-xs" v-if="form.errors.name">{{ form.errors.name }}</span>
                    </div>
                    <div>
                        <FwbInput v-model="form.command" label="Command" size="sm"/>
                        <span class="text-red-500 text-xs" v-if="form.errors.command">{{ form.errors.command }}</span>
                    </div>
                    <div>
                        <FwbTextarea v-model="form.description" label="Description" size="sm"  />
                        <span class="text-red-500 text-xs" v-if="form.errors.description">{{ form.errors.description }}</span>
                    </div>
                    <div>
                        <FwbToggle v-model="form.is_global" label="Description" size="sm"  />
                        <span class="text-red-500 text-xs" v-if="form.errors.is_global">{{ form.errors.is_global }}</span>
                    </div>
                    <FwbButton type="submit">Submit</FwbButton>
                </div>
            </form>
        </template>
    </FwbModal>

    <FwbTable>
        <FwbTableHead>
            <FwbTableHeadCell>Name</FwbTableHeadCell>
            <FwbTableHeadCell>Command</FwbTableHeadCell>
            <FwbTableHeadCell>Description</FwbTableHeadCell>
            <FwbTableHeadCell>ACTIONS</FwbTableHeadCell>
        </FwbTableHead>
        <FwbTableBody>
            <FwbTableRow v-for="item in alias" :key="item.id">
                <FwbTableCell>{{ item.name }}</FwbTableCell>
                <FwbTableCell>{{ item.command }}</FwbTableCell>
                <FwbTableCell>{{ item.description }}</FwbTableCell>
                <FwbTableCell class="flex gap-2 items-center justify-end">
                    <FwbButton color="alternative" size="sm" @click="openManage(item)" >Edit</FwbButton>
                    <FwbButton color="red" size="sm" @click="axios.delete('/settings/terminal/aliases/' + item.id)
                    .then(() => alias.splice(alias.indexOf(item), 1))">Delete</FwbButton>


                </FwbTableCell>
            </FwbTableRow>
        </FwbTableBody>
    </FwbTable>
</template>
