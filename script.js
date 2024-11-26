
//try and get all elemnts from the page. Some elements exist on different pages.
var p = document.getElementsByClassName('product');
let product = Array.from(p); 
var s = document.getElementsByClassName('Selector');
let selector = Array.from(s);
var PS = document.getElementsByClassName('ProductSearch');
let searchProductType = Array.from(PS);
var pb = document.getElementsByClassName('payeeSearchButton');
let payeeButton = Array.from(pb);
var payB = document.getElementsByClassName('payButton');
let payButton = Array.from(payB);
var detailsB = document.getElementsByClassName('detailsButton');
let detailsButton = Array.from(detailsB);

var addToCart = document.getElementById('addToCart');
if (addToCart != null)
{
    addToCart.addEventListener('click', addToCartClick);
}

var basket = document.getElementById('basketImage');
var basketContents;
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

var basketRemoveButton = document.getElementsByClassName('basketItemRemoveBox');
if (basketRemoveButton != null)
{
    let removeArr = Array.from(basketRemoveButton);
    removeArr.forEach(element => {
        element.addEventListener('click', basketRemove);
    });
}

basket.addEventListener('click', basketClick);


var resetSearch = document.getElementById('resetButton');
var payeeInput = document.getElementById('payeeInput');
var searchBar = document.getElementById('Search');

var quantityUp = document.getElementById('quantityUp');
var quantityDown = document.getElementById('quantityDown');

basketLoad()

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
    quantityUp.addEventListener('click', quantityAdjust);
    quantityDown.addEventListener('click', quantityAdjust);
}

if (searchBar != null){
    searchBar.addEventListener('keypress', searchProducts);
}

if (searchProductType != null){
    searchProductType.forEach(element => {
        addEventListener('click', searchProducts);
    })
}

if(selector != null){
    selector.forEach(element => {
        element.addEventListener('click', refineElements);
    })
}

if (payeeButton != null){
        payeeButton.forEach(element => {
            element.addEventListener('click', searchPayee);
        });
}

if (payeeInput != null){
    payeeInput.addEventListener('keypress', searchPayee);
}

if (payButton != null){
    payButton.forEach(element => {
        element.addEventListener('click', payStaff);
    });
}

if (detailsButton != null){
    detailsButton.forEach(element => {
        element.addEventListener('click', payeeDetails);
    });
}

if (resetSearch != null){
    resetSearch.addEventListener('click', resetSearchFields);
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
        location.replace("product.html?" + ID);
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
    console.log(quantityText);
    let quantity = Number(quantityText.innerHTML);
    if (quantityButton.id == 'quantityUp')
    {
        quantityText.innerHTML = quantity + 1;
    }
    else if (quantity > 1)
    {
        quantityText.innerHTML = quantity - 1;
    }
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

function addBasketContents(productId)
{
    basketContents.push(productId);
    localStorage.setItem('basketContents', JSON.stringify(basketContents));
}

function removeBasketContents(productId)
{
    var length = basketContents.length;
    for (let i = 0; i < length; i++)
    {
        let item = basketContents.shift();
        if (item != targetId)
        {
            basketContents.push(item);
        }
    }
    console.log(basketContents);
    localStorage.setItem('basketContents', JSON.stringify(basketContents));
}

function addToCartClick()
{
    let url = window.location.href;
    let splitUrl = url.split('?');
    let productId = splitUrl[1];

    addBasketContents(productId);

    console.log(basketContents);
}

function basketLoad()
{
    let basketContentsBox = document.getElementById('basketContentsBox');
    let totalBox = document.getElementById('basketTotalBox');
    if (basketContents.length == 0)
    {
        if (totalBox != null)
        {
            totalBox.remove();
        }
        let h2 = document.createElement('h2');
        h2.innerHTML = 'looks like your basket is empty...'
        basketContentsBox.appendChild(h2);
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
        addBuyNowHtml();
    }
}

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
    let body = document.getElementsByTagName('body')[0];

    let buyNowBox = document.createElement('div');
    buyNowBox.setAttribute('id', 'buyNowBox');
    buyNowBox.classList.add('button');
    let buyNowText = document.createElement('h1');
    buyNowText.innerHTML = "Buy Now";
    buyNowBox.appendChild(buyNowText);
    body.appendChild(buyNowBox);
    buyNowBox.addEventListener('click', displayCheckout);
}

