const submitButton = document.getElementById('finalize-route');
const estimatedTimeHtml = document.getElementById('estimated-time-input');
const personCountHtml = document.getElementById('person-count');
const startDate = document.getElementById('travel-start');
const endDate = document.getElementById('travel-end');
const bagageOption = document.getElementById('chk-bag');
const foodOption = document.getElementById('chk-fd');
const locationOption = document.getElementById('chk-loc');

export function setDates() {
    const date = new Date();

    let day = date.getDate();
    let month = date.getMonth() + 1;
    if (month.toString().length < 2) {
        month = '0' + month;
    }
    let year = date.getFullYear();
    let currentDate = `${year}-${month}-${day}`;

    startDate.setAttribute('min', currentDate);
    startDate.value = currentDate;
}

export function submitForm() {
    submitButton.addEventListener('click', () => {
        getValues();
    });
}

function getValues() {
    const estimatedTime = estimatedTimeHtml.value;

    const start = new Date(startDate.value);
    const end = new Date(endDate.value);

    const desiredTime = (end - start) / (1000 * 60 * 60 * 24);

    console.log(estimatedTime, desiredTime);

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
    } else if (desiredTime < estimatedTime) {
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

    sendData(data);

    
}

function sendData(data) {
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