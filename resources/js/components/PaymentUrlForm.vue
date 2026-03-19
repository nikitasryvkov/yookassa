<template>
    <div>
        <label for="payment_method_id" class="form-label">Способ оплаты</label>
        <select
            id="payment_method_id"
            name="payment_method_id"
            v-model="selectedMethod"
            required
            class="form-input"
        >
            <option value="">Выберите способ оплаты</option>
            <option
                v-for="method in methods"
                :key="method.payment_method_id"
                :value="method.payment_method_id"
            >
                {{ method.method.name }}
            </option>
        </select>
    </div>

    <div>
        <label for="amount" class="form-label">Сумма</label>
        <input
            type="number"
            name="sum"
            required
            id="amount"
            v-model.number="amount"
            placeholder="Введите сумму"
            class="form-input"
            min="1"
            step="any"
        >
    </div>

    <div>
        <label for="customer_email" class="form-label">Email покупателя (для чека)</label>
        <input
            type="email"
            name="customer_email"
            id="customer_email"
            v-model.trim="customerEmail"
            placeholder="buyer@example.com"
            class="form-input"
        >
    </div>

    <div class="flex items-center gap-6 rounded-xl border border-slate-700/60 bg-slate-800/40 px-4 py-3 text-sm text-slate-300">
        <span>Комиссия: <strong id="commission" class="text-amber-400">{{ commission }} ₽</strong></span>
        <span>К оплате: <strong id="total" class="text-emerald-400 text-base">{{ total }} ₽</strong></span>
    </div>

    <input id="total-input" type="hidden" name="total" :value="total" />
    <input id="total-commission" type="hidden" name="commission" :value="commission" />
</template>

<script setup>
import { ref, computed, watch } from 'vue'

// Пропс от родителя
const props = defineProps({
    methods: {
        type: Array,
        required: true
    },
    yandexCommission: {
        type: String,
        required: true
    },
    cardCommission: {
        type: String,
        required: true
    },
    yookassaCommission: {
        type: String,
        required: true
    },
    qrCommission: {
        type: String,
        required: true
    }
})

    // Состояния формы
const selectedMethod = ref('')
const amount = ref(0)
const customerEmail = ref('')

// Преобразуем комиссии из строки в число
const cardCommission = computed(() => {
    const value = parseFloat(props.cardCommission)
    console.log('Card Commission:', value)
    return !isNaN(value) ? value : 0
})

const qrCommission = computed(() => {
    const value = parseFloat(props.qrCommission)
    console.log('QR Commission:', value)
    return !isNaN(value) ? value : 0
})

const yandexCommission = computed(() => {
    const value = parseFloat(props.yandexCommission)
    console.log('Yandex Commission:', value)
    return !isNaN(value) ? value : 0
})

const yookassaCommission = computed(() => {
    const value = parseFloat(props.yookassaCommission)
    console.log('YooKassa Commission:', value)
    return !isNaN(value) ? value : 0
})

// Логирование для отладки
watch(selectedMethod, (newValue) => {
    console.log('Selected method changed:', newValue)
})

// Вычисляемое значение для commissionPercent, зависимое от выбранного метода
const commissionPercent = computed(() => {
    console.log('Calculating commissionPercent for selectedMethod:', selectedMethod.value)

    if (!selectedMethod.value) {
        console.log('No method selected. Returning 0 commission.')
        return 0
    }

    const methodId = Number.parseInt(String(selectedMethod.value), 10)

    switch (methodId) {
        case 1: // Если id = 1, то используется cardCommission
            console.log('Using cardCommission:', cardCommission.value)
            return cardCommission.value
        case 2: // Если id = 2, то используется qrCommission
            console.log('Using qrCommission:', qrCommission.value)
            return qrCommission.value
        case 3: // Если id = 3, то используется yandexCommission
            console.log('Using yandexCommission:', yandexCommission.value)
            return yandexCommission.value
        case 4: // YooKassa: отдельная ставка
            console.log('Using yookassaCommission:', yookassaCommission.value)
            return yookassaCommission.value
        default:
            console.log('Invalid method selected. Returning 0 commission.')
            return 0 // Если метод не выбран, комиссия равна 0
    }
})

// Логирование для отладки
watch(commissionPercent, (newCommission) => {
    console.log('Commission Percent changed:', newCommission)
})

// Вычисляемая комиссия
const commission = computed(() => {
    const calculatedCommission = +(amount.value * commissionPercent.value / 100).toFixed(2)
    console.log('Calculated Commission:', calculatedCommission)
    return calculatedCommission
})

// Итоговая сумма с учётом комиссии
const total = computed(() => {
    const calculatedTotal = (amount.value + commission.value).toFixed(2)
    console.log('Calculated Total:', calculatedTotal)
    return calculatedTotal
})

// Следим за изменениями и обновляем скрытые поля
watch([total, commission], () => {
    document.getElementById('total-input').value = total.value
    document.getElementById('total-commission').value = commission.value
})
</script>
