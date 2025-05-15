<template>
    <div class="flex flex-col items-center justify-center h-screen bg-gray-100">
        <div class="bg-white p-6 rounded-lg shadow-md max-w-lg w-full text-center">
            <h1 class="text-2xl font-semibold text-gray-800 mb-4">Bem-vindo ao Painel!</h1>
            <p class="text-gray-600 mb-6">Você está autenticado!</p>

            <div class="flex flex-col space-y-4">
                <button @click="showModal = true"
                    class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 focus:outline-none">
                    Cadastrar Vendedor
                </button>

                <button @click="openListModal"
                    class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 focus:outline-none">
                    Listar Vendedores
                </button>

                <button @click="showSaleModal = true"
                    class="bg-purple-500 text-white py-2 px-4 rounded hover:bg-purple-600 focus:outline-none">
                    Cadastrar Venda
                </button>

                <button @click="openSalesListModal"
                    class="bg-indigo-500 text-white py-2 px-4 rounded hover:bg-indigo-600 focus:outline-none">
                    Listar Vendas
                </button>

                <button @click="openSellerSalesModal"
                    class="bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600 focus:outline-none">
                    Listar Vendas por Vendedor
                </button>
            </div>

            <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-md w-96">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cadastrar Vendedor</h2>

                    <form @submit.prevent="registerSeller" class="space-y-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-600">Nome</label>
                            <input id="name" v-model="name" type="text" required
                                class="w-full p-2 border border-gray-300 text-black rounded mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-600">E-mail</label>
                            <input id="email" v-model="email" type="email" required
                                class="w-full p-2 border border-gray-300 text-black rounded mt-1 focus:outline-none focus:ring-2 focus:ring-blue-500" />
                        </div>

                        <div class="mt-4 flex justify-between items-center">
                            <button type="submit"
                                class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 focus:outline-none">
                                Cadastrar
                            </button>

                            <button @click="showModal = false" type="button" class="text-red-500 hover:text-red-700">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div v-if="listModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-md w-[80%] max-h-[80vh] overflow-y-auto">
                    <h2 class="text-2xl font-semibold text-black mb-4">Vendedores Cadastrados</h2>
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 text-center font-bold text-black">Nome</th>
                                <th class="py-2 px-4 text-center font-bold text-black">E-mail</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Data de Cadastro</th>
                                <th v-if="userIsAdmin"
                                    class="py-2 px-4 text-center font-bold text-black">Reenviar email de comissão</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="seller in sellers" :key="seller.id" class="hover:bg-gray-50">
                                <td class="py-2 px-4 border border-gray-300 text-left text-black">{{ seller.name }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-left text-black">{{ seller.email }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300 text-left text-black">{{
                                    new Date(seller.created_at).toLocaleString('pt-BR') }}
                                </td>
                                <td v-if="userIsAdmin"
                                    class="py-2 px-4 border border-gray-300 text-center text-black">
                                    <button @click="resendCommissionEmail(seller.id)"
                                        class="bg-blue-500 text-white py-1 px-2 rounded hover:bg-blue-600 focus:outline-none">
                                        Reenviar
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 text-right">
                        <button @click="listModal = false"
                            class="bg-black text-white py-2 px-4 rounded hover:bg-gray-800 focus:outline-none">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="showSaleModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-md w-96">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Cadastrar Venda</h2>

                    <form @submit.prevent="registerSale" class="space-y-4">
                        <div>
                            <label for="seller_id" class="block text-sm font-medium text-gray-600">ID do
                                Vendedor</label>
                            <input id="seller_id" v-model="sale.seller_id" type="number" required
                                class="w-full p-2 border border-gray-300 text-black rounded mt-1 focus:outline-none focus:ring-2 focus:ring-purple-500" />
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-600">Valor da Venda
                                (R$)</label>
                            <input id="amount" v-model="sale.amount" type="number" step="0.01" required
                                class="w-full p-2 border border-gray-300 text-black rounded mt-1 focus:outline-none focus:ring-2 focus:ring-purple-500" />
                        </div>

                        <div>
                            <label for="sale_date" class="block text-sm font-medium text-gray-600">Data da Venda</label>
                            <input id="sale_date" v-model="sale.sale_date" type="date" required
                                class="w-full p-2 border border-gray-300 text-black rounded mt-1 focus:outline-none focus:ring-2 focus:ring-purple-500" />
                        </div>

                        <div class="mt-4 flex justify-between items-center">
                            <button type="submit"
                                class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600 focus:outline-none">
                                Cadastrar
                            </button>

                            <button @click="showSaleModal = false" type="button"
                                class="text-red-500 hover:text-red-700">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div v-if="salesListModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-md w-[80%] max-h-[80vh] overflow-y-auto">
                    <h2 class="text-2xl font-semibold text-black mb-4">Vendas Registradas</h2>
                    <table class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-2 text-center font-bold text-black">ID Vendedor</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Nome Vendedor</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Valor (R$)</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Comissão (R$)</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Data da Venda</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Horário de registro da venda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="saleItem in sales" :key="saleItem.id" class="hover:bg-gray-50">
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{
                                    saleItem.seller_id }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{
                                    saleItem.seller.name }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{
                                    formatCurrency(saleItem.value / 100) }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{
                                    formatCurrency(saleItem.commission / 100) }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{ new
                                    Date(saleItem.date).toLocaleDateString('pt-BR') }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{ new
                                    Date(saleItem.created_at).toLocaleString('pt-BR') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <div class="mt-4 text-right">
                        <button @click="salesListModal = false"
                            class="bg-black text-white py-2 px-4 rounded hover:bg-gray-800 focus:outline-none">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>

            <div v-if="sellerSalesModal"
                class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-md w-[80%] max-h-[80vh] overflow-y-auto">
                    <h2 class="text-2xl font-semibold text-black mb-4">Vendas por Vendedor</h2>

                    <div class="mb-4">
                        <label for="seller_select" class="block text-sm font-medium text-gray-600">Selecione o
                            Vendedor</label>
                        <select id="seller_select" v-model="selectedSellerId" @change="fetchSalesBySeller"
                            class="w-full p-2 border border-gray-300 text-black rounded mt-1 focus:outline-none focus:ring-2 focus:ring-orange-500">
                            <option value="" disabled>Escolha um vendedor</option>
                            <option v-for="seller in sellers" :key="seller.id" :value="seller.id">
                                {{ seller.name }} (ID: {{ seller.id }})
                            </option>
                        </select>
                    </div>

                    <table v-if="selectedSellerSales.length" class="min-w-full bg-white border border-gray-300">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 text-center font-bold text-black">ID Venda</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Valor (R$)</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Comissão (R$)</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Data da Venda</th>
                                <th class="py-2 px-4 text-center font-bold text-black">Horário de Registro</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="saleItem in selectedSellerSales" :key="saleItem.id" class="hover:bg-gray-50">
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{ saleItem.id }}
                                </td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{
                                    formatCurrency(saleItem.value / 100) }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{
                                    formatCurrency(saleItem.commission / 100) }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{ new
                                    Date(saleItem.date).toLocaleDateString('pt-BR') }}</td>
                                <td class="py-2 px-4 border border-gray-300 text-center text-black">{{ new
                                    Date(saleItem.created_at).toLocaleString('pt-BR') }}</td>
                            </tr>
                        </tbody>
                    </table>

                    <p v-else-if="selectedSellerId" class="text-gray-600 text-center">Nenhuma venda encontrada para este
                        vendedor.</p>
                    <p v-else class="text-gray-600 text-center">Selecione um vendedor para visualizar as vendas.</p>

                    <div class="mt-4 text-right">
                        <button @click="sellerSalesModal = false"
                            class="bg-black text-white py-2 px-4 rounded hover:bg-gray-800 focus:outline-none">
                            Fechar
                        </button>
                    </div>
                </div>
            </div>

            <SuccessModal :is-visible="showSuccessModal" :message="successMessage" @close="showSuccessModal = false" />
            <ErrorModal :is-visible="showErrorModal" :message="errorMessage" @close="showErrorModal = false" />
            <Loading :is-visible="isLoading" />

            <button @click="logout"
                class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600 focus:outline-none mt-4">
                Sair
            </button>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent } from 'vue';
import { useDashboard } from '@/composables/dashboard';
import SuccessModal from '@/components/SuccessModal.vue';
import ErrorModal from '@/components/ErrorModal.vue';
import Loading from '@/components/Loading.vue';

export default defineComponent({
    name: 'Dashboard',
    components: {
        SuccessModal,
        ErrorModal,
        Loading,
    },
    setup() {
        return {
            ...useDashboard(),
        };
    },
});
</script>