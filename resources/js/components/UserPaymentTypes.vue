<template>
    <div @change="changeUserPaymentMethod">
        <slot/>
    </div>
</template>

<script setup>
    import {changeUserPaymentMethod} from "../composables/changeUserPaymentMethod.js";
</script>
