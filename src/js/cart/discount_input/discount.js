const searchDiscountButton = document.getElementById('cp-icon');
const discountGroup = document.getElementById('discount-group');
const discountInput = document.getElementById('discount-code');
const discountValue = document.getElementById('discount-value');
const iconContainer = document.getElementById('valid-icon');

const validIcon = '<i class="bi bi-check-circle-fill text-success"></i>';
const notValidIcon = '<i class="bi bi-x-circle-fill text-danger"></i>';
const formText = '<div class="form-text" id="form-text1">__desc__</div>';

let currentDiscountCode = null;

export async function searchDiscount() {
    await getDiscount();
}

function getDiscount() {
    searchDiscountButton.addEventListener('click', async () => {
        let discount = await getDiscountAPI(discountInput.value);
        const formTextHTML = document.getElementById('form-text1');
        if (formTextHTML) {
            formTextHTML.remove();
        }

        if (discount.ok) {
            iconContainer.innerHTML = validIcon;
            const reduction = parseInt(discount.discount.discount_value);
            const code = discount.discount.code;
            const description = discount.discount.description + ` (${reduction}%)`

            
            const newFormTextEl = document.createElement('div');
            newFormTextEl.innerHTML = modifyFormText(description);
            discountGroup.appendChild(newFormTextEl.firstChild);
            
            if (code !== currentDiscountCode) {
                currentDiscountCode = code;
                discountValue.value = reduction;
                discountValue.dispatchEvent(new Event('change'));
            }
        } else {
            iconContainer.innerHTML = notValidIcon;
            currentDiscountCode = null;
            discountValue.value = '';
            discountValue.dispatchEvent(new Event('change'));
        }
    });
}

async function getDiscountAPI(discountCode) {
    const response = await fetch(`/api/discounts/?code=${discountCode}`, {
        method: 'GET',
    });

    const data = await response.json();

    return data;
}

function modifyFormText(description) {
    const newFormText = formText.replace('__desc__', description);
    return newFormText;
}