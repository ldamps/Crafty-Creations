
//try and get all elemnts from the page. Some elements exist on different pages.
var p = document.getElementsByClassName('product');
let product = Array.from(p); 
var s = document.getElementsByClassName('Selector');
let selector = Array.from(s);
var PS = document.getElementsByClassName('ProductSearch');
let POSTSENDProductType = Array.from(PS);
var payB = document.getElementsByClassName('payButton');
let payButton = Array.from(payB);
var detailsB = document.getElementsByClassName('detailsButton');
let detailsButton = Array.from(detailsB);

var supplierB = document.getElementsByClassName('supplierButton');
let supplierButton = Array.from(supplierB);

var addSB = document.getElementsByClassName('addSupplierButton');
let addSupplierButton = Array.from(addSB);

var addToCart = document.getElementById('addToCart');
var basket = document.getElementById('basketImage');
var basketContents;

var resetSearch = document.getElementById('resetButton');
var PayeeSearchReset = document.getElementById('PayeeResetButton');
var payeeInput = document.getElementById('payeeInput');
var payeeButton = document.getElementById('payeeSearchButton');

var POSTSENDBar = document.getElementById('Search');

var quantityUp = document.getElementById('quantityUp');
var quantityDown = document.getElementById('quantityDown');

var searchButton = document.getElementById('searchButton');
searchButton.addEventListener('click', searchButtonClick);

var stockSearchinput = document.getElementById('stockSearchInput');
var stockSearchButton = document.getElementById('stockSearchButton');
var stockSearchReset = document.getElementById('stockResetSearch');

if (localStorage.getItem('basketContents') != null)
    {
        console.log('found');
        basketContents = JSON.parse(localStorage.getItem('basketContents'));
    }
    else
    {
        console.log('not found');
        basketContents = [];
    }

var b = document.getElementsByClassName('button');
let button = Array.from(b);

//Therefore, we need to check if the element exists before adding an event listener to it. Which is what the following if statements do.

if (product != null){
    product.forEach(element => {
        // creating unique image for each product
        // IDs are set from 0, and product IDs from 1, which is why there is a +1
        var SSID = Number(element.id) +1;
        src  = `images/${SSID}.png`;
        img  = element.querySelector("img");
        img.setAttribute("src", src);
        
        element.addEventListener('click', productOnClick, SSID);
    });
}

if (button != null){
    button.forEach(element => {
        element.addEventListener('click', buttonOnClick);
        element.addEventListener('mouseleave', buttonMouseOut);
        element.addEventListener('mouseenter', buttonMouseEnter);
    })
}

if (quantityUp != null){
    //quantityUp.addEventListener('click', quantityAdjust);
    //quantityDown.addEventListener('click', quantityAdjust);
}

if (POSTSENDBar != null){
    POSTSENDBar.addEventListener('keypress', POSTSENDProducts);
}

if (POSTSENDProductType != null){
    POSTSENDProductType.forEach(element => {
        addEventListener('click', POSTSENDProducts);
    })
}

if(selector != null){
    selector.forEach(element => {
        element.addEventListener('click', refineElements);
    })
}

if (payeeButton != null){
    payeeButton.addEventListener('click', POSTSENDPayee);
}

if (payeeInput != null){
    payeeInput.addEventListener('keypress', POSTSENDPayee);
}

if (payButton != null){
    payButton.forEach(element => {
        element.addEventListener('click', payStaff);
    });
}

if (PayeeSearchReset != null){
    PayeeSearchReset.addEventListener('click', resetPayeeSearch);
}

if (detailsButton != null){
    detailsButton.forEach(element => {
        element.addEventListener('click', payeeDetails);
    });
}

if (resetSearch != null){
    resetSearch.addEventListener('click', resetSearchFields);
}

if (basket != null){
    basket.addEventListener('click', basketClick);
}

if (addToCart != null)
    {
        addToCart.addEventListener('click', addToCartClick);
    }

if (supplierButton != null){
    supplierButton.forEach(element => {
        element.addEventListener('click', supplierDetails);
    });
}

