const cardRegion = document.getElementById('card-region');

const cardTemplate = `
<div class="card col" style="width: 18rem;">
    <img src="" class="card-img-top" alt="">
    <div class="card-body">
        <h5 class="card-title">__name__</h5>
        <p class="card-text">__desc__</p>
        <a href="#" class="btn btn-primary">Détails</a>
    </div>
</div>
`;

export async function renderCards() {
    const packs = await getPacks();

    for (const pack of packs) {
        let wrapper = document.createElement('div');
        
        wrapper.innerHTML = makeCard(pack);

        cardRegion.appendChild(wrapper);
    }
}

function makeCard(pack) {
    let card = cardTemplate.replace('__name__', pack.name).replace('__desc__', pack.description);
    return card;
}

async function getPacks() {
    const response = await fetch("/api/packs/", {
            method: 'GET'
    });

    const data = await response.json();
    
    if (!data.ok) throw Error("Packs non ok" + packs);
    
    const packs = data.packs;

    return packs;
}