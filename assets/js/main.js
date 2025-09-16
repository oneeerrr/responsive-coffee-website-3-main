/*=============== SHOW MENU ===============*/
const navMenu = document.getElementById('nav-menu'),
      navToggle = document.getElementById('nav-toggle'),
      navClose = document.getElementById('nav-close')

/* Menu show */
if(navToggle){
    navToggle.addEventListener('click', () =>{
        navMenu.classList.add('show-menu')
    })
}

/* Menu hidden */
if(navClose){
    navClose.addEventListener('click', () =>{
        navMenu.classList.remove('show-menu')
    })
}

/*=============== REMOVE MENU MOBILE ===============*/
const navLink = document.querySelectorAll('.nav__link')

const linkAction = () =>{
    const navMenu = document.getElementById('nav-menu')
    // When we click on each nav__link, we remove the show-menu class
    navMenu.classList.remove('show-menu')
}
navLink.forEach(n => n.addEventListener('click', linkAction))

/*=============== ADD SHADOW HEADER ===============*/
const shadowHeader = () =>{
    const header = document.getElementById('header')
    this.scrollY>=50 ? header.classList.add('shadow-header')
                     : header.classList.remove('shadow-header')
}
window.addEventListener('scroll', shadowHeader)

/*=============== SWIPER POPULAR ===============*/
const swiperPopular = new Swiper('.popular__swiper', {
    loop: true,
    grabCursor: true,
    spaceBetween: 32,
    slidesPerView: 'auto',
    centeredSlides: 'auto',

    breakpoints: {
        1150:{
            spaceBetween: 80,
        }
    }
})

/*=============== SHOW SCROLL UP ===============*/ 
const scrollUp = () =>{
	const scrollUp = document.getElementById('scroll-up')
    // When the scroll is higher than 350 viewport height, add the show-scroll class to the a tag with the scrollup class
	this.scrollY >= 350 ? scrollUp.classList.add('show-scroll')
						: scrollUp.classList.remove('show-scroll')
}
window.addEventListener('scroll', scrollUp)

/*=============== SCROLL SECTIONS ACTIVE LINK ===============*/
const sections = document.querySelectorAll('section[id]')
    
const scrollActive = () =>{
  	const scrollDown = window.scrollY

	sections.forEach(current =>{
		const sectionHeight = current.offsetHeight,
			  sectionTop = current.offsetTop - 58,
			  sectionId = current.getAttribute('id'),
			  sectionsClass = document.querySelector('.nav__menu a[href*=' + sectionId + ']')

		if(scrollDown > sectionTop && scrollDown <= sectionTop + sectionHeight){
			sectionsClass.classList.add('active-link')
		}else{
			sectionsClass.classList.remove('active-link')
		}                                                    
	})
}
window.addEventListener('scroll', scrollActive)

/*=============== SCROLL REVEAL ANIMATION ===============*/
const sr = ScrollReveal({
    origin: 'top',
    distance: '60px',
    duration: 2000,
    delay: 300,
    reset: true
})

sr.reveal(`.popular__swiper, .footer__container, .footer__copy`)
sr.reveal(`.home__shape`, {origin: 'bottom'})
sr.reveal(`.home__coffee`, {delay: 1000, distance: '200px', duration: 1500})
sr.reveal(`.home__splash`, {delay: 1000, scale: '200px', duration: 1500})
sr.reveal(`.home__bean-1, .home__bean-2`, {delay: 2200, scale: '0px', duration: 1500, rotate: {z:180}})
sr.reveal(`.home__ice-1, .home__ice-2`, {delay: 2600, scale: '0px', duration: 1500, rotate: {z:180}})
sr.reveal(`.home__leaf`, {delay: 2800, scale: '0px', duration: 1500, rotate: {z:90}})
sr.reveal(`.home__title`, {delay: 3500})
sr.reveal(`.home__data, .home__sticker`, {delay: 4000})
sr.reveal(`.about__data`, {origin:'left'})
sr.reveal(`.about__images`, {origin:'right'})
sr.reveal(`.about__coffee`, {delay:1000})
sr.reveal(`.about__leaf-1, .about__leaf-2`, {delay:1400, rotate: {z:90}})
sr.reveal(`.products__card, .contact__info`, {interval: 100})
sr.reveal(`.contact__shape`, {delay:600, scale: 0})
sr.reveal(`.contact__delivery`, {delay:1200})