if (addSupplierButton != null){
    addSupplierButton.forEach(element => {
        element.addEventListener('click', addSupplier);
    });
}

if (stockSearchButton != null){
    stockSearchButton.addEventListener('click', stockSearch);
}
if (stockSearchinput != null){
    stockSearchinput.addEventListener('keypress', stockSearch);
}
if (stockSearchReset != null){
    stockSearchReset.addEventListener('click', resetStockSearch);
}

//Functions of the event listeners

//When clicking on a product, the product is highlighted and the user is redirected to the product page.
function productOnClick(element)
{
    ID = Number(element.currentTarget.id)+Number(1);
    element.currentTarget.classList.add('productClick');
    element.currentTarget.style.boxShadow = '-4px -4px #000000';
    element.currentTarget.onanimationend = (element) => {
        element.currentTarget.classList.remove('productClick');
        location.replace("product.php?ID=" + ID);
        element.currentTarget.style.boxShadow = '';
    };
}

//When clicking on a button, the button is animated to show that it has been clicked.
function buttonOnClick(element)
{
    let button = element.currentTarget;
    button.classList.add('buttonClickDown');
    button.style.backgroundImage = "";
    button.onanimationend = async () => {
        await new Promise(r => setTimeout(r, 40));
        button.classList.remove('buttonClickDown');
        button.classList.add('buttonClickUp');
        if (!button.classList.contains('blank'))
        {
            button.style.backgroundImage = "url('rainbowTexture1.png')";
        }
        button.onanimationend = () => {
            button.classList.remove('buttonClickUp');
        }
    }
}

async function searchButtonClick()
{
    await new Promise(r => setTimeout(r, (450)));
    if (searchButton.innerHTML == 'X')
    {
        searchButton.innerHTML = '⌕'
    }
    else
    {
        searchButton.innerHTML = 'X'
    }
}

//When the mouse leaves the button, the button is animated to show that the mouse has left.
function buttonMouseOut(element)
{
    let button = element.currentTarget;
    button.style.backgroundImage = "";
    button.classList.add('buttonReturn');
    button.onanimationend = () => {
        button.classList.remove('buttonReturn');
        clearButtonListeners(button);
    }
}

//When the mouse enters the button, the button is animated to show that the mouse has entered.
function buttonMouseEnter(element)
{
    let button = element.currentTarget;
    if (!button.classList.contains('blank'))
        {
            button.style.backgroundImage = "url('rainbowTexture1.png')";
        }
    clearButtonListeners(button);
}

//This function removes all the classes from the button that are used for the animations.
function clearButtonListeners(button)
{
    button.classList.remove('buttonReturn');
    button.classList.remove('buttonClickUp');
    button.classList.remove('buttonClickDown');
    button.classList.remove('buttonMouseOut');
}

//This function adjusts the quantity of the product to be purchased.
function quantityAdjust(element)
{
    let quantityButton = element.currentTarget;
    let quantityText = document.getElementById('quantityText');
    let quantityInput = document.getElementById('quantityInput'); // hidden input field for quantity
    console.log(quantityText);
    let quantity = Number(quantityText.innerHTML);
    
    
    if (quantityButton.id == 'quantityUp') {
        quantity++; 
    } else if (quantity > 1) {
        quantity--; 
    }

    quantityText.innerHTML = quantity; // update the text on the page
    quantityInput.value = quantity;   // update the hidden input 
}


function basketClick(element)
{
    let url = "basket.html?" + basketContents[0];

    for (let i = 1; i < basketContents.length; i++)
    {
        url = url + ":" + basketContents[i];
    }
    location.replace(url);
    window.onload(basketLoad());
}

function updateBasketContents(productId)
{
    basketContents.push(productId);
    localStorage.setItem('basketContents', JSON.stringify(basketContents));
}

function addToCartClick()
{
    let url = window.location.href;
    let splitUrl = url.split('?');
    let productId = splitUrl[1];

    updateBasketContents(productId);

    console.log(basketContents);
}

