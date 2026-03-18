<template>
    <button
        @click="deletePaymentPoint"
        type="button"
        class="px-4 py-2 bg-red-500 text-white font-bold rounded-lg shadow-md hover:bg-red-600
        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
        x
    </button>
</template>

<script setup>
const props = defineProps({
    id: String
});

const deletePaymentPoint = async (e) => {
    if(props.id == 1) {
        alert('Нельзя удалить точку с id = 1')
        return;
    }

    if(!confirm('Уверены что хотите удалить точку?'))
    {
        return;
    }

    await axios.post('/payment-points/delete', {
        "id": props.id
    })
        .then(function (response) {
            e.target.closest('FORM').remove();
        })
        .catch(function (error) {
            alert(error);
        });
}
</script>

<style scoped>

</style>
