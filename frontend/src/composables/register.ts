import { defineComponent, ref } from 'vue';
import axios from 'axios';
import router from '@/router';

export function useRegister() {
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

    const redirectToLogin = () => {
        showSuccessModal.value = false;
        router.push('/login');
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
        redirectToLogin,
    };
}
