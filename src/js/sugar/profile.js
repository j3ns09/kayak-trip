export const renderToasts = () => {
    const errorToast = document.getElementById('errorToast');
    const successToast = document.getElementById('successToast');

    if (errorToast) {
        const t = bootstrap.Toast.getOrCreateInstance(errorToast);
        t.show();
    }

    if (successToast) {
        const t = bootstrap.Toast.getOrCreateInstance(successToast);
        t.show();
    }
};