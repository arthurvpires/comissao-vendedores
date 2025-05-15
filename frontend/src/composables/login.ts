import axios from 'axios';
import { ref } from 'vue';
import { useRouter } from 'vue-router';

export function useLogin() {
    const email = ref('');
    const password = ref('');
    const error = ref('');
    const router = useRouter();

    const login = async () => {
        try {
            error.value = '';

            const response = await axios.post('http://comissao-vendedores.local/api/login', {
                email: email.value,
                password: password.value,
            }, { withCredentials: true });

            const token = response.data.token;
            localStorage.setItem('token', token);

            router.push('/dashboard');
        } catch (err: any) {
            error.value = 'Credenciais inválidas';
        }
    };

    return {
        email,
        password,
        login,
        error,
    };
}