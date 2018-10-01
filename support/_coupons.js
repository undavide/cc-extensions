// Plain text for the coupon page
function clickHandler() {
    var baseURL = "http://sites.fastspring.com/barranca/product/";
    var product = document.getElementById('product').value;
    var coupon = document.getElementById('coupon').value;
    var email = document.getElementById('email_address').value;

    if (email != "") { return; }
    var productURL = baseURL + product + "?coupon=" + coupon;

    if (product == "" || coupon == "") {
        alert("Warning!\n\nPlease make sure to have selected a product, and entered a coupon!")
        return;
    }

    window.open(productURL);

    var txt = "Clicking the blue button above should have opened a new webpage, where you can acquire the discounted product. In case you have problems, please point your browser to: <br/><br/>" +
    "<b><a href='" + productURL + "'>" + productURL + "</a></b>"

    document.getElementById('confirmation_text').innerHTML = txt;
}
