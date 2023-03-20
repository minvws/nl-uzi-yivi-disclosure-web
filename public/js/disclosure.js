'use strict';

function irmaStart(ura, title){
    const token = document.getElementsByName("_token")[0].value
    let options = {
        // Developer options
        debugging: true,

        // Front-end options
        language:  'en',
        translations: {
            header: title,
            loading: 'Just one second please!'
        },

        // Back-end options
        session: {
            url: 'http://localhost:8000/irma',
            start: {
                url: o => `${o.url}/start`,
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Content-Type": 'application/x-www-form-urlencoded;charset=UTF-8'
                },
                body: "ura=" + ura
            },
            result: false
        }
    };

    const irmaPopup = irma.newPopup({
        ...options,
        element: '#irma-web-form'
    });
    irmaPopup.start();
}