function ZenCMS_setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}
function ZenCMS_getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) != -1) return c.substring(name.length,c.length);
    }
    return "";
}
var close = ZenCMS_getCookie('ZenCMS-ADMIN-CP-NAV-CLOSE');
if (close == 1) {
    $(".ZenCMS-ADMIN-CP-NAV").hide();
    $(".ZenCMS-ADMIN-CP-BTN-OPEN").show();
}
$( ".ZenCMS-ADMIN-CP-BTN-CLOSE a" ).click(function() {
    $(".ZenCMS-ADMIN-CP-NAV").hide();
    $(".ZenCMS-ADMIN-CP-BTN-OPEN").show();
    ZenCMS_setCookie('ZenCMS-ADMIN-CP-NAV-CLOSE', 1, 365);
});
$( ".ZenCMS-ADMIN-CP-BTN-OPEN a" ).click(function() {
    $(".ZenCMS-ADMIN-CP-NAV").show();
    $(".ZenCMS-ADMIN-CP-BTN-OPEN").hide();
    ZenCMS_setCookie('ZenCMS-ADMIN-CP-NAV-CLOSE', 0, 365);
});
