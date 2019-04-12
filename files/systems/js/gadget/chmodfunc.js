function getKeyPress(e) {

    var keynum;

    if (window.event) { // IE
        keynum = e.keyCode;

    } else if (e.which) { // Netscape/Firefox/Opera

        keynum = e.which;
    }
    return String.fromCharCode(keynum);
}

function cleanperm(e, id) {

    var idr = id + 'r';
    var idw = id + 'w';
    var idx = id + 'x';

    document.getElementById(id).value = '';
    document.getElementById(idr).checked = false;
    document.getElementById(idw).checked = false;
    document.getElementById(idx).checked = false;

}
function calupperm(e, id) {

    var idr = id + 'r';
    var idw = id + 'w';
    var idx = id + 'x';

    var val = getKeyPress(e);

    if (val == 0) {

        document.getElementById(idr).checked = false;
        document.getElementById(idw).checked = false;
        document.getElementById(idx).checked = false;

    } else if (val == 1) {

        document.getElementById(idr).checked = false;
        document.getElementById(idw).checked = false;
        document.getElementById(idx).checked = true;

    } else if (val == 2) {

        document.getElementById(idr).checked = false;
        document.getElementById(idw).checked = true;
        document.getElementById(idx).checked = false;

    } else if (val == 3) {

        document.getElementById(idr).checked = false;
        document.getElementById(idw).checked = true;
        document.getElementById(idx).checked = true;

    } else if (val == 4) {

        document.getElementById(idr).checked = true;
        document.getElementById(idw).checked = false;
        document.getElementById(idx).checked = false;

    } else if (val == 5) {

        document.getElementById(idr).checked = true;
        document.getElementById(idw).checked = false;
        document.getElementById(idx).checked = true;

    } else if (val == 6) {

        document.getElementById(idr).checked = true;
        document.getElementById(idw).checked = true;
        document.getElementById(idx).checked = false;

    } else if (val == 7) {

        document.getElementById(idr).checked = true;
        document.getElementById(idw).checked = true;
        document.getElementById(idx).checked = true;

    } else {

        document.getElementById(idr).checked = false;
        document.getElementById(idw).checked = false;
        document.getElementById(idx).checked = false;
    }
    if (document.getElementById(id).value) {

        cleanperm(id);
    }
}
function calcperm() {

    document.getElementById("u").value = 0;
    if (document.getElementById('ur').checked) {
        document.getElementById("u").value = document.getElementById("u").value * 1 + document.getElementById("ur").value * 1;
    }
    if (document.getElementById('uw').checked) {
        document.getElementById("u").value = document.getElementById("u").value * 1 + document.getElementById("uw").value * 1;
    }
    if (document.getElementById("ux").checked) {
        document.getElementById("u").value = document.getElementById("u").value * 1 + document.getElementById("ux").value * 1;
    }

    document.getElementById("g").value = 0;
    if (document.getElementById('gr').checked) {
        document.getElementById("g").value = document.getElementById("g").value * 1 + document.getElementById("gr").value * 1;
    }
    if (document.getElementById('gw').checked) {
        document.getElementById("g").value = document.getElementById("g").value * 1 + document.getElementById("gw").value * 1;
    }
    if (document.getElementById("gx").checked) {
        document.getElementById("g").value = document.getElementById("g").value * 1 + document.getElementById("gx").value * 1;
    }

    document.getElementById("w").value = 0;
    if (document.getElementById('wr').checked) {
        document.getElementById("w").value = document.getElementById("w").value * 1 + document.getElementById("wr").value * 1;
    }
    if (document.getElementById('ww').checked) {
        document.getElementById("w").value = document.getElementById("w").value * 1 + document.getElementById("ww").value * 1;
    }
    if (document.getElementById("wx").checked) {
        document.getElementById("w").value = document.getElementById("w").value * 1 + document.getElementById("wx").value * 1;
    }
}