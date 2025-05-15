import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

export function useLogin() {
    const email = ref('');
    const password = ref('');
    const error = ref('');
    const isLoading = ref(false);
    const router = useRouter();

    const login = async () => {
        isLoading.value = true;
        try {
            error.value = '';
            const response = await axios.post('http://comissao-vendedores.local/api/login', {
                email: email.value,
                password: password.value,
            }, { withCredentials: true });
            localStorage.setItem('token', response.data.token);
            router.push('/dashboard');
        } catch (err: any) {
            if (err.response?.status === 401) {
                error.value = 'Email ou senha incorretos';
            } else {
                error.value = 'Erro ao fazer login: ' + (err.response?.data?.message || 'Falha na conexão com o servidor');
            }
        } finally {
            isLoading.value = false;
        }
    };

    return {
        email,
        password,
        login,
        error,
        isLoading,
    };
}
