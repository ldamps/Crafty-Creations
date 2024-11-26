
//try and get all elemnts from the page. Some elements exist on different pages.
var p = document.getElementsByClassName('product');
let product = Array.from(p); 
var b = document.getElementsByClassName('button');
let button = Array.from(b);
var s = document.getElementsByClassName('Selector');
let selector = Array.from(s);
var PS = document.getElementsByClassName('ProductSearch');
let POSTSENDProductType = Array.from(PS);
var pb = document.getElementsByClassName('payeeSearchButton');
let payeeButton = Array.from(pb);
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
var payeeInput = document.getElementById('payeeInput');
var POSTSENDBar = document.getElementById('Search');

var quantityUp = document.getElementById('quantityUp');
var quantityDown = document.getElementById('quantityDown');



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
        payeeButton.forEach(element => {
            element.addEventListener('click', POSTSENDPayee);
        });
}

if (payeeInput != null){
    payeeInput.addEventListener('keypress', POSTSENDPayee);
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
        
    }
}

//This function is used to send the post request to the server.
function POSTSEND(data, page){
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
    POSTSEND("PayeeSearch=" + encodeURIComponent(data), "payroll.php");
}

//This function is used to pay a staff member on the payroll page.
function payStaff(element){
    POSTSEND("Pay="+encodeURIComponent(element.currentTarget.id), "payroll.php");
}

function payeeDetails(element){
    POSTSEND("Details="+encodeURIComponent(element.currentTarget.id), "payroll.php");
}

function resetSearchFields(element){
    POSTSEND("reset=1", "index.php");
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
