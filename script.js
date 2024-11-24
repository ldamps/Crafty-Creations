var p = document.getElementsByClassName('product');
let product = Array.from(p);
var b = document.getElementsByClassName('button');
let button = Array.from(b);
var s = document.getElementsByClassName('Selector');
let selector = Array.from(s);
var PS = document.getElementsByClassName('ProductSearch');
let searchProductType = Array.from(PS);
var pb = document.getElementsByClassName('payeeSearchButton');
let payeeButton = Array.from(pb);
var payB = document.getElementsByClassName('payButton');
let payButton = Array.from(payB);

var payeeInput = document.getElementById('payeeInput');
var searchBar = document.getElementById('Search');
var quantityUp = document.getElementById('quantityUp');
var quantityDown = document.getElementById('quantityDown');





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
    })
}

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

function buttonOnClick(element)
{
    let button = element.currentTarget;
    button.classList.add('buttonClickDown');
    button.style.backgroundImage = "";
    button.onanimationend = async () => {
        await new Promise(r => setTimeout(r, 40));
        button.classList.remove('buttonClickDown');
        button.classList.add('buttonClickUp');
        button.style.backgroundImage = "url('rainbowTexture1.png')";
        button.onanimationend = () => {
            button.classList.remove('buttonClickUp');
        }
    }
}

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

function buttonMouseEnter(element)
{
    let button = element.currentTarget;
    button.style.backgroundImage = "url('rainbowTexture1.png')";
    clearButtonListeners(button);
}

function clearButtonListeners(button)
{
    button.classList.remove('buttonReturn');
    button.classList.remove('buttonClickUp');
    button.classList.remove('buttonClickDown');
    button.classList.remove('buttonMouseOut');
}

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

function search(data, page){
    const xhhtp = new XMLHttpRequest();
    xhhtp.open('POST', page, true);
    xhhtp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhhtp.onreadystatechange = function () {
        if (xhhtp.readyState === XMLHttpRequest.DONE) {
            console.log(xhhtp.status);
            if (xhhtp.status === 200) {
                console.log(xhhtp.responseText);
            }
            else {
                console.log(xhhtp.status);
            }
        }
    }
    xhhtp.send(data);
}

function refineElements(element){
    let data = element.srcElement.innerText;
    search("Search=" + encodeURIComponent(data), "index.php");
}
    
function searchProducts(element){
    // console.log("string");
    // console.log(element.currentTarget);
    if(element.currentTarget.id == 'Search'){
        // console.log(element.currentTarget.value);
        element.currentTarget.onkeypress = (element) => {
            console.log(element.key);
            if (element.key == 'Enter'){
                // console.log(element.currentTarget.value);
                search("Search=" + encodeURIComponent(element.currentTarget.value), "index.php");
            }
        }
    }

    if(element.currentTarget.id == 'ProductSearch'){
        console.log(element.currentTarget.value);
        search("ProductSearch=" + encodeURIComponent(element.currentTarget.value));
    }
}

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
    console.log(data);
    search("PayeeSearch=" + encodeURIComponent(data), "payroll.php");
}

function payStaff(element){
    search("Pay="+encodeURIComponent(element.currentTarget.id), "payroll.php");
}
