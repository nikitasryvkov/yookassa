export const changeUserPaymentMethod = async (event) => {

    await axios.post('/users-payment-methods/manage', {
        "user": event.target.dataset.user,
        "method": event.target.dataset.method,
        "checked": +event.target.checked,
    })
        .then(function (response) {
            const data = response.data;
            console.log(data);
        })
        .catch(function (error) {
            alert(error);
        });
    return
}
