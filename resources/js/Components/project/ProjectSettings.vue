<script setup>
import {useForm} from "@inertiajs/vue3";
import {FwbButton, FwbInput, FwbSelect, FwbTextarea} from "flowbite-vue";
import {watch} from "vue";

const props = defineProps({
    project: Object,
    phpVersions: Array,
    nodeVersions: Array,
    projectTypes: Array,
    hostname: String,
})

const form = useForm({
    name: props.project.name,
    description: props.project.description,
    type: props.project.type,
    path: props.project.path,
    url: props.project.url,
    php_version: props.project.php_version,
    node_version: props.project.node_version
})

watch(
    () => form.name,
    () => {
        let name = form.name.trim();
        form.url = name.replace(/\s+/g, '-') + '.' + props.hostname;

        // Başlangıç path'i alınıyor
        let basePath = props.project.path;
        let paths = basePath.split('\\');

        // Yeni path oluşturuluyor
        let newPath = paths.slice(0, paths.length - 1).join('\\');
        newPath += '\\' + name;

        console.log('path', newPath);

        // Path formda güncelleniyor
        form.path = newPath;
    }
);

</script>

<template>
    <div class="w-full bg-white rounded-lg shadow p-4 dark:bg-gray-800 text-xl font-bold text-gray-900 dark:text-white">

        <form @submit.prevent="form.post(`/projects/${project.id}/update`)">
            <div class="flex flex-col gap-4 w-full">
                <div>
                    <FwbInput v-model="form.name" label="Name *" size="sm" type="text" required :validation-status="form.errors.name?'error':''"/>
                    <span class="text-red-500 text-xs" v-if="form.errors.name">{{ form.errors.name }}</span>
                </div>
                <div>
                    <FwbTextarea v-model="form.description" label="Description" size="sm"/>
                    <span class="text-red-500 text-xs" v-if="form.errors.description">{{ form.errors.description }}</span>
                </div>
                <div>
                    <FwbInput v-model="form.path" label="Path *" size="sm" type="text" required disabled />
                    <span class="text-red-500 text-xs" v-if="form.errors.path">{{ form.errors.path }}</span>
                </div>
                <div>
                    <FwbInput v-model="form.url" label="Url *" size="sm" type="text" required disabled/>
                    <span class="text-red-500 text-xs" v-if="form.errors.url">{{ form.errors.url }}</span>
                </div>
                <div>
                    <FwbSelect v-model="form.type" label="Type *" size="sm" :options="projectTypes" />
                    <span class="text-red-500 text-xs" v-if="form.errors.type">{{ form.errors.type }}</span>
                </div>
                <div>
                    <FwbSelect v-model="form.php_version" label="Php Version *" size="sm" :options="phpVersions" />
                    <span class="text-red-500 text-xs" v-if="form.errors.php_version">{{ form.errors.php_version }}</span>
                </div>
                <div>
                    <FwbSelect v-model="form.node_version" label="Node Version *" size="sm" :options="nodeVersions" />
                    <span class="text-red-500 text-xs" v-if="form.errors.node_version">{{ form.errors.node_version }}</span>
                </div>
            </div>
            <div class="flex justify-end mt-4">
                <FwbButton type="submit" size="sm" :disabled="form.processing">Update</FwbButton>
            </div>
        </form>
    </div>
</template>


