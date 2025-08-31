export function initHebergements() {
    const form = document.getElementById("form_hebergement");
    const chambresContainer = document.getElementById("chambres-container");
    let chambreIndex = 0;

    window.ajouterChambre = function () {
        chambreIndex++;
        const div = document.createElement("div");
        div.classList.add("row", "mb-2");
        div.innerHTML = `
            <div class="col-md-4">
                <input type="text" name="room[${chambreIndex}][room_name]" class="form-control" placeholder="Nom chambre (ex : Double)">
            </div>
            <div class="col-md-3">
                <input type="number" name="chambres[${chambreIndex}][capacity]" class="form-control" placeholder="Capacité" min="1">
            </div>
            <div class="col-md-3">
                <input type="number" name="chambres[${chambreIndex}][base_price]" class="form-control" placeholder="Prix / nuit" step="0.01" min="0">
            </div>
            <div class="col-md-2 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.closest('.row').remove()">X</button>
            </div>
        `;
        chambresContainer.appendChild(div);
    };

    form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        var result;
        try {
            const response = await fetch("/processes/accommodations/add_accommodation_process.php", {
                method: "POST",
                credentials: 'include',
                body: formData
            });

            result = await response.json();

            if (result.ok) {
                form.reset();
                chambresContainer.innerHTML = "";
                console.log(result);
            }
        } catch (err) {
            console.error("Erreur :", err);
        }

        window.location.reload();
    });
}