function basketLoad()
{
    let basketContentsBox = document.getElementById('basketContentsBox');
    if (basketContents == [])
    {
        let h1 = document.createElement('h1');
        h1.innerHTML = 'looks like your basket is empty...'
        basketContentsBox.appendChild(h1);
    }
    else {
        //add contents of basket here, php is required though I think.
        const quantities = new Map()
        basketContents.forEach(element => {
            if (quantities.has(element)) {
                let quantity = quantities.get(element);
                quantities.set(element, quantity += 1);
            }
            else
            {
                quantities.set(element, 1);
            }
        });
        quantities.forEach (function(value, key) {
            addBasketItemHtml(key, value)
        })
        addTotalHtml();
        setTotal();
        addBasketAnimations();
        addBuyNowHtml();
    }
}

async function addBasketAnimations() {
    let basketItem = document.getElementsByClassName('basketItemBox');
    let total = document.getElementById('basketTotalBox');

    let basketItemArr = Array.from(basketItem);
    let count = -1;

    basketItemArr.forEach(async function(element) {
        count++;
        await new Promise(r => setTimeout(r, (100 * count)));
        element.classList.add('slideUp');
        element.onanimationend = () => {
            element.classList.remove('slideUp');
        }
    });
    await new Promise(r => setTimeout(r, (100 * count)));
    total.classList.add('slideUp');
    total.onanimationend = () => {
        total.classList.remove('slideUp');
    }
}

/**

function addBasketItemHtml(id, quantity)
{
    let basketContentsBox = document.getElementById('basketContentsBox');

    let basketItemBox = document.createElement('div');
    basketItemBox.classList.add('basketItemBox');
    basketItemBox.setAttribute('id', id);
    basketContentsBox.appendChild(basketItemBox);

    let basketItem = document.createElement('div');
    basketItem.classList.add('basketItem');
    basketItem.classList.add('button');
    basketItemBox.appendChild(basketItem);

    let basketItemInnerTable = document.createElement('div');
    basketItemInnerTable.classList.add('basketItemInnerTable');
    basketItem.appendChild(basketItemInnerTable);

    let basketItemImageBox = document.createElement('div');
    basketItemImageBox.classList.add('basketItemImageBox');
    basketItemInnerTable.appendChild(basketItemImageBox);

    let basketItemNameBox = document.createElement('div');
    basketItemNameBox.classList.add('basketItemNameBox');
    let basketItemName = document.createElement('h2');
    basketItemName.innerHTML = "Product Name";
    basketItemNameBox.appendChild(basketItemName);
    basketItemInnerTable.appendChild(basketItemNameBox);

    let basketQuantity = document.createElement('div');
    basketQuantity.classList.add('basketQuantity');
    let basketQuantityText = document.createElement('h1');
    basketQuantityText.innerHTML = quantity;
    basketQuantity.appendChild(basketQuantityText);
    basketItemInnerTable.appendChild(basketQuantity);

    let basketItemRemoveBox = document.createElement('div');
    basketItemRemoveBox.classList.add("basketItemRemoveBox");
    basketItemRemoveBox.classList.add("button");
    basketItemRemoveBox.setAttribute("id", id);
    basketItemRemoveBox.addEventListener('click', basketRemove);
    let basketItemRemove = document.createElement('div');
    basketItemRemove.classList.add('basketItemRemove');
    let basketItemRemoveText = document.createElement('h1');
    basketItemRemoveText.innerHTML = "X";
    basketItemRemove.appendChild(basketItemRemoveText);
    basketItemRemoveBox.appendChild(basketItemRemove);
    basketItemBox.appendChild(basketItemRemoveBox);

    let basketItemPriceBox = document.createElement('div');
    basketItemPriceBox.classList.add("basketItemPriceBox");
    let basketItemPrice = document.createElement('div');
    basketItemPrice.classList.add('basketItemPrice');
    let basketItemPriceText = document.createElement('h1');
    basketItemPriceText.innerHTML = "£12.49";
    basketItemPrice.appendChild(basketItemPriceText);
    basketItemPriceBox.appendChild(basketItemPrice);
    basketItemBox.appendChild(basketItemPriceBox);
}

 */

