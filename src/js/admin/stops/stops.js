const addStopBtn = document.getElementById('add-stop');
const selectsDiv = document.getElementById('selects-container');

function attachAccommodationLoader(stopSelect, accommodationSelect) {
    stopSelect.addEventListener("change", async () => {
        const stopId = stopSelect.value;
        if (!stopId) {
            accommodationSelect.innerHTML = '<option value="">Choisissez un hébergement</option>';
            return;
        }

        try {
            const response = await fetch(`/api/accommodations/?stop_id=${stopId}`);
            if (!response.ok) throw new Error("Erreur API");

            let accommodations = await response.json();

            accommodationSelect.innerHTML = '<option value="">Choisissez un hébergement</option>';

            console.log(accommodations);
            accommodations = accommodations.accommodations;
            accommodations.forEach(acc => {
                const opt = document.createElement("option");
                opt.value = acc.id;
                opt.textContent = acc.name;
                accommodationSelect.appendChild(opt);
            });
        } catch (err) {
            console.error("Erreur chargement hébergements:", err);
            accommodationSelect.innerHTML = '<option value="">(Erreur de chargement)</option>';
        }
    });
}

export function addDiv() {
    addStopBtn.addEventListener("click", (e) => {
        e.preventDefault();

        const row = document.createElement("div");
        row.classList.add("row", "mt-2");

        const stopSelectDiv = document.createElement("div");
        stopSelectDiv.classList.add("col-sm-3");
        const stopSelect = document.querySelector(".selects-stops").cloneNode(true);
        stopSelect.name = "stop_id[]";
        stopSelectDiv.appendChild(stopSelect);

        const accSelectDiv = document.createElement("div");
        accSelectDiv.classList.add("col-sm-3");
        const accSelect = document.createElement("select");
        accSelect.classList.add("form-control", "selects-accommodations");
        accSelect.name = "accommodation_id[]";
        accSelect.innerHTML = '<option value="">Choisissez un hébergement</option>';
        accSelectDiv.appendChild(accSelect);

        row.appendChild(stopSelectDiv);
        row.appendChild(accSelectDiv);
        selectsDiv.appendChild(row);

        attachAccommodationLoader(stopSelect, accSelect);
    });

    const firstStop = document.querySelector(".selects-stops");
    const firstAcc = document.querySelector(".selects-accommodations");
    if (firstStop && firstAcc) {
        attachAccommodationLoader(firstStop, firstAcc);
    }
}