async function displayCheckout(element)
{
    buyNow = element.currentTarget;
    await new Promise(r => setTimeout(r, 600));
    buyNow.remove();

    let body = document.getElementsByTagName('body')[0];

    let checkoutBox = document.createElement('div');
    checkoutBox.setAttribute('id', 'checkoutBox');
    body.appendChild(checkoutBox);

    let addressHeading = document.createElement('h2');
    addressHeading.innerHTML = 'Address';
    checkoutBox.appendChild(addressHeading);

    let addressOptionsBox = document.createElement('div');
    addressOptionsBox.setAttribute('id', 'addressOptionsBox');
    checkoutBox.appendChild(addressOptionsBox);

    let addressOption = document.createElement('div');
    addressOption.classList.add('addressOption');
    addressOptionsBox.appendChild(addressOption);

    let addressOptionName = document.createElement('h4');
    addressOptionName.classList.add('addressOptionName');
    addressOptionName.innerHTML = 'Address Option Name';
    addressOption.appendChild(addressOptionName);

    let addressOptionButton = document.createElement('div');
    addressOptionButton.classList.add('addressOptionButton');
    addressOption.appendChild(addressOptionButton);

    let addressOptionRadio = document.createElement('input');
    addressOptionRadio.setAttribute('type', 'radio');
    addressOptionButton.appendChild(addressOptionRadio);

    let paymentHeading = document.createElement('h2');
    paymentHeading.innerHTML = 'Payment';
    checkoutBox.appendChild(paymentHeading);

    let paymentOptionsBox = document.createElement('div');
    paymentOptionsBox.setAttribute('id', 'paymentOptionsBox');
    checkoutBox.appendChild(paymentOptionsBox);

    let paymentOption = document.createElement('div');
    paymentOption.classList.add('paymentOption');
    paymentOptionsBox.appendChild(paymentOption);

    let paymentOptionAcc = document.createElement('h4');
    paymentOptionAcc.classList.add('paymentOptionAcc');
    paymentOptionAcc.innerHTML = '**** **** **** 1234';
    paymentOption.appendChild(paymentOptionAcc);

    let paymentOptionButton = document.createElement('div');
    paymentOptionButton.classList.add('paymentOptionButton');
    paymentOption.appendChild(paymentOptionButton);

    let paymentOptionRadio = document.createElement('input');
    paymentOptionRadio.setAttribute('type', 'radio');
    paymentOptionButton.appendChild(paymentOptionRadio);

    let checkoutButton = document.createElement('div');
    checkoutButton.setAttribute('id', 'checkoutButton');
    checkoutButton.classList.add('button');
    checkoutButton.addEventListener('mouseenter', buttonMouseEnter);
    checkoutButton.addEventListener('mouseleave', buttonMouseOut);
    checkoutButton.addEventListener('click', buttonOnClick);
    checkoutButton.addEventListener('click', checkoutClick);
    body.appendChild(checkoutButton);

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

async function checkoutClick(element)
{
    await new Promise(r => setTimeout(r, 490));
    window.location.href = "orderComplete.html";
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
    totalText.innerHTML = "£" + total;

}

//This function is used to send the post request to the server.
function search(data, page){
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
    xhhtp.send(data);
}

//This function restricts the products to be shown on index to be only one of the types of product.
function refineElements(element){
    let data = element.srcElement.innerText;
    search("ProductSearch=" + encodeURIComponent(data), "index.php");
}


//This function is used to search for products on the index page.
function searchProducts(element){
    if(element.currentTarget.id == 'Search'){
        element.currentTarget.onkeypress = (element) => {
            if (element.key == 'Enter'){
                search("Search=" + encodeURIComponent(element.currentTarget.value), "index.php");
            }
        }
    }
}

//This function is used to search for a payee on the payroll page.
function searchPayee(element){
    if(element.currentTarget.id == 'payeeInput'){
        element.currentTarget.onkeypress = (element) => {
            if (element.key == 'Enter'){
                var data = element.currentTarget.value;
            }
        }
    }
    else{
        var data = document.getElementById("payeeInput").value;
    }
    search("PayeeSearch=" + encodeURIComponent(data), "payroll.php");
}

//This function is used to pay a staff member on the payroll page.
function payStaff(element){
    search("Pay="+encodeURIComponent(element.currentTarget.id), "payroll.php");
}

function payeeDetails(element){
    search("Details="+encodeURIComponent(element.currentTarget.id), "payroll.php");
}

function resetSearchFields(element){
    search("reset=1", "index.php");
}