<script setup xmlns="http://www.w3.org/1999/html">
import {useForm} from "@inertiajs/vue3";
import {FwbCard, FwbCheckbox} from "flowbite-vue";
import {ref} from "vue";

defineProps({
    git: String,
    composer: String,
    allPhpVersions: Array,
    allNodeVersions: Array,
})

const form = useForm({
    git: false,
    composer: false,
    php: [],
    node: [],
})
const loading = ref(false);

</script>

<template>
    <div class="w-full min-h-screen flex flex-col dark:bg-gray-800 dark:text-white p-4">
        <div class="w-full  flex items-center py-4">
            <h2 class="dark:text-white font-bold text-center w-full text-3xl">Installer</h2>
        </div>
        <form @submit.prevent="form.post('/installer', {onStart: () => loading = true})" class="w-full flex flex-col gap-4" v-if="!loading">
            <div class="w-full flex flex-col gap-4">
                <div class="w-full flex flex-col gap-2">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Git</h2>
                    <p class="text-gray-500 dark:text-gray-400">Git is a distributed version control system.</p>
                </div>
                <div class="w-full flex flex-col gap-2">
                    <FwbCheckbox v-model="form.git" id="git" name="git" value="true" label="Install Git" v-if="!git"/>
                    <span v-else class="text-green-500"> <span class="icon-[mdi--check-circle]"></span> Git is already installed : {{
                            git
                        }}</span>
                </div>
            </div>
            <div class="w-full flex flex-col gap-4">
                <div class="w-full flex flex-col gap-2">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Composer</h2>
                    <p class="text-gray-500 dark:text-gray-400">Composer is a dependency manager for PHP.</p>
                </div>
                <div class="w-full flex flex-col gap-2">
                    <FwbCheckbox v-model="form.composer" id="composer" name="composer" value="true"
                                 label="Install Composer" v-if="!composer"/>
                    <span v-else class="text-green-500"> <span class="icon-[mdi--check-circle]"></span> Composer is already installed : {{
                            composer
                        }}</span>
                </div>
            </div>
            <div class="w-full flex flex-col gap-4">
                <div class="w-full flex flex-col gap-2">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">PHP</h2>
                    <p class="text-gray-500 dark:text-gray-400">PHP is a server-side scripting language.</p>
                </div>
                <div class="w-full flex justify-between flex-wrap gap-4">
                    <div class="" v-for="version in allPhpVersions" :key="version">
                        <label class="flex items-center gap-2 cursor-pointer dark:text-white text-gray-900"
                               :for="version.name">
                            <input :id="version.name" type="checkbox" v-model="form.php" :value="version"
                                   class=" w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"/>
                            {{ version.name }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="w-full flex flex-col gap-4 mt-4">
                <div class="w-full flex flex-col gap-2">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Node</h2>
                    <p class="text-gray-500 dark:text-white">Node.js is a JavaScript runtime environment.</p>
                </div>
                <div class="w-full flex  justify-between flex-wrap gap-4 items-center">
                    <div class="" v-for="version in allNodeVersions" :key="version">
                        <label class="flex items-center gap-2 cursor-pointer dark:text-white text-gray-900"
                               :for="version.value">
                            <input :id="version.value" type="checkbox" v-model="form.node" :value="version"
                                   class=" w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"/>
                            {{ version.version }}
                        </label>
                    </div>

                </div>
            </div>
            <div class="w-full flex flex-col gap-4">
                <button class="w-full bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 border border-indigo-700 rounded
                        focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 max-w-sm mx-auto">
                    Install
                </button>
            </div>
        </form>
        <div class="w-full flex flex-col gap-4" v-else>
            <div class="w-full flex flex-col gap-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Git</h2>
                <p class="text-gray-500 dark:text-gray-400">Git is a distributed version control system.</p>
            </div>
            <div class="w-full flex flex-col gap-2">
                <span class="text-green-500"> <span class="icon-[mdi--check-circle]"></span> Git is already installed : {{
                        git
                    }}</span>
            </div>
            <div class="w-full flex flex-col gap-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Composer</h2>
                <p class="text-gray-500 dark:text-gray-400">Composer is a dependency manager for PHP.</p>
            </div>
            <div class="w-full flex flex-col gap-2">
                <span class="text-green-500"> <span class="icon-[mdi--check-circle]"></span> Composer is already installed : {{
                        composer
                    }}</span>
            </div>
            <div class="w-full flex flex-col gap-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">PHP</h2>
                <p class="text-gray-500 dark:text-gray-400">PHP is a server-side scripting language.</p>
            </div>
            <div class="w-full flex flex-col gap-2">
                <span class="text-green-500" v-for=" versionphp in form.php">
                    <span class="icon-[mdi--check-circle]"></span>
                    {{versionphp.name }}
                </span>
            </div>
            <div class="w-full flex flex-col gap-2">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Node</h2>
                <p class="text-gray-500 dark:text-white">Node.js is a JavaScript runtime environment.</p>
            </div>
            <div class="w-full flex flex-col gap-2">
                <span class="text-green-500" v-for=" versionnode in form.node">
                    <span class="icon-[mdi--check]"></span>
                    {{versionnode.version }}}
                </span>
            </div>
        </div>
    </div>
</template>
