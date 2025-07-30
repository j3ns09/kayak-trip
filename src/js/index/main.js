// import { loadContent } from "./pageLoader.js";
// import { refreshTabs } from "../../shared/js/tabManager.js";
// import { getCurrentPage, setCurrentPage } from "./storageUtils.js";
// import { checkFilterDivAttach } from "./filterButtons.js";
// import { listenPTButtons } from "./generalButtons.js";

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