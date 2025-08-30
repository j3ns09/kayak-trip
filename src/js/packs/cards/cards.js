const cardRegion = document.getElementById('card-region');

const cardTemplate = `
<div class="card col" style="width: 18rem;">
    <img src="" class="card-img-top" alt="">
    <div class="card-body d-flex flex-column">
        <h5 class="card-title">__name__</h5>
        <p class="card-text">__desc__</p>
        <a href="pack-details.php?pack_id=__id__" class="btn btn-primary mt-auto">Détails</a>
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
    let shortDesc = pack.description.length > 100 ? pack.description.substring(0, 100) + '...' : pack.description;
    let card = cardTemplate
        .replace('__name__', pack.name)
        .replace('__desc__', shortDesc)
        .replace('__id__', pack.id);
    return card;
}

async function getPacks() {
    const response = await fetch("/api/packs/", {
        method: 'GET'
    });

    const data = await response.json();
    
    if (!data.ok) throw Error("Packs non ok" + data.error);
    
    return data.packs;
}
