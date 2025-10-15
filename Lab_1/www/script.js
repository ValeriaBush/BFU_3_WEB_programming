document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('deliveryDate').min = new Date().toISOString().split('T')[0];
});

document.getElementById('foodOrderForm').addEventListener('submit', function(e) {
    const name = document.querySelector("[name='name']").value;
    const email = document.querySelector("[name='email']").value;
    const portions = document.querySelector("[name='portions']").value;
    const dish = document.querySelector("[name='dish']");
    const dishText = dish.options[dish.selectedIndex].text;
    const deliveryDate = document.querySelector("[name='deliveryDate']").value;
    const sauce = document.querySelector("[name='sauce']").checked ? 'Да' : 'Нет';
    const deliveryType = document.querySelector("[name='deliveryType']:checked").value;
    
    const deliveryTypeText = getDeliveryTypeText(deliveryType);
    
    alert(`Ваш заказ:\n\nИмя: ${name}\nEmail: ${email}\nКоличество порций: ${portions}\nБлюдо: ${dishText}\nДата доставки: ${deliveryDate}\nДобавить соус: ${sauce}\nТип доставки: ${deliveryTypeText}`);
});

function getDeliveryTypeText(type) {
    const deliveryTypes = {
        'courier': 'Курьерская доставка',
        'pickup': 'Самовывоз',
        'express': 'Экспресс-доставка (30 мин)'
    };
    return deliveryTypes[type] || type;
}