var p = document.getElementsByClassName('product');
let product = Array.from(p);
console.log(product);

product.forEach(element => {
    element.addEventListener('click', productOnClick);
});

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