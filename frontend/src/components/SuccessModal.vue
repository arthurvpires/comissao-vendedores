<template>
    <div v-if="isVisible" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
        <div class="bg-white p-6 rounded-lg shadow-md w-96">
            <h2 class="text-2xl font-semibold text-green-600 mb-4">Sucesso</h2>
            <p class="text-gray-600 mb-6">{{ message }}</p>
            <div class="text-right">
                <button @click="handleClose"
                    class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 focus:outline-none">
                    Fechar
                </button>
            </div>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { useRouter } from 'vue-router';

export default defineComponent({
    name: 'SuccessModal',
    props: {
        isVisible: {
            type: Boolean,
            required: true,
        },
        message: {
            type: String,
            required: true,
        },
        redirect: {
            type: String,
            default: '',
        },
    },
    emits: ['close'],
    setup(props, { emit }) {
        const router = useRouter();

        const handleClose = () => {
            emit('close');
            if (props.redirect) {
                router.push(props.redirect);
            }
        };

        return {
            handleClose,
        };
    },
});
</script>