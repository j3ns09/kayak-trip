document.addEventListener("DOMContentLoaded", () => {
    const logoutToast = document.getElementById('logoutToast');
    const loginToast = document.getElementById('loginToast');

    if (logoutToast) {
        const t = bootstrap.Toast.getOrCreateInstance(logoutToast);
        t.show();
    }

    if (loginToast) {
        const t = bootstrap.Toast.getOrCreateInstance(loginToast);
        t.show();
    }

});