<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-2xl text-black font-semibold text-center mb-6">Criar Conta</h2>
            <form @submit.prevent="register">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input id="name" type="text" v-model="name"
                        class="mt-1 block text-black w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required />
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" v-model="email"
                        class="mt-1 block text-black w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required />
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input id="password" type="password" v-model="password"
                        class="mt-1 block text-black w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required />
                </div>

                <button type="submit"
                    class="w-full bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition duration-200">
                    Cadastrar
                </button>
            </form>

            <SuccessModal :is-visible="showSuccessModal" :message="successMessage" redirect="/login"
                @close="showSuccessModal = false" />
            <ErrorModal :is-visible="showErrorModal" :message="errorMessage" @close="showErrorModal = false" />
            <Loading :is-visible="isLoading" />
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from 'vue';
import axios from 'axios';
import SuccessModal from '@/components/SuccessModal.vue';
import ErrorModal from '@/components/ErrorModal.vue';
import Loading from '@/components/Loading.vue';

export default defineComponent({
    name: 'Register',
    components: {
        SuccessModal,
        ErrorModal,
        Loading,
    },
    setup() {
        const name = ref('');
        const email = ref('');
        const password = ref('');
        const showSuccessModal = ref(false);
        const showErrorModal = ref(false);
        const isLoading = ref(false);
        const successMessage = ref('');
        const errorMessage = ref('');

        const showSuccess = (message: string) => {
            successMessage.value = message;
            showSuccessModal.value = true;
        };

        const showError = (message: string) => {
            errorMessage.value = message;
            showErrorModal.value = true;
        };

        const register = async () => {
            isLoading.value = true;
            try {
                await axios.post('http://comissao-vendedores.local/api/register', {
                    name: name.value,
                    email: email.value,
                    password: password.value,
                });

                name.value = '';
                email.value = '';
                password.value = '';
                showSuccess('Usuário cadastrado com sucesso');
            } catch (error: any) {
                showError('Erro ao cadastrar usuário: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
            } finally {
                isLoading.value = false;
            }
        };

        return {
            name,
            email,
            password,
            showSuccessModal,
            showErrorModal,
            isLoading,
            successMessage,
            errorMessage,
            register,
        };
    },
});
</script>