import { updateTotal, updateQuantities } from './total/total.js';
import { submitForm } from './checkout/checkout.js';

document.addEventListener('DOMContentLoaded', async () => {
    updateTotal();
    updateQuantities();
    submitForm();
});