function addTotalHtml()
{
    let basketContentsBox = document.getElementById('basketContentsBox');

    let basketTotalBox = document.createElement('div');
    basketTotalBox.setAttribute('id', 'basketTotalBox');
    basketContentsBox.appendChild(basketTotalBox);

    let basketTotal = document.createElement('div');
    basketTotal.setAttribute('id', 'basketTotal');
    basketTotalBox.appendChild(basketTotal);

    let basketTotalText = document.createElement('div');
    basketTotalText.setAttribute('id', 'basketTotalText');
    let basketTotalTextH1 = document.createElement('h1');
    basketTotalTextH1.innerHTML = "Total";
    basketTotalText.appendChild(basketTotalTextH1);
    basketTotal.appendChild(basketTotalText);

    let basketTotalPrice = document.createElement('div');
    basketTotalPrice.setAttribute('id', 'basketTotalPrice');
    let basketTotalPriceH1 = document.createElement('h1');
    basketTotalPriceH1.innerHTML = "£00.00";
    basketTotalPrice.appendChild(basketTotalPriceH1);
    basketTotal.appendChild(basketTotalPrice);

}

function addBuyNowHtml()
{
    let box = document.getElementById('emptyBox');

    let buyNowBox = document.createElement('div');
    buyNowBox.setAttribute('id', 'buyNowBox');
    buyNowBox.classList.add('button');
    let buyNowText = document.createElement('h1');
    buyNowText.innerHTML = "Buy Now";
    buyNowBox.appendChild(buyNowText);
    box.appendChild(buyNowBox);
    buyNowBox.addEventListener('click', displayCheckout);
}

