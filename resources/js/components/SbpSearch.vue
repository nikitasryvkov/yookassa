<template>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg">
        <!-- Кнопка показа формы -->
        <button @click="toggleForm"
                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            {{ showForm ? 'Скрыть форму поиска' : 'Показать форму поиска' }}
        </button>

        <!-- Форма поиска -->
        <div v-if="showForm" class="mt-4">
            <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" v-model="searchFields.operation_id" placeholder="ID Операции" class="input-field">
                <input type="text" v-model="searchFields.qrc_id" placeholder="QR ID" class="input-field">
                <input type="text" v-model="searchFields.amount" placeholder="Сумма" class="input-field">
                <input type="text" v-model="searchFields.payer_mobile_number" placeholder="Мобильный плательщика" class="input-field">
                <input type="text" v-model="searchFields.payer_name" placeholder="Имя плательщика" class="input-field">
                <input type="text" v-model="searchFields.brand_name" placeholder="Брэнд" class="input-field">
                <input type="text" v-model="searchFields.merchant_id" placeholder="Merchant ID" class="input-field">
                <input type="text" v-model="searchFields.purpose" placeholder="Назначение" class="input-field">
                <input type="text" v-model="searchFields.customer_code" placeholder="Код клиента" class="input-field">
                <input type="date" v-model="searchFields.created_at" placeholder="Дата" class="input-field">

                <div class="col-span-1 md:col-span-2 flex justify-between">
                    <button type="submit" class="btn-primary">Найти</button>
                    <button type="button" @click="clearFilters" class="btn-secondary">Сбросить</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

const props = defineProps({
    filters: Object
});

const showForm = ref(false);

// Заполняем searchFields из props.filters
const searchFields = ref({
    operation_id: '',
    qrc_id: '',
    amount: '',
    payer_mobile_number: '',
    payer_name: '',
    brand_name: '',
    merchant_id: '',
    purpose: '',
    customer_code: '',
    created_at: ''
});

onMounted(() => {
    Object.keys(searchFields.value).forEach(key => {
        if (props.filters[key]) {
            searchFields.value[key] = props.filters[key];
        }
    });
});

const toggleForm = () => {
    showForm.value = !showForm.value;
};

// Отправка формы через GET-запрос
const submitForm = () => {
    const params = new URLSearchParams(searchFields.value).toString();
    window.location.href = `/tb/incoming-sbp-payments?${params}`; // Перенаправляем страницу с параметрами
};

// Очистка полей и редирект на страницу без параметров
const clearFilters = () => {
    searchFields.value = {
        operation_id: '',
        qrc_id: '',
        amount: '',
        payer_mobile_number: '',
        payer_name: '',
        brand_name: '',
        merchant_id: '',
        purpose: '',
        customer_code: '',
        created_at: ''
    };
    window.location.href = '/tb/incoming-sbp-payments'; // Перенаправляем без параметров
};
</script>

<style scoped>
.input-field {
    @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500;
}

.btn-primary {
    @apply px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700;
}

.btn-secondary {
    @apply px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700;
}
</style>
