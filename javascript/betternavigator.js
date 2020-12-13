// For reading cookies
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
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
    var BetterNavigator = document.getElementById("BetterNavigator");
    var BetterNavigatorStatus = document.getElementById("BetterNavigatorStatus");
    var BetterNavigatorLogoutLink = document.getElementById("BetterNavigatorLogoutLink");
    var BetterNavigatorLogoutForm = document.getElementById("LogoutForm_BetterNavigatorLogoutForm");

    // Toggle visibility of menu by clicking status
    BetterNavigatorStatus.onclick = function () {
        if (BetterNavigator.classList.contains('collapsed')) {
            BetterNavigator.classList.add('open');
            BetterNavigator.classList.remove('collapsed');
            document.cookie = "BetterNavigator=open;path=/";
        } else {
            BetterNavigator.classList.add('collapsed');
            BetterNavigator.classList.remove('open');
            document.cookie = "BetterNavigator=collapsed;path=/";
        }
    };

    // Restore menu state
    if (getCookie('BetterNavigator') === 'open') {
        BetterNavigator.classList.add('open');
        BetterNavigator.classList.remove('collapsed');
    }

    if (BetterNavigatorLogoutForm) {
        // Upgrade logout link to directly log users out instead of redirecting to logout form
        BetterNavigatorLogoutLink.onclick = function (e) {
            e.preventDefault();
            BetterNavigatorLogoutForm.submit();
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