/*=============== CART FUNCTIONALITY ===============*/

// Elements
const cartToggle = document.querySelector('.cart-toggle');
const cartSidebar = document.getElementById('cart-sidebar');
const cartOverlay = document.getElementById('cart-overlay');
const cartClose = document.getElementById('cart-close');
const cartCount = document.getElementById('cart-count');
const cartItems = document.getElementById('cart-items');

// Open cart sidebar
function openCart() {
    cartSidebar.classList.add('active');
    cartOverlay.classList.add('active');
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

// Close cart sidebar
function closeCart() {
    cartSidebar.classList.remove('active');
    cartOverlay.classList.remove('active');
    document.body.style.overflow = 'auto'; // Restore scrolling
}

// Event listeners for cart toggle
cartToggle?.addEventListener('click', function(e) {
    e.preventDefault();
    openCart();
});

// Event listeners for cart close
cartClose?.addEventListener('click', closeCart);
cartOverlay?.addEventListener('click', closeCart);

// Close cart when pressing Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && cartSidebar.classList.contains('active')) {
        closeCart();
    }
});

/*=============== ADD TO CART FUNCTIONALITY ===============*/

// Function to add product to cart
async function addToCart(id, name, price, qty = 1) {
    console.log('Adding to cart:', {id, name, price, qty}); // Debug log
    
    try {
        const formData = new FormData();
        formData.append('action', 'add_to_cart');
        formData.append('id', id);
        formData.append('nama', name);
        formData.append('harga', price);
        formData.append('qty', qty);

        const response = await fetch('keranjang-handler.php', {
            method: 'POST',
            body: formData
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();
        console.log('Server response:', result); // Debug log

        if (result.status === 'success') {
            // Update cart counter
            if (cartCount) {
                cartCount.textContent = result.total_items;
            }
            
            // Show notification
            showNotification(`${name} berhasil ditambahkan ke keranjang!`);
            
            // Refresh cart items
            refreshCartItems();
            
            return true;
        } else {
            showNotification(result.message || 'Gagal menambahkan produk ke keranjang', 'error');
            return false;
        }
    } catch (error) {
        console.error('Error adding to cart:', error);
        showNotification('Terjadi kesalahan saat menambahkan ke keranjang', 'error');
        return false;
    }
}

// Function to remove item from cart
async function removeFromCart(id) {
    try {
        const formData = new FormData();
        formData.append('action', 'remove_from_cart');
        formData.append('id', id);

        const response = await fetch('keranjang-handler.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            // Update cart counter
            if (cartCount) {
                cartCount.textContent = result.total_items;
            }
            
            showNotification('Produk berhasil dihapus dari keranjang!');
            refreshCartItems();
        } else {
            showNotification(result.message || 'Gagal menghapus produk', 'error');
        }
    } catch (error) {
        console.error('Error removing from cart:', error);
        showNotification('Gagal menghapus produk dari keranjang', 'error');
    }
}

// Function to clear entire cart
async function clearCart() {
    if (!confirm('Apakah Anda yakin ingin mengosongkan keranjang?')) {
        return;
    }
    
    try {
        const formData = new FormData();
        formData.append('action', 'clear_cart');

        const response = await fetch('keranjang-handler.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            if (cartCount) {
                cartCount.textContent = '0';
            }
            
            showNotification('Keranjang berhasil dikosongkan!');
            refreshCartItems();
        } else {
            showNotification(result.message || 'Gagal mengosongkan keranjang', 'error');
        }
    } catch (error) {
        console.error('Error clearing cart:', error);
        showNotification('Gagal mengosongkan keranjang', 'error');
    }
}

// Function to refresh cart items display
async function refreshCartItems() {
    try {
        const response = await fetch('item.php');
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const html = await response.text();
        if (cartItems) {
            cartItems.innerHTML = html;
            attachCartEventListeners(); 
        }
    } catch (error) {
        console.error('Error refreshing cart items:', error);
        if (cartItems) {
            cartItems.innerHTML = '<p class="cart-error">Gagal memuat keranjang</p>';
        }
    }
}

// Function to attach event listeners to cart buttons
function attachCartEventListeners() {
    // Event listeners for remove item buttons
    const removeButtons = document.querySelectorAll('.cart-remove-item');
    removeButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const id = this.getAttribute('data-id');
            removeFromCart(id);
        });
    });

    // Event listener for clear cart button
    const clearButton = document.getElementById('cart-clear');
    if (clearButton) {
        clearButton.addEventListener('click', function(e) {
            e.preventDefault();
            clearCart();
        });
    }
}

