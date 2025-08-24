
const radios = document.querySelectorAll('.acc-radio');
const totalSpan = document.getElementById('total');

let total = parseFloat(totalSpan.textContent.replace("€", "").trim());

console.log(radios);

export function updateTotal() {
    let selectedAcc = {};

    radios.forEach(radio => {
        radio.addEventListener("change", () => {
            const stopName = radio.name;
            const price = parseFloat(radio.dataset.price);

            selectedAcc[stopName] = price;

            let newTotal = total;
            for (let key in selectedAcc) {
                newTotal += selectedAcc[key];
            }

            totalSpan.textContent = newTotal.toFixed(2) + " €";
        });
    });
}