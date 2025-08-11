const submitButton = document.getElementById('finalize-route');
const estimatedTimeHtml = document.getElementById('estimated-time-input');
const personCountHtml = document.getElementById('person-count');
const travelTime = document.getElementById('travel-time');
const bagageOption = document.getElementById('chk-bag');
const foodOption = document.getElementById('chk-fd');
const locationOption = document.getElementById('chk-loc');

export function submitForm() {
    submitButton.addEventListener('click', () => {
        getValues();
    });
}

function getValues() {
    const estimatedTime = JSON.parse(estimatedTimeHtml.value);
    const hours = estimatedTime.hours + estimatedTime.minutes / 60;
    const desiredTime = travelTime.value;

    const value = personCountHtml.value.trim();
    const personCount = Number(value);

    if (!Number.isFinite(personCount) || personCount <= 0) {
        alert("Merci d'entrer un nombre valide dans le champ du nombre de participants");
        return;
    }
    
    // TODO : UX
    if (desiredTime > 10) {
        alert("Pour le moment, nous ne proposons que des voyages de 10 jours maximum.");
        return;
    } else if (desiredTime < hours / 24) {
        alert("Le temps de voyage désiré est trop court. Veuillez vous référer au temps estimé.")
        return;
    }

    const bagage = bagageOption.checked;
    const food = foodOption.checked;
    const location = locationOption.checked;

    const data = {
        desiredTime: desiredTime,
        personCount: personCount,
        bagage: bagage,
        food: food,
        location: location
    };

    fetch('/api/cart/', {
        method: 'POST',
        credentials: 'include',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        console.log("Réponse du serveur: ", result);
        if (result.ok) {
            window.location.href = "/cart.php";
        } else {
            alert("Erreur lors de l'enregistrement des données.");
        }
    })
    .catch(error => console.error('Erreur :', error));
}