import { getXSRFToken } from "./util";

export const logout = async () => {
    const logoutRoute = document.getElementById("logoutRoute").value;
    const response = await fetch(logoutRoute, {
        method: "POST",
        credentials: "same-origin",
        headers: {
            "X-XSRF-TOKEN": getXSRFToken(),
        },
        redirect: "follow",
    });
    if (response.ok) {
        window.location = "/";
    }
};
