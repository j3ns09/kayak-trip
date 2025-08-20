export const renderToasts = () => {
    const logoutToast = document.getElementById('logoutToast');
    const loginToast = document.getElementById('loginToast');
    const errorToast = document.getElementById('errorToast');

    if (logoutToast) {
        const t = bootstrap.Toast.getOrCreateInstance(logoutToast);
        t.show();
    }

    if (loginToast) {
        const t = bootstrap.Toast.getOrCreateInstance(loginToast);
        t.show();
    }

    if (errorToast) {
        const t = bootstrap.Toast.getOrCreateInstance(errorToast);
        t.show();
    }

};