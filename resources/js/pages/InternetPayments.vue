<template>
    <div @change="cardsChange" @click="checkReceiptStatus">
        <slot name="cards"/>
    </div>
</template>


<script setup>

import {cardsChange} from "../composables/useCardsChange.js";
import {checkReceiptStatus} from "../composables/checkReceiptStatus.js"

</script>