async function displayCheckout(element) {
    buyNow = element.currentTarget;
    await new Promise(r => setTimeout(r, 600));
    buyNow.remove();

    let box = document.getElementById('emptyBox');

    let checkoutBox = document.createElement('div');
    checkoutBox.setAttribute('id', 'checkoutBox');
    box.appendChild(checkoutBox);


    //address section
    let addressHeading = document.createElement('h2');
    addressHeading.innerHTML = 'Home Delivery';
    checkoutBox.appendChild(addressHeading);

    let addressOptionsBox = document.createElement('div');
    addressOptionsBox.setAttribute('id', 'addressOptionsBox');
    checkoutBox.appendChild(addressOptionsBox);
    

    if (userAddress && userAddress.length > 0) {
        userAddress.forEach(function(address) {
            let addressOption = document.createElement('div');
            addressOption.classList.add('addressOption');
            addressOptionsBox.appendChild(addressOption);
    
            let addressOptionName = document.createElement('h4');
            addressOptionName.classList.add('addressOptionName');
            addressOptionName.innerHTML = `${address.HouseNumber} ${address.StreetName}, ${address.City}, ${address.Postcode}`;
            addressOption.appendChild(addressOptionName);
    
            let addressOptionButton = document.createElement('div');
            addressOptionButton.classList.add('addressOptionButton');
            addressOption.appendChild(addressOptionButton);
    
            let addressOptionRadio = document.createElement('input');
            addressOptionRadio.setAttribute('type', 'radio');
            addressOptionRadio.setAttribute('name', 'deliveryOptionRadio'); // Radio buttons are grouped
            addressOptionRadio.setAttribute('value', `${address.HouseNumber} ${address.StreetName}, ${address.City}, ${address.Postcode}`);
            addressOptionButton.appendChild(addressOptionRadio);
        });
    } else {
        let noAddressMessage = document.createElement('p');
        noAddressMessage.classList.add('noAddressMessage');
        noAddressMessage.innerHTML = 'No addresses available for home delivery';
        addressOptionsBox.appendChild(noAddressMessage);
    }

    //collection section
    let collectionHeading = document.createElement('h2');
    collectionHeading.innerHTML = 'Collect in Store';
    checkoutBox.appendChild(collectionHeading);
    let collectionOptionsBox = document.createElement('div');
    collectionOptionsBox.setAttribute('id', 'collectionOptionsBox');
    checkoutBox.appendChild(collectionOptionsBox);


    if (availableShops && availableShops.length > 0) {
        availableShops.forEach(function(shop) {
            let collectionOption = document.createElement('div');
            collectionOption.classList.add('collectionOption');
            collectionOptionsBox.appendChild(collectionOption);

            let collectionOptionName = document.createElement('h4');
            collectionOptionName.classList.add('collectionOptionName');
            collectionOptionName.innerHTML = `${shop.StreetName}, ${shop.City}, ${shop.Postcode}`;
            collectionOption.appendChild(collectionOptionName);

            let collectionOptionButton = document.createElement('div');
            collectionOptionButton.classList.add('collectionOptionButton');
            collectionOption.appendChild(collectionOptionButton);

            let collectionOptionRadio = document.createElement('input');
            collectionOptionRadio.setAttribute('type', 'radio');
            collectionOptionRadio.setAttribute('name', 'deliveryOptionRadio'); // Group radio buttons
            collectionOptionRadio.setAttribute('value', `${shop.StreetName}, ${shop.City}, ${shop.Postcode}`);
            collectionOptionButton.appendChild(collectionOptionRadio);
        });
    } else {
        let noShopsMessage = document.createElement('p');
        noShopsMessage.classList.add('noShopsMessage');
        noShopsMessage.innerHTML = 'No shops available for collection';
        collectionOptionsBox.appendChild(noShopsMessage);
    }
    

  

    let paymentHeading = document.createElement('h2');
    paymentHeading.innerHTML = 'Payment';
    checkoutBox.appendChild(paymentHeading);

    let paymentOptionsBox = document.createElement('div');
    paymentOptionsBox.setAttribute('id', 'paymentOptionsBox');
    checkoutBox.appendChild(paymentOptionsBox);

    // Check if userPayments is not empty or undefined
if (userPayments && userPayments.length > 0) {
    userPayments.forEach(function (payment) {
        let paymentOption = document.createElement('div');
        paymentOption.classList.add('paymentOption');
        paymentOptionsBox.appendChild(paymentOption);

        let paymentOptionAcc = document.createElement('h4');
        paymentOptionAcc.classList.add('paymentOptionAcc');
        let cardLastFour = String(payment.CardNumber).slice(-4); // Get the last 4 digits of the CardNumber
        paymentOptionAcc.innerHTML = `**** **** **** ${cardLastFour}`;
        paymentOption.appendChild(paymentOptionAcc);

        let paymentOptionButton = document.createElement('div');
        paymentOptionButton.classList.add('paymentOptionButton');
        paymentOption.appendChild(paymentOptionButton);

        let paymentOptionRadio = document.createElement('input');
        paymentOptionRadio.setAttribute('type', 'radio');
        paymentOptionRadio.setAttribute('name', 'paymentOption'); // Radio buttons are grouped
         paymentOptionRadio.setAttribute('value', `${cardLastFour}`);
        paymentOptionButton.appendChild(paymentOptionRadio);
    });
} else {
    let noPaymentMessage = document.createElement('p');
    noPaymentMessage.classList.add('noPaymentMessage');
    noPaymentMessage.innerHTML = 'No saved payment methods available.';
    paymentOptionsBox.appendChild(noPaymentMessage);
}

    let checkoutButton = document.createElement('div');
    checkoutButton.setAttribute('id', 'checkoutButton');
    checkoutButton.classList.add('button');
    checkoutButton.addEventListener('mouseenter', buttonMouseEnter);
    checkoutButton.addEventListener('mouseleave', buttonMouseOut);
    checkoutButton.addEventListener('click', buttonOnClick);
    checkoutButton.addEventListener('click', checkoutClick);
    box.appendChild(checkoutButton);

    let checkoutButtonText = document.createElement('h1');
    checkoutButtonText.innerHTML = 'Checkout';
    checkoutButton.appendChild(checkoutButtonText);

    checkoutBox.classList.add('slideUp');
    checkoutButton.classList.add('slideUp');
    checkoutBox.onanimationend = () => {
        checkoutBox.classList.remove('slideUp');
        checkoutButton.classList.remove('slideUp');
    }
}

