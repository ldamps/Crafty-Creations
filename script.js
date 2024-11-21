var p = document.getElementsByClassName('product');
let product = Array.from(p);
var b = document.getElementsByClassName('button');
let button = Array.from(b);
console.log(product);

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

basket.addEventListener('click', basketClick);

var quantityUp = document.getElementById('quantityUp');
var quantityDown = document.getElementById('quantityDown');

if (product != null)
{
    product.forEach(element => {
        element.addEventListener('click', productOnClick);
    });
}

if (button != null)
{
    button.forEach(element => {
        element.addEventListener('click', buttonOnClick);
        element.addEventListener('mouseleave', buttonMouseOut);
        element.addEventListener('mouseenter', buttonMouseEnter);
    })
}

if (quantityUp != null)
{
    quantityUp.addEventListener('click', quantityAdjust);
    quantityDown.addEventListener('click', quantityAdjust);
}

function productOnClick(element)
{
    element.currentTarget.classList.add('productClick');
    element.currentTarget.style.boxShadow = '-4px -4px #000000';
    element.currentTarget.onanimationend = (element) => {
        element.currentTarget.classList.remove('productClick');
        location.replace("product.html?" + element.currentTarget.id);
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
        if (!button.classList.contains('blank'))
        {
            button.style.backgroundImage = "url('rainbowTexture1.png')";
        }
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
    if (!button.classList.contains('blank'))
        {
            button.style.backgroundImage = "url('rainbowTexture1.png')";
        }
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