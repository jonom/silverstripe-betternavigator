//For reading cookies
function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)===' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) !== -1) {
            return c.substring(name.length,c.length);
        }
    }
    return "";
}

function initialiseBetterNavigator() {
    //Dom elements
    var BetterNavigator = document.getElementById("BetterNavigator");
    var BetterNavigatorStatus = document.getElementById("BetterNavigatorStatus");

    //Toggle visibility of menu by clicking status
    BetterNavigatorStatus.onclick = function () {
        BetterNavigator.className = BetterNavigator.className === 'collapsed' ? 'open' : 'collapsed';
        //Set cookie to remember state
        document.cookie = "BetterNavigator=" + BetterNavigator.className + ";path=/";
    };

    //Restore menu state
    if (getCookie('BetterNavigator') === 'open') {
        BetterNavigator.className = 'open';
    }
}

if (document.addEventListener) {
    document.addEventListener("DOMContentLoaded", function () {
        //wait til DOM is ready
        initialiseBetterNavigator();
    });
} else {
    //This is the case for IE8 and below
    //initialise straight away - fine if script is loaded after BN dom element
    initialiseBetterNavigator();
}
