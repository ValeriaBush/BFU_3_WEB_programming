document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('deliveryDate').min = new Date().toISOString().split('T')[0];
});

document.getElementById('foodOrderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const name = this.name.value;
    const email = this.email.value;
    const portions = this.portions.value;
    const dish = this.dish.value;
    const deliveryDate = this.deliveryDate.value;
    const sauce = this.sauce.checked ? 'Да' : 'Нет';
    const deliveryType = formData.get('deliveryType');
    
    const dishText = this.dish.options[this.dish.selectedIndex].text;
    
    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = `
        <h2>Ваш заказ принят!</h2>
        <div class="order-summary">
            <p><strong>Имя:</strong> ${name}</p>
            <p><strong>Email:</strong> ${email}</p>
            <p><strong>Количество порций:</strong> ${portions}</p>
            <p><strong>Блюдо:</strong> ${dishText}</p>
            <p><strong>Дата доставки:</strong> ${deliveryDate}</p>
            <p><strong>Добавить соус:</strong> ${sauce}</p>
            <p><strong>Тип доставки:</strong> ${getDeliveryTypeText(deliveryType)}</p>
        </div>
    `;
    
    this.reset();
});

function getDeliveryTypeText(type) {
    const deliveryTypes = {
        'courier': 'Курьерская доставка',
        'pickup': 'Самовывоз',
        'express': 'Экспресс-доставка (30 мин)'
    };
    return deliveryTypes[type] || type;
}