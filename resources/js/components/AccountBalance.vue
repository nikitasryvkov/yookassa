<template>

    <p>
        Баланс счёта: <strong><span v-if="loading">Загрузка... 🔄</span>{{ balance }}</strong>
        <slot name="commission" />
    </p>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const props = defineProps({
    accountId: String,
    balance: [Number, String]
});

const balance = ref(props.balance); // создаём локальное хранилище для баланса
const loading = ref(true);



onMounted(async () => {
    if(props.accountId)
    {
        try {
            const response = await axios.post('/tb/balance', {
                id: props.accountId
            });
            balance.value = response.data.data.balance;
        } catch (error) {
            balance.value = 'Ошибка при получении баланса';
        } finally {
            loading.value = false;
        }
    }
});
</script>
