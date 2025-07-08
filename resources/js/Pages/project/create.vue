<script setup>

import Default from "@/Layouts/default.vue";
import {useForm} from "@inertiajs/vue3";
import {ref, watch} from "vue";
import {FwbButton, FwbInput, FwbSelect,} from "flowbite-vue";

const props = defineProps({
    phpVersions: Array,
    globalPhpVersion: String,
    nodeVersions: Array,
    globalNodeVersion: String,
    paths: Array,
    defaultPath: String,
    hostName: String,
    projectTypes: Array,
})
const activeTab = ref(1)

const pathBase = ref(props.defaultPath)

const project = useForm({
    name: '',
    version: '',
    php_version: props.globalPhpVersion,
    path: '',
    url: '',
    is_secure: false,
    type: 'blank',
    node_version: props.globalNodeVersion,
    extra: {
        laravel_version: '',
    }
})

// Utility fonksiyonu - component dışında tanımlayın
function transliterate(text) {
    const charMap = {
        'ç': 'c', 'Ç': 'C',
        'ğ': 'g', 'Ğ': 'G',
        'ı': 'i', 'İ': 'I',
        'ö': 'o', 'Ö': 'O',
        'ş': 's', 'Ş': 'S',
        'ü': 'u', 'Ü': 'U',
        'â': 'a', 'Â': 'A',
        'î': 'i', 'Î': 'I',
        'û': 'u', 'Û': 'U'
    }

    return text.replace(/[çÇğĞıİöÖşŞüÜâÂîÎûÛ]/g, match => charMap[match] || match)
}

function createSlug(text) {
    return transliterate(text)
        .trim()                         // Başındaki ve sonundaki boşlukları temizle
        .replace(/\s+/g, '-')           // Boşlukları tire ile değiştir
        .replace(/[^a-zA-Z0-9-]/g, '')  // Sadece harf, rakam ve tire bırak
        .toLowerCase()                  // Küçük harfe çevir
        .replace(/-+/g, '-')            // Çoklu tireleri tek tireye çevir
        .replace(/^-|-$/g, '')          // Başındaki ve sonundaki tireleri kaldır
}

// Watch fonksiyonu
watch(() => project.name, () => {
    const originalName = project.name
    const slugName = createSlug(originalName)

    project.name = slugName
    project.path = pathBase.value + slugName

    if (project.name.length > 0) {
        project.url = project.name + '.' + props.hostName
    } else {
        project.url = ''
    }
})

watch(() => pathBase.value, () => {
    project.path = pathBase.value + project.name
})

</script>

<template>
    <default>
        <template #title>
            Project Create
        </template>
        <form @submit.prevent="project.post('/projects')" class="w-full">

                        <div class="w-full  gap-4">
                            <div class="my-4">
                                <FwbSelect v-model="project.type" label="Project Type" :options="projectTypes"
                                           size="sm"/>
                                <span class="text-red-500 text-xs" v-if="project.errors.type">{{project.errors.type }}</span>
                            </div>
                            <div>
                                <FwbInput v-model="project.name" label="Project Name" size="sm"/>
                                <span class="text-red-500 text-xs" v-if="project.errors.name">{{project.errors.name }}</span>
                            </div>
                            <div class="flex gap-4 mt-4">
                                <div class="w-full">
                                    <FwbSelect v-model="project.php_version" label="PHP Version" :options="phpVersions"
                                               size="sm"/>
                                    <span class="text-red-500 text-xs"
                                          v-if="project.errors.php_version">{{ project.errors.php_version }}</span>
                                </div>
                                <div class="w-full">
                                    <FwbSelect v-model="project.node_version" label="Node Version"
                                               :options="nodeVersions" size="sm"/>
                                    <span class="text-red-500 text-xs"
                                          v-if="project.errors.node_version">{{ project.errors.node_version }}</span>
                                </div>

                            </div>
                            <div class=" mt-4">
                                <FwbSelect v-model="pathBase" label="Path" :options="paths" size="sm"/>
                                <span class="text-red-500 text-xs" v-if="project.errors.path">{{project.errors.path }}</span>
                                <span class="text-indigo-500 text-xs">{{ project.path }}</span>
                            </div>

                            <div class=" mt-4">
                                <FwbInput v-model="project.url" label="Project URL" size="sm"/>
                                <span class="text-red-500 text-xs" v-if="project.errors.url">{{project.errors.url }}</span>
                            </div>

                        </div>

                        <div class="w-full flex items-center justify-end gap-4 mt-4">
                            <FwbButton type="submit" :disabled="project.processing">Create</FwbButton>
                        </div>
        </form>
    </default>
</template>
