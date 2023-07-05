import './bootstrap';

import '@minvws/manon/collapsible';
import { onDomReady } from '@minvws/manon/utils';
import YiviPopup from '@privacybydesign/yivi-popup';
import YiviCore from '@privacybydesign/yivi-core';
import YiviClient from '@privacybydesign/yivi-client';

onDomReady(initYiviButtons);

function initYiviButtons()
{
    // For each yivi start button
    document.querySelectorAll('[data-yivi-start-button]')
        .forEach(function (button) {
            initYiviButton(button);
        });
}

/**
 * Initialize a yivi start button with click event listener
 * @param {Element} button
 */
function initYiviButton(button)
{
    // Get the data attributes
    const ura = button.getAttribute('data-yivi-ura');
    const title = button.getAttribute('data-yivi-title');
    const csrfToken = button.getAttribute('data-csrf-token');

    if (isEmpty(ura)) {
        console.error("Missing data-yivi-ura attribute on button");
        return;
    }

    if (isEmpty(csrfToken)) {
        console.error("Missing data-csrf-token attribute on button");
        return;
    }

    // Add a click event listener
    button.addEventListener('click', function () {
        // Start the yivi session
        yiviStart(ura, title, csrfToken);
    });
}

/**
 * Start a yivi session
 * @param {string} ura
 * @param {string} title
 * @param {string} csrfToken
 */
function yiviStart(ura, title, csrfToken)
{
    const yivi = new YiviCore({
        element: '#yivi-web-form',

        // Developer options
        debugging: import.meta.env.DEV,

        // Front-end options
        language:  document.documentElement.lang,

        // Back-end options
        session: {
            url: '/yivi-session',
            start: {
                url: o => `${o.url}/start`,
                method: 'POST',
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": 'application/x-www-form-urlencoded;charset=UTF-8'
                },
                body: "ura=" + ura
            },
            result: false
        }
    });

    yivi.use(YiviPopup);
    yivi.use(YiviClient);
    yivi.start();
}

/**
 * Check if the given data is empty
 * @param {string|undefined|null} data
 * @returns {boolean}
 */
function isEmpty(data)
{
    return data === "" || data === null || data === undefined;
}
