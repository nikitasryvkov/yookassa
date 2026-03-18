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
                <!-- Селект для User ID -->
                <select v-if="isAdmin" v-model="searchFields.user_id" class="input-field">
                    <option value="">Выберите User ID</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                </select>

                <!-- Селект для Payment Point ID -->
                <select v-if="isAdmin" v-model="searchFields.payment_point_id" class="input-field">
                    <option value="">Выберите Payment Point ID</option>
                    <option v-for="point in paymentPoints" :key="point.id" :value="point.id">{{ point.name }}</option>
                </select>

                <!-- Селект для Payment Method ID -->
                <select v-model="searchFields.payment_method_id" class="input-field">
                    <option value="">Выберите Payment Method</option>
                    <option v-for="method in paymentMethods" :key="method.id" :value="method.id">{{ method.name }}</option>
                </select>

                <!-- Текстовые инпуты для других полей -->
                <input type="date" v-model="searchFields.created_at" placeholder="Payer Tag" class="input-field">

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
    users: Array|Object,
    paymentPoints: Array|Object,
    paymentMethods: Array|Object,
    filters: Object,
    isAdmin: Boolean
});

const showForm = ref(false);

// Заполняем searchFields из props.filters
const searchFields = ref({
    user_id: '',
    payment_point_id: '',
    payment_method_id: '',
    created_at: ''
});

onMounted(() => {
    // Применяем начальные фильтры из props, если они есть
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
    window.location.href = `/reports/commission?${params}`; // Перенаправляем страницу с параметрами
};

// Очистка полей и редирект на страницу без параметров
const clearFilters = () => {
    searchFields.value = {
        user_id: '',
        payment_point_id: '',
        payment_method_id: '',
        created_at: ''
    };
    window.location.href = '/reports/commission'; // Перенаправляем без параметров
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