// Function to load initial cart count
async function loadCartCount() {
    try {
        const response = await fetch('get-cart.php');
        const result = await response.json();
        if (cartCount && result.total_items) {
            cartCount.textContent = result.total_items;
        }
    } catch (error) {
        console.log('Cart count not available:', error);
    }
}

// Main DOMContentLoaded event
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, setting up cart functionality'); // Debug log
    
    // Attach event listeners to all "Add to Cart" buttons in products section
    const productButtons = document.querySelectorAll('.products__button, .add-to-cart');
    console.log('Found product buttons:', productButtons.length); // Debug log
    
    productButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            console.log('Product button clicked'); // Debug log
            
            // Get product data from button attributes or parent element
            const productCard = this.closest('.products__card');
            
            const id = this.getAttribute('data-id') || productCard?.getAttribute('data-id');
            const name = this.getAttribute('data-name') || productCard?.querySelector('.products__name')?.textContent;
            const priceText = this.getAttribute('data-price') || productCard?.querySelector('.products__price')?.textContent;
            
            // Clean price (remove "Rp" and formatting)
            const price = parseInt(priceText.toString().replace(/[^\d]/g, ''));
            const qty = 1; // Default quantity
            
            console.log('Product data:', {id, name, price, qty}); // Debug log
            
            if (id && name && price && !isNaN(price)) {
                const success = await addToCart(id, name, price, qty);
                if (success) {
                    // Visual feedback
                    const originalContent = this.innerHTML;
                    this.innerHTML = '<i class="ri-check-line"></i>';
                    setTimeout(() => {
                        this.innerHTML = originalContent;
                    }, 1500);
                }
            } else {
                console.error('Data produk tidak lengkap:', {id, name, price});
                showNotification('Data produk tidak lengkap', 'error');
            }
        });
    });
    
    // Attach event listeners to popular section buttons
    const popularButtons = document.querySelectorAll('.add-to-cart-popular');
    console.log('Found popular buttons:', popularButtons.length); // Debug log
    
    popularButtons.forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            console.log('Popular button clicked'); // Debug log
            
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const priceText = this.getAttribute('data-price');
            const price = parseInt(priceText);
            const qty = 1;
            
            console.log('Popular product data:', {id, name, price, qty}); // Debug log
            
            if (id && name && price && !isNaN(price)) {
                const success = await addToCart(id, name, price, qty);
                if (success) {
                    setTimeout(() => {
                        this.textContent = originalText;
                    }, 1500);
                }
            } else {
                console.error('Data produk popular tidak lengkap:', {id, name, price});
                showNotification('Data produk tidak lengkap', 'error');
            }
        });
    });
    
    // Load initial cart count
    loadCartCount();
    
    // Attach cart event listeners
    attachCartEventListeners();
});

// Function to show notification
function showNotification(message, type = 'success') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.textContent = message;
    
    // Add CSS styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'error' ? '#e74c3c' : '#27ae60'};
        color: white;
        padding: 15px 20px;
        border-radius: 5px;
        box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        z-index: 9999;
        font-size: 14px;
        max-width: 300px;
        word-wrap: break-word;
        animation: slideIn 0.3s ease-in;
    `;
    
    // Add to document
    document.body.appendChild(notification);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (notification && notification.parentNode) {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 3000);
}

// Add CSS animations
if (!document.querySelector('#notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        @keyframes slideIn {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        @keyframes slideOut {
            from { transform: translateX(0); opacity: 1; }
            to { transform: translateX(100%); opacity: 0; }
        }
    `;
    document.head.appendChild(style);
}

