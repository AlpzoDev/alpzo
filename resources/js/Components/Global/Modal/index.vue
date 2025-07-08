<script setup>
import {ref} from 'vue'

const props = defineProps({
    title: String,
    showModal: {
        type: Boolean,
        default: false
    },
    fullScreen: {
        type: Boolean,
        default: false
    }
})

const showModal = ref(props.showModal)

const closeModal = () => {
    showModal.value = false
}

const openModal = () => {
    showModal.value = true
}

</script>

<template>
    <!-- Modal toggle -->
    <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-1 px-2 rounded disabled:cursor-not-allowed disabled:opacity-50 mt-2" @click="openModal">
        {{ title }}
    </button>

    <!-- Main modal -->
    <div v-show="showModal"
         tabindex="-1"
         class="overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0  flex flex-col z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900 bg-opacity-50">
        <div :class="[
' p-4 w-full  max-h-full',
fullScreen ? 'h-screen ' : 'h-auto max-w-2xl'
]">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ title }}</h3>
                    <button type="button"
                            class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                            @click="closeModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                             viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <slot/>
                </div>
            </div>
        </div>
    </div>

</template>
