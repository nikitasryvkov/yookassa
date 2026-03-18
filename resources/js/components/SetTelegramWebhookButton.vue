<template>
    <button @click="setTelegramWebhook" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 ml-2">
        Установить телеграм вэбхук
    </button>
</template>

<script setup>

const props = defineProps({
    token: String,
    secret: String,
    id: String
});

const setTelegramWebhook = async (event) => {
    // Prevent default behavior (например, если это форма, предотвратить отправку)
    event.preventDefault();

    // Логика для установки вебхука
    await axios.post('/bots/set-wh', {
        "token": props.token,
        "secret": props.secret,
        "id": props.id
    })
        .then(function (response) {
            const data = response.data;
            document.querySelector('.info-' + props.id).innerHTML = data.result;
        })
        .catch(function (error) {
            alert(error);
        });
    return
};


</script>
