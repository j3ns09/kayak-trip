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

    document.querySelectorAll(".no-accommodation").forEach(noAccCheckbox => {
        noAccCheckbox.addEventListener("change", function() {
            const stopId = this.id.replace("no-acc-", "");
            const roomCheckboxes = document.querySelectorAll(
                `input[name="room-${stopId}[]"]`
            );

            if (this.checked) {
                roomCheckboxes.forEach(cb => {
                    cb.checked = false;
                    cb.disabled = true;
                });
            } else {
                roomCheckboxes.forEach(cb => {
                    cb.disabled = false;
                });
            }

            console.log(stopId);
            console.log(roomCheckboxes);
        });
    });    
});