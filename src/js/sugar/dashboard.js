export const renderToasts = () => {
    const errorToast = document.getElementById('errorToast');

    if (errorToast) {
        const t = bootstrap.Toast.getOrCreateInstance(errorToast);
        t.show();
    }

};