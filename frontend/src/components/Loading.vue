<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
        <div class="flex flex-col items-center">
            <div class="spinner"></div>
            <p v-if="message" class="mt-2 text-white text-lg">{{ message }}</p>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'Loading',
    props: {
        isVisible: {
            type: Boolean,
            required: true,
        },
        message: {
            type: String,
            default: 'Carregando...',
        },
    },
});
</script>

<style scoped>
.spinner {
    border: 4px solid rgba(255, 255, 255, 0.3);
    border-top: 4px solid #ffffff;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }

    100% {
        transform: rotate(360deg);
    }
}
</style>