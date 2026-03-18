<template>
    <div class="text-lg font-semibold text-center">Сейчас {{props.now}}</div>
    <div v-if="props.payments.length" class="text-center mb-10">Сегодняшние поступления:</div>
    <div v-if="props.payments.length < 1" class="text-center">Поступлений ещё нет</div>

    <Tabs :tabs="tabs">
        <template #tab1>
            <div @change="cardsChange" @click="checkReceiptStatus">
                <slot name="cards"/>
            </div>
        </template>
        <template #tab2>
            <slot name="bpayments"/>
        </template>
    </Tabs>



</template>

<script setup>
    const props = defineProps({
        now: [String],
        payments: [Array],
    })

    const tabs = [
        { label: "SBP", slot: "tab1" },
        { label: "Telegram bot", slot: "tab2" }
    ];

    import {cardsChange} from "../composables/useCardsChange.js";
    import {checkReceiptStatus} from "../composables/checkReceiptStatus.js"
</script>
