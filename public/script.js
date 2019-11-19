// Set the cookie
function set_cookie(name, val) {
    var date = new Date();
    date.setTime(date.getTime() + (30 * 24 * 60 * 60 * 1000)); // the cookie will expires after 1 month
    var expires = "expires="+ date.toUTCString();
    document.cookie = name + "=" + val + ";" + expires + ";path=/";
}
// Get the cookie
function get_cookie(name) {
    name = name + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
        c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
        }
    }
    return "";
}
