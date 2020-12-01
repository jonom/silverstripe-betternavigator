// For reading cookies
function getCookie(cname) {
    let name = cname + "=";
    let ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) !== -1) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

function initialiseBetterNavigator() {
    // Dom elements
    const betterNavigator = document.getElementById("BetterNavigator");
    const betterNavigatorStatus = document.getElementById("BetterNavigatorStatus");
    const betterNavigatorLogoutLink = document.getElementById("BetterNavigatorLogoutLink");
    const betterNavigatorLogoutForm = document.getElementById("LogoutForm_BetterNavigatorLogoutForm");

    // Toggle visibility of menu by clicking status
    betterNavigatorStatus.onclick = function () {
        if (betterNavigator.classList.contains('collapsed')) {
            betterNavigator.classList.add('open');
            betterNavigator.classList.remove('collapsed');
            document.cookie = "BetterNavigator=open;path=/";
        } else {
            betterNavigator.classList.add('collapsed');
            betterNavigator.classList.remove('open');
            document.cookie = "BetterNavigator=collapsed;path=/";
        }
    };

    // Restore menu state
    if (getCookie('BetterNavigator') === 'open') {
        betterNavigator.classList.add('open');
        betterNavigator.classList.remove('collapsed');
    }

    if (betterNavigatorLogoutForm) {
        // Upgrade logout link to directly log users out instead of redirecting to logout form
        betterNavigatorLogoutLink.onclick = function (e) {
            e.preventDefault();
            betterNavigatorLogoutForm.submit();
        };
    }
}

if (document.addEventListener && document.readyState === 'loading') {
    document.addEventListener("DOMContentLoaded", function () {
        // wait til DOM finishes loading
        initialiseBetterNavigator();
    });
} else {
    // This is the case for IE8 and below OR already loaded document (e.g. when using async)
    // initialise straight away - fine if script is loaded after BN dom element
    initialiseBetterNavigator();
}
