var p = document.getElementsByClassName('product');
let product = Array.from(p);
var b = document.getElementsByClassName('button');
let button = Array.from(b);
console.log(product);

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
    element.currentTarget.style.boxShadow = 'inset 7.5px 7.5px #000000';
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
    button.onanimationend = async () => {
        await new Promise(r => setTimeout(r, 40));
        button.classList.remove('buttonClickDown');
        button.classList.add('buttonClickUp');
        button.onanimationend = () => {
            button.classList.remove('buttonClickUp');
        }
    }
}

function buttonMouseOut(element)
{
    let button = element.currentTarget;
    button.classList.add('buttonReturn');
    button.onanimationend = () => {
        button.classList.remove('buttonReturn');
        clearButtonListeners(button);
    }
}

function buttonMouseEnter(element)
{
    let button = element.currentTarget;
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
    else if (quantity > 0)
    {
        quantityText.innerHTML = quantity - 1;
    }
}