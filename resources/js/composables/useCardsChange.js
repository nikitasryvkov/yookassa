export const cardsChange = async (event) => {
    if (event.target.classList.contains('create-reciept')) {
        const paymentCard = event.target.closest('.payment-card');
        const checkStatusElem = paymentCard.querySelector('.check-status');
        event.target.closest('LABEL').remove();
        await axios.post('/receipt', {
            type: paymentCard.dataset.type,
            id: paymentCard.dataset.id,
        })
            .then(function (response) {
                checkStatusElem.innerHTML = 'Отправлен запрос на создание чека';
                checkStatusElem.classList.remove('invisible');
                console.log(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
        return
    }
}
