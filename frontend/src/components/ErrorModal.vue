<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-semibold text-red-600 mb-4">Erro</h2>
            <p class="text-gray-600 mb-6">{{ message }}</p>
            <div class="text-right">
                <button @click="$emit('close')"
                    class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';

export default defineComponent({
    name: 'ErrorModal',
    props: {
        isVisible: {
            type: Boolean,
            required: true,
        },
        message: {
            type: String,
            required: true,
        },
    },
    emits: ['close'],
});
</script>
