import { ref, onMounted } from 'vue';
import axios from 'axios';
import router from '@/router';

export function useDashboard() {
    const getToday = () => {
        const today = new Date();
        const year = today.getFullYear();
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');
        return `${year}-${month}-${day}`;
    };

    const name = ref('');
    const email = ref('');
    const showModal = ref(false);
    const listModal = ref(false);
    const showSaleModal = ref(false);
    const salesListModal = ref(false);
    const sellerSalesModal = ref(false);
    const showSuccessModal = ref(false);
    const showErrorModal = ref(false);
    const isLoading = ref(false);
    const successMessage = ref('');
    const errorMessage = ref('');
    const userEmail = ref('');

    const sellers = ref<any[]>([]);
    const sales = ref<any[]>([]);
    const selectedSellerId = ref('');
    const selectedSellerSales = ref<any[]>([]);

    const sale = ref({
        seller_id: '',
        amount: '',
        sale_date: getToday(),
    });

    const formatCurrency = (value: number) => {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL'
        }).format(value);
    };

    const showSuccess = (message: string) => {
        successMessage.value = message;
        showSuccessModal.value = true;
    };

    const showError = (message: string) => {
        errorMessage.value = message;
        showErrorModal.value = true;
    };

    const fetchUserEmail = async () => {
        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/login');
            return;
        }

        isLoading.value = true;
        try {
            const response = await axios.get('http://comissao-vendedores.local/api/user', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
                withCredentials: true,
            });
            userEmail.value = response.data.email || '';
        } catch (error: any) {
            if (error.response?.status === 401) {
                localStorage.removeItem('token');
                router.push('/login');
                showError('Sessão expirada. Por favor, faça login novamente.');
            } else {
                showError('Erro ao carregar dados do usuário: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
            }
        } finally {
            isLoading.value = false;
        }
    };

    onMounted(() => {
        fetchUserEmail();
    });

    const logout = async () => {
        isLoading.value = true;
        try {
            await axios.post('http://comissao-vendedores.local/api/logout', {}, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
                withCredentials: true,
            });
            localStorage.removeItem('token');
            router.push('/login');
        } catch (error: any) {
            showError('Erro ao sair: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
        } finally {
            isLoading.value = false;
        }
    };

    const registerSeller = async () => {
        isLoading.value = true;
        try {
            await axios.post('http://comissao-vendedores.local/api/create/seller', {
                name: name.value,
                email: email.value,
            }, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
                withCredentials: true,
            });

            showModal.value = false;
            name.value = '';
            email.value = '';
            showSuccess('Vendedor cadastrado com sucesso');
        } catch (error: any) {
            showError('Erro ao cadastrar vendedor: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
        } finally {
            isLoading.value = false;
        }
    };

    const registerSale = async () => {
        isLoading.value = true;
        try {
            await axios.post('http://comissao-vendedores.local/api/create/sale', {
                seller_id: sale.value.seller_id,
                value: sale.value.amount,
                date: sale.value.sale_date,
            }, {
                headers: {
                    Authorization: `Bearer ${localStorage.getItem('token')}`,
                },
                withCredentials: true,
            });

            showSaleModal.value = false;
            sale.value = { seller_id: '', amount: '', sale_date: getToday() };
            showSuccess('Venda cadastrada com sucesso');
        } catch (error: any) {
            showError('Erro ao cadastrar venda: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
        } finally {
            isLoading.value = false;
        }
    };

    const openListModal = async () => {
        listModal.value = true;
        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/login');
            return;
        }

        isLoading.value = true;
        try {
            const response = await axios.get('http://comissao-vendedores.local/api/sellers', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
                withCredentials: true,
            });

            sellers.value = response.data;
        } catch (error: any) {
            showError('Erro ao listar vendedores: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
        } finally {
            isLoading.value = false;
        }
    };

    const openSalesListModal = async () => {
        salesListModal.value = true;
        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/login');
            return;
        }

        isLoading.value = true;
        try {
            const response = await axios.get('http://comissao-vendedores.local/api/sales', {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
                withCredentials: true,
            });
            sales.value = response.data;
        } catch (error: any) {
            showError('Erro ao listar vendas: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
        } finally {
            isLoading.value = false;
        }
    };

    const fetchSalesBySeller = async () => {
        if (!selectedSellerId.value) {
            selectedSellerSales.value = [];
            return;
        }

        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/login');
            return;
        }

        isLoading.value = true;
        try {
            const response = await axios.get(`http://comissao-vendedores.local/api/seller/${selectedSellerId.value}/sales`, {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
                withCredentials: true,
            });
            selectedSellerSales.value = response.data;
        } catch (error: any) {
            showError('Erro ao listar vendas do vendedor: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
            selectedSellerSales.value = [];
        } finally {
            isLoading.value = false;
        }
    };

    const openSellerSalesModal = async () => {
        sellerSalesModal.value = true;
        selectedSellerId.value = '';
        selectedSellerSales.value = [];

        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/login');
            return;
        }

        if (!sellers.value.length) {
            isLoading.value = true;
            try {
                const response = await axios.get('http://comissao-vendedores.local/api/sellers', {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                    withCredentials: true,
                });
                sellers.value = response.data;
            } catch (error: any) {
                showError('Erro ao carregar vendedores: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
            } finally {
                isLoading.value = false;
            }
        }
    };

    const resendCommissionEmail = async (sellerId: string) => {
        const token = localStorage.getItem('token');
        if (!token) {
            router.push('/login');
            return;
        }

        isLoading.value = true;
        try {
            await axios.post(
                'http://comissao-vendedores.local/api/seller/resend-daily-report-email',
                { seller_id: sellerId },
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                    },
                    withCredentials: true,
                }
            );
            showSuccess('Email de comissão reenviado com sucesso');
        } catch (error: any) {
            if (error.response?.status === 401) {
                localStorage.removeItem('token');
                router.push('/login');
                showError('Sessão expirada. Por favor, faça login novamente.');
            } else {
                showError('Erro ao reenviar email de comissão: ' + (error.response?.data?.message || 'Falha na conexão com o servidor'));
            }
        } finally {
            isLoading.value = false;
        }
    };

    return {
        name,
        email,
        showModal,
        listModal,
        showSaleModal,
        salesListModal,
        sellerSalesModal,
        showSuccessModal,
        showErrorModal,
        isLoading,
        successMessage,
        errorMessage,
        userEmail,
        sellers,
        sales,
        selectedSellerId,
        selectedSellerSales,
        sale,
        logout,
        registerSeller,
        registerSale,
        openListModal,
        openSalesListModal,
        openSellerSalesModal,
        fetchSalesBySeller,
        resendCommissionEmail,
        formatCurrency,
    };
}