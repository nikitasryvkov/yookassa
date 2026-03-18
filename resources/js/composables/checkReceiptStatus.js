export const checkReceiptStatus = async (event) => {

    if(event.target.classList.contains('check-receipt-status'))
    {
        event.preventDefault();
        const id = event.target.dataset.id;

        await axios.post('/receipt/get-status', {id})
            .then(function (response) {
                const statusResponseContainer = event.target.closest('.check-receipt-status-container')
                    .querySelector('.check-receipt-status-response')
                let html = '';
                const data = response.data;
                if(data.error) {
                    html = data.error
                }

                let date = new Date(data.fiscalInfo.date);
                date = date.toLocaleDateString() + ' ' + date.toLocaleTimeString();
                html += `<span>${data.status} №Чека: ${data.fiscalInfo.checkNumber} от ${date}</span>`;
                statusResponseContainer.innerHTML = html;
                console.log(data);
            })
            .catch(function (error) {
                alert(error);
            });
        return
    }

}
