<script setup>

import {FwbButton, FwbButtonGroup, FwbInput, FwbModal, FwbSelect, FwbTextarea, FwbToggle} from "flowbite-vue";
import {useForm} from "@inertiajs/vue3";
import {ref, onMounted} from "vue";
import axios from "axios";

const props = defineProps({
    project: Object,
    globalPhpVersion: String,
    phpVersions: Array,
    nodeVersions: Array,
    globalNodeVersion: String,
})

const commands = ref([])
const currentCommandIndex = ref(0)
const isMenu = ref(false)


const aliases = ref([])
const aliasCreate = ref(false)

const aliasForm = useForm({
    name: '',
    command: '',
    description: '',
    is_global: false
})


const form = useForm({
    command: '',
    php: props.globalPhpVersion,
    node: props.globalNodeVersion
})
const save = async () => {
    console.log('form', form, props.project.id)
    await axios.post('/projects/' + props.project.id + '/terminal', form).then(response => {
        commands.value.push(response.data)
        form.command = ''
        currentCommandIndex.value = commands.value.length
    })
}

const exCommand = () => {
    if (commands.value.length === 0) {
        return
    }
    currentCommandIndex.value = (currentCommandIndex.value + 1) % commands.value.length
    form.command = commands.value[currentCommandIndex.value].command
}
const nextCommand = () => {
    if (commands.value.length === 0) {
        return
    }
    currentCommandIndex.value = (currentCommandIndex.value - 1) % commands.value.length
    form.command = commands.value[currentCommandIndex.value].command
}
const changeCommand = (command) => {
    form.command = command;
    isMenu.value = false;
}

function getAlias() {
    axios.get('/settings/terminal/aliases').then(response => {
        aliases.value = response.data.data
    })
}

onMounted(() => {
    getAlias()
})

const saveAlias = () => {
    axios.post('/settings/terminal/aliases', aliasForm).then(response => {
        aliasCreate.value = false
        aliases.value.push(response.data)
        aliasForm.reset()
    })
}

</script>

<template>
    <div class="w-full h-full text-white ">
        <div class="w-full">
            <div class="w-full flex items-center justify-between p-4">
                <div class="flex items-center gap-2">
                    <span class="icon-[mdi--terminal] text-2xl"></span>
                    <span class="font-bold text-lg">Terminal</span>
                </div>
                <div class="flex items-center gap-4">
                    <FwbSelect label="PHP Version" :options="phpVersions" size="sm" v-model="form.php"/>
                    <FwbSelect label="Node Version" :options="nodeVersions" size="sm" v-model="form.node"/>
                </div>
            </div>
        </div>
        <div
            class="w-full h-96 flex flex-col flex-1 dark:text-white dark:bg-gray-800 overflow-y-auto p-4 rounded-md">

            <h2 class="text-2xl font-bold" v-if="commands.length === 0">Terminal</h2>
            <p class="text-gray-500" v-if="commands.length === 0">Type your command and press enter</p>
            <template v-if="commands" v-for="command in commands">
                <div :class="{
                    'w-ful flex flex-col gap-2 mb-2 bg-gray-900 p-4 rounded-md border-t': true,
                    'border-blue-600': command.command === form.command,
                    'border-red-600': !command.success,
                    'border-green-600': command.success,
                }">
                    <p :class="{
                        'text-blue-400': command.command === form.command,
                    }">{{ project.path }} > {{ command.command }}</p>
                    <pre class="p-2 bg-gray-800 text-white rounded-md text-xs xl:text-sm !select-text">{{
                            command.output
                        }}</pre>
                </div>
            </template>

        </div>
        <div class="w-full p-4">
            <form @submit.prevent="save" class="w-full flex">
                <FwbInput v-model="form.command"
                          type="text" required
                          @keyup.ctrl.up="exCommand()"
                          @keyup.ctrl.down="nextCommand()"
                          @keyup.esc="form.command = ''"
                          @keyup.ctrl.delete="commands = []"
                          @contextmenu.prevent="isMenu = true"
                          placeholder="Enter command" class="w-full"/>
                <FwbButton variant="alternative" type="submit">
                    <span class="icon-[mdi--send] text-lg -rotate-45"></span>
                </FwbButton>
            </form>
            <FwbModal position="center-end" v-if="isMenu" @close="isMenu = false">
                <template #header>
                    <FwbButton outline variant="alternative" size="xs" type="button" @click="getAlias"><span class="icon-[mdi--refresh]"></span></FwbButton>
                    <h3 class="text-base font-semibold text-gray-900 dark:text-white">Alias</h3>
                </template>
                <template #body>
                    <div class="w-full flex flex-col gap-2 h-[calc(100vh-15rem)] overflow-y-auto">
                        <div class="w-full flex flex-col gap-2 text-sm dark:text-white border border-gray-200 dark:bg-gray-800
                             rounded-md p-4" v-for="alias in aliases" @click="changeCommand(alias.command)">
                            <h2 class="text-lg font-semibold">{{ alias.name }}</h2>
                            <p>{{ alias.command }}</p>
                            <p>{{ alias.description }}</p>
                        </div>
                    </div>

                </template>
                <template #footer>
                    <FwbButton outline variant="alternative" size="xs" type="button" @click="aliasCreate = !aliasCreate">{{ aliasCreate ? 'Close' : 'Create'}}</FwbButton>
                    <div class="w-full flex flex-col gap-2 bg-gray-800 p-4" v-if="aliasCreate">
                        <FwbInput v-model="aliasForm.name" placeholder="Alias Name" class="w-full" size="sm" required/>
                        <span v-if="aliasForm.errors.name" class="text-red-500 text-xs">{{
                                aliasForm.errors.name
                            }}</span>
                        <FwbInput v-model="aliasForm.command" placeholder="Command" class="w-full"/>
                        <span v-if="aliasForm.errors.command" class="text-red-500 text-xs">{{
                                aliasForm.errors.command
                            }}</span>
                        <FwbTextarea v-model="aliasForm.description" placeholder="Description"
                                     class="w-full resize-none h-12"/>
                        <span v-if="aliasForm.errors.description"
                              class="text-red-500 text-xs">{{ aliasForm.errors.description }}</span>
                        <FwbToggle v-model="aliasForm.is_global" label="Global"/>
                        <span v-if="aliasForm.errors.is_global"
                              class="text-red-500 text-xs">{{ aliasForm.errors.is_global }}</span>

                        <FwbButton type="button" @click="saveAlias()">
                            Save
                        </FwbButton>
                    </div>
                </template>
            </FwbModal>
        </div>
    </div>
</template>
