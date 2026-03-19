<template>
    <div class="flex items-center justify-start">
        <button
            v-if="state === 'idle'"
            @click.prevent="handleClick"
            class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600/15 px-3 py-1.5 text-xs font-semibold text-blue-400 ring-1 ring-blue-500/30 hover:bg-blue-600/25 transition-colors"
        >
            Начислить агентские
        </button>

        <div v-else-if="state === 'loading'" class="flex items-center justify-start">
            <svg class="animate-spin h-4 w-4 text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"/>
            </svg>
        </div>

        <div v-else-if="state === 'success'" class="badge-success">
            Запрос отправлен
        </div>

        <div v-else-if="state === 'error'" class="badge-error">
            {{ errorMessage }}
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
    id: {
        type: [String, Number],
        required: true
    }
})

const state = ref('idle') // idle | loading | success | error
const errorMessage = ref('')

async function handleClick() {
    state.value = 'loading'
    errorMessage.value = ''

    try {
        const response = await axios.post('/reports/commission/pay', { id: props.id })

        if (response.data.error) {
            throw new Error(response.data.error)
        }

        state.value = 'success'
    } catch (error) {
        errorMessage.value = error.message || 'Ошибка при отправке запроса'
        state.value = 'error'
    }
}
</script>