async function checkoutClick(element) {
    // Get selected options
    let addressSelected = document.querySelector('input[name="deliveryOptionRadio"]:checked');
    let paymentSelected = document.querySelector('input[name="paymentOption"]:checked');

    if (!addressSelected) {
        alert('Please select a delivery option.');
        return;
    }

    if (!paymentSelected) {
        alert('Please select a payment option.');
        return;
    }

    // get the selected values from the radio buttons
    let deliveryOption = addressSelected.value; 
    let paymentOption = paymentSelected.value;  

    // debug
    console.log('Selected Delivery Option:', deliveryOption);
    console.log('Selected Payment Option:', paymentOption);

    try {
        // send the order info to basket.php for processing
        let response = await fetch('basket.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `deliveryOption=${encodeURIComponent(deliveryOption)}&paymentOption=${encodeURIComponent(paymentOption)}` 
        });

        // debug
        console.log('Response from basket.php:', response.status);
        const responseBody = await response.text();
        console.log('Response body from basket.php:', responseBody);

        if (!response.ok) {
            console.error('Failed to send data to basket.php:', response.statusText);
            alert('There was an error processing your order.');
            return;  
        }

        console.log('Data sent to basket.php successfully');
      
        //setTimeout(function() {
            //window.location.href = 'orderComplete.php';
        //}, 10000);
        window.location.href = 'orderComplete.php';


    } catch (error) {
        console.error('Error sending order info to basket.php:', error);
        alert('There was an error sending the order information.');
    }
}



async function basketRemove(element)
{
    targetId = element.currentTarget.id;
    await new Promise(r => setTimeout(r, 490));
    let basketItem = document.getElementsByClassName('basketItemBox');
    let itemArr = Array.from(basketItem);
    itemArr.forEach(element => {
        if (element.id == targetId)
        {
            console.log(element);
            element.remove();
            removeBasketContents(targetId);
        }
    });
    if (basketContents.length == 0)
    {
        basketLoad();
    }
    setTotal();
}

/* 
function setTotal()
{
    let totalDiv = document.getElementById('basketTotalPrice');
    let totalText = totalDiv.children[0];
    var total = 0;
    let priceBoxArr = Array.from(document.getElementsByClassName('basketItemPrice'));
    if (priceBoxArr.length != 0)
    {
        priceBoxArr.forEach(element => {
            let rawPrice = element.children[0].innerHTML;
            let price = rawPrice.replace('£', "");
            total = total + parseFloat(price);
        });
    }
    else
    {
        total = '00.00';
    }
    totalText.innerHTML = '£' + total;
}

*/
//This function is used to send the post request to the server.
function POSTSEND(data, page){
    console.log(data);
    const xhhtp = new XMLHttpRequest();
    xhhtp.open('POST', page, true);
    xhhtp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    // xhhtp.onreadystatechange = function () {
    //     if (xhhtp.readyState === XMLHttpRequest.DONE) {
    //         console.log(xhhtp.status);
    //         if (xhhtp.status === 200) {
    //             console.log(xhhtp.responseText);
    //         }
    //         else {
    //             console.log(xhhtp.status);
    //         }
    //     }
    // }
    console.log(data);

    xhhtp.send(data);
}

//This function restricts the products to be shown on index to be only one of the types of product.
function refineElements(element){
    let data = element.srcElement.innerText;
    POSTSEND("ProductSearch=" + encodeURIComponent(data), "index.php");
}


