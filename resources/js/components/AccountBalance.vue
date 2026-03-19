<template>

    <div>
        <div class="stat-value" v-if="loading">
            <span class="inline-block h-6 w-24 animate-pulse rounded bg-slate-700"></span>
        </div>
        <div class="stat-value" v-else>{{ balance }}</div>
        <slot name="commission" />
    </div>
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
