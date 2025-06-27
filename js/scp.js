
    const productContainer = document.querySelector('.product-container');
    const leftBtn = document.querySelector('.left-btn');
    const rightBtn = document.querySelector('.right-btn');

    leftBtn.addEventListener('click', () => {
        productContainer.scrollBy({ left: -300, behavior: 'smooth' });
    });

    rightBtn.addEventListener('click', () => {
        productContainer.scrollBy({ left: 300, behavior: 'smooth' });
    });