//This function is used to POSTSEND for products on the index page.
function POSTSENDProducts(element){
    if(element.currentTarget.id == 'Search'){
        element.currentTarget.onkeypress = (element) => {
            if (element.key == 'Enter'){
                POSTSEND("Search=" + encodeURIComponent(element.currentTarget.value), "index.php");
            }
        }
    }
}

//This function is used to POSTSEND for a payee on the payroll page.
function POSTSENDPayee(element){
    var data;
    if(element.currentTarget.id == 'payeeInput'){
        element.currentTarget.onkeypress = (element) => {
            if (element.key == 'Enter'){
                // console.log(element.currentTarget.value);

                data = element.currentTarget.value;
                console.log(data);
            }
        }
    }
    else if ( element.currentTarget.id === 'payeeSearchButton'){
        data = document.getElementById("payeeInput").value;
        console.log(data);
    }

    if(typeof data === 'undefined'){
        console.log("Undefined");
    }
    else{
        console.log("aaa");
        POSTSEND("PayeeSearch=" + encodeURIComponent(data), "payroll.php");
    }
}

//This function is used to pay a staff member on the payroll page.
function payStaff(element){
    POSTSEND("Pay="+encodeURIComponent(element.currentTarget.id), "payroll.php");
}

var payrollDetailsHover = false;

function payeeDetails(element){
    POSTSEND("Details="+encodeURIComponent(element.currentTarget.id), "payroll.php");
    var details = document.getElementById('payrollDetails');
    details.classList.add('slideUp');
    details.onanimationend = () => {
        details.classList.remove('slideUp');
    }
    button.forEach(element => {
        if (!element.id.classList.contains('payButton'))
        {
            clearButtonListeners('payButton');
        }
    });
    var table = document.getElementsByClassName('section')[0];
    table.style.opacity = '0.5';
    var nav = document.getElementsByTagName('nav')[0];
    var nav1 = document.getElementsByTagName('nav')[1];
    nav.style.opacity = '0.5';
    nav1.style.opacity = '0.5';

    details.addEventListener('mouseenter', function(){payrollDetailsHover = true; console.log(payrollDetailsHover);});
    details.addEventListener('mouseleave', function(){payrollDetailsHover = false; console.log(payrollDetailsHover);});

    document.body.addEventListener('click', hidePayrollDetails);

}

function hidePayrollDetails() {
    var details = document.getElementById('payrollDetails');
    if (payrollDetailsHover == false)
    {
        details.remove();
    }
    document.body.removeEventListener('click', hidePayrollDetails);
    button.forEach(element => {
        element.addEventListener('click', buttonOnClick);
        element.addEventListener('mouseleave', buttonMouseOut);
        element.addEventListener('mouseenter', buttonMouseEnter);
    });
}

function resetSearchFields(element){
    POSTSEND("reset=1", "index.php");
}

function resetPayeeSearch(element){
    POSTSEND("reset=1", "payroll.php");
}

function supplierDetails(element){
    POSTSEND("supplierDetails="+encodeURIComponent(element.currentTarget.id), "suppliers.php");
}

function addSupplier(element){
    var newSupplier = [];
    newSupplier.push(document.getElementById('addNewSupplierName').value);
    newSupplier.push(document.getElementById('addNewSupplierType').value);
    newSupplier.push(document.getElementById('addNewSupplierEmail').value);
    newSupplier.push(document.getElementById('addNewSupplierAddress').value);
    if(newSupplier.includes('')){
        alert('Please fill in all fields');
        return;
    }
    console.log(newSupplier);
    POSTSEND("addSupplier="+encodeURIComponent(newSupplier), "addNewSupplier.php");
}

function stockSearch(element){
    console.log("stockSearch");
    if(element.currentTarget.id == 'stockSearchButton'){
        POSTSEND("stockSearch="+encodeURIComponent(stockSearchinput.value), "stockPage.php");
    }
    else if (element.key == 'Enter'){
        POSTSEND("stockSearch="+encodeURIComponent(stockSearchinput.value), "stockPage.php");
    }
}
function resetStockSearch(element){
    POSTSEND("reset=1", "stockPage.php");
}   