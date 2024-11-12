var p = document.getElementsByClassName('product');
let product = Array.from(p);
console.log(product);

var quantityUp = document.getElementById('quantityUp');
var quantityDown = document.getElementById('quantityDown');

if (product != null)
{
    product.forEach(element => {
        element.addEventListener('click', productOnClick);
    });
}

if (quantityUp != null)
{
    quantityUp.addEventListener('click', buttonOnClick);
    quantityDown.addEventListener('click', buttonOnClick);
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
    element.currentTarget.classList.add('buttonClick');
    element.currentTarget.boxShadow = '-3px -3px #000000';
    element.currentTarget.onanimationend = (element) => {
        element.currentTarget.classList.remove('buttonClick');
        element.currentTarget.style.boxShadow = '';
    }
    var quantityText = document.getElementById('quantityText')
    let quantity = Number(quantityText.innerHTML);
    if (element.currentTarget.id == 'quantityDown' && quantity > 0)
    {
        quantityText.innerHTML = quantity - 1;
    }
    else if (element.currentTarget.id == 'quantityUp')
    {
        quantityText.innerHTML = quantity + 1;
    }
}