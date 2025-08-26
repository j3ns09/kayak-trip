import { updateTotal, updateQuantities, recalculateTotal } from './total/total.js';
import { submitForm } from './checkout/checkout.js';
import { searchDiscount } from './discount_input/discount.js';
import { enableTooltips } from './tooltip/tooltip.js';

document.addEventListener('DOMContentLoaded', async () => {
    recalculateTotal();
    
    enableTooltips();
    updateTotal();
    updateQuantities();
    submitForm();

    await searchDiscount();
});