<template>
    <div>
        <button @click="toggleForm" class="btn-ghost w-full text-sm">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
            </svg>
            {{ showForm ? 'Скрыть фильтры' : 'Показать фильтры' }}
        </button>

        <div v-if="showForm" class="mt-4">
            <form @submit.prevent="submitForm" class="grid grid-cols-1 md:grid-cols-2 gap-3">
                <select v-if="isAdmin" v-model="searchFields.user_id" class="input-field">
                    <option value="">Пользователь</option>
                    <option v-for="user in users" :key="user.id" :value="user.id">{{ user.name }}</option>
                </select>

                <select v-if="isAdmin" v-model="searchFields.payment_point_id" class="input-field">
                    <option value="">Платёжная точка</option>
                    <option v-for="point in paymentPoints" :key="point.id" :value="point.id">{{ point.name }}</option>
                </select>

                <select v-model="searchFields.payment_method_id" class="input-field">
                    <option value="">Способ оплаты</option>
                    <option v-for="method in paymentMethods" :key="method.id" :value="method.id">{{ method.name }}</option>
                </select>

                <input type="date" v-model="searchFields.created_at" class="input-field">

                <div class="col-span-1 md:col-span-2 flex gap-3">
                    <button type="submit" class="search-btn-primary">Найти</button>
                    <button type="button" @click="clearFilters" class="search-btn-secondary">Сбросить</button>
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
    @apply w-full rounded-xl border border-slate-600/80 bg-slate-900/60 px-3.5 py-2.5 text-sm text-slate-100
           placeholder:text-slate-500 transition-all duration-200 focus:border-blue-500/60 focus:outline-none focus:ring-2 focus:ring-blue-500/25;
}
.search-btn-primary {
    @apply inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
           bg-blue-600 text-white shadow-md shadow-blue-500/20 hover:bg-blue-500 transition-all duration-200;
}
.search-btn-secondary {
    @apply inline-flex items-center justify-center gap-2 rounded-xl px-4 py-2.5 text-sm font-semibold
           border border-slate-600 bg-slate-800 text-slate-200 hover:border-slate-500 hover:bg-slate-700 transition-all duration-200;
}
</style>
