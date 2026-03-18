<template>
    <label class="hidden md:block">&nbsp;</label>
    <button
        @click="deleteUser"
        type="button"
        class="px-4 py-2 bg-red-500 text-white font-bold rounded-lg shadow-md hover:bg-red-600
        focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
        &times;
    </button>
</template>

<script setup>
const props = defineProps({
    id: String
});

    const deleteUser = async (e) => {
        if(!confirm('Уверены что хотите удалить пользователя?'))
        {
            return;
        }

        await axios.post('/users/delete', {
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
