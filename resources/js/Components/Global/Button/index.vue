<script setup>
defineProps({
    type: {
        type: String,
        default: 'button',
    },
    color: {
        type: String,
        default: 'primary',
    },
    size: {
        type: String,
        default: 'md',
    },
    disabled: {
        type: Boolean,
        default: false,
    },
    loading: {
        type: Boolean,
        default: false,
    },
    icon: {
        type: String,
        default: null,
    }
})
</script>

<template>
    <button :type="type" :class="`btn btn-${color} btn-${size} ${loading ? 'loading' : ''}`" :disabled="disabled">
        <span v-if="icon">
            <span class="icon" :class="icon"></span>
        </span>
        <slot></slot>
    </button>
</template>
