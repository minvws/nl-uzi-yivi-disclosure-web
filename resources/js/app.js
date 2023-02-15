import './bootstrap';
import { logout } from "./logout";


document.addEventListener("DOMContentLoaded", () => {
    // Add logout handler
    const logoutLink = document.querySelector("#logout");
    if (logoutLink) {
        logoutLink.addEventListener("click", logout);
    }
});