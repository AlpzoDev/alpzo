<script setup>
import Menubar from "@/Layouts/menubar.vue";
import { FwbCard, FwbButton, FwbBadge, FwbSpinner, FwbTooltip } from "flowbite-vue";
import { ref, computed, onMounted } from "vue";
import axios from "axios";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    phpVersions: Array,
    installedVersions: Array,
    globalPhpVersion: String
})

const isLoading = ref(false)
const selectedVersion = ref(null)

const installedPhpVersions = computed(() => {
    return props.phpVersions?.filter(version =>
        props.installedVersions?.some(installed => installed.version === version.version)
    ) || []
})

const popularVersions = computed(() => {
    return props.phpVersions?.filter(version =>
        ['8.3', '8.2', '8.1', '8.0'].some(popular => version.version.startsWith(popular))
    ).slice(0, 4) || []
})

const activeVersion = computed(() => {
    return installedPhpVersions.value.find(v => v.version === props.globalPhpVersion)
})

const quickInstall = async (version) => {
    isLoading.value = true
    selectedVersion.value = version.version

    try {
        await axios.post('/php-versions/install', version)
        setTimeout(() => {
            router.reload()
        }, 1000)
    } catch (err) {
        alert('Yükleme sırasında hata oluştu')
    } finally {
        isLoading.value = false
        selectedVersion.value = null
    }
}

const setActive = async (version) => {
    try {
        await axios.post('/php-versions/set-default', { version: version.version })
        router.reload()
    } catch (err) {
        alert('Varsayılan sürüm ayarlanırken hata oluştu')
    }
}

const openPhpManager = () => {
    router.visit('/php-versions')
}
</script>

<template>
    <Menubar title="PHP Manager" :version="globalPhpVersion || 'Yok'">
        <div class="w-full h-full flex flex-col gap-4 py-4">

            <!-- Aktif PHP Sürümü -->
            <div class="w-full">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <i class="icon-[mdi--php] text-blue-500"></i>
                    Aktif Sürüm
                </h2>
                <FwbCard v-if="activeVersion" class="w-full p-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                PHP
                            </div>
                            <div>
                                <div class="font-semibold text-sm text-gray-900 dark:text-white">
                                    {{ activeVersion.version }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Aktif
                                </div>
                            </div>
                        </div>
                        <FwbBadge color="green" size="sm">
                            <i class="icon-[mdi--check] mr-1"></i>
                            CLI
                        </FwbBadge>
                    </div>
                </FwbCard>
                <FwbCard v-else class="w-full p-3 border-dashed border-2">
                    <div class="text-center text-gray-500 dark:text-gray-400">
                        <i class="icon-[mdi--alert-circle] text-2xl mb-2"></i>
                        <div class="text-sm">Aktif PHP sürümü yok</div>
                    </div>
                </FwbCard>
            </div>

            <!-- Yüklü Sürümler -->
            <div class="w-full">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <i class="icon-[mdi--download] text-green-500"></i>
                    Yüklü Sürümler ({{ installedPhpVersions.length }})
                </h2>
                <div class="w-full flex flex-col gap-2 max-h-32 overflow-y-auto">
                    <FwbCard v-for="version in installedPhpVersions" :key="version.version"
                             class="w-full p-2 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors"
                             @click="setActive(version)">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 bg-blue-500 rounded flex items-center justify-center text-white text-xs font-bold">
                                    {{ version.version.split('.')[0] }}
                                </div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ version.version }}
                                </div>
                            </div>
                            <div class="flex items-center gap-1">
                                <FwbBadge v-if="version.version === globalPhpVersion"
                                         color="green" size="xs">
                                    Aktif
                                </FwbBadge>
                                <i class="icon-[mdi--chevron-right] text-gray-400"></i>
                            </div>
                        </div>
                    </FwbCard>

                    <div v-if="installedPhpVersions.length === 0"
                         class="text-center text-gray-400 dark:text-gray-500 py-4">
                        <i class="icon-[mdi--package-variant-closed] text-xl mb-1"></i>
                        <div class="text-xs">Yüklü sürüm yok</div>
                    </div>
                </div>
            </div>

            <!-- Hızlı Yükleme -->
            <div class="w-full">
                <h2 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                    <i class="icon-[mdi--flash] text-orange-500"></i>
                    Hızlı Yükleme
                </h2>
                <div class="w-full flex flex-col gap-2">
                    <div v-for="version in popularVersions" :key="version.version"
                         class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center gap-2">
                            <div class="text-sm font-medium text-gray-900 dark:text-white">
                                PHP {{ version.version }}
                            </div>
                        </div>
                        <FwbButton v-if="!installedPhpVersions.some(v => v.version === version.version)"
                                   @click="quickInstall(version)"
                                   :disabled="isLoading && selectedVersion === version.version"
                                   color="blue" size="xs">
                            <FwbSpinner v-if="isLoading && selectedVersion === version.version"
                                       size="3" class="mr-1" />
                            <i v-else class="icon-[mdi--download] mr-1"></i>
                            {{ isLoading && selectedVersion === version.version ? 'Yükleniyor...' : 'Yükle' }}
                        </FwbButton>
                        <FwbBadge v-else color="green" size="xs">
                            <i class="icon-[mdi--check] mr-1"></i>
                            Yüklü
                        </FwbBadge>
                    </div>
                </div>
            </div>

            <!-- Aksiyon Butonları -->
            <div class="w-full mt-auto">
                <FwbButton @click="openPhpManager"
                           class="w-full mb-2"
                           color="purple"
                           size="sm">
                    <i class="icon-[mdi--cog] mr-2"></i>
                    PHP Yöneticisi
                </FwbButton>

                <div class="grid grid-cols-2 gap-2">
                    <FwbButton @click="router.reload()"
                               color="alternative"
                               size="xs"
                               outline>
                        <i class="icon-[mdi--refresh] mr-1"></i>
                        Yenile
                    </FwbButton>
                    <FwbButton @click="router.visit('/settings')"
                               color="alternative"
                               size="xs"
                               outline>
                        <i class="icon-[mdi--settings] mr-1"></i>
                        Ayarlar
                    </FwbButton>
                </div>
            </div>

            <!-- Sistem Bilgisi -->
            <div class="w-full mt-2 p-2 bg-gray-50 dark:bg-gray-800 rounded-lg">
                <div class="text-xs text-gray-500 dark:text-gray-400 text-center">
                    <div class="flex items-center justify-center gap-1 mb-1">
                        <i class="icon-[mdi--information]"></i>
                        Sistem Durumu
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-[10px]">
                        <div>Toplam: {{ phpVersions?.length || 0 }}</div>
                        <div>Yüklü: {{ installedPhpVersions.length }}</div>
                    </div>
                </div>
            </div>
        </div>
    </Menubar>
</template>

<style scoped>
.max-h-32 {
    max-height: 8rem;
}

::-webkit-scrollbar {
    width: 4px;
}

::-webkit-scrollbar-track {
    background: transparent;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 2px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

.dark ::-webkit-scrollbar-thumb {
    background: #4a5568;
}

.dark ::-webkit-scrollbar-thumb:hover {
    background: #2d3748;
}
</style>
