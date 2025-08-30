document.addEventListener('DOMContentLoaded', async () => {
    const newsletterForm = document.getElementById('newsletter-form');

    if (newsletterForm) {
        newsletterForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const id = document.getElementById('iduser').value;
    
            console.log(id);
        
            const res = await fetch('/api/newsletter/', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id })
            });
    
            const data = await res.json();
    
            if (data.ok) {
                window.location.reload();
            }
        });
        
    }
});