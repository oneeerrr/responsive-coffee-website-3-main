<?php
session_start();
include "koneksi.php";
?>

<!DOCTYPE html>
   <html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <!--=============== FAVICON ===============-->
      <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

      <!--=============== REMIXICONS ===============-->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">

      <!--=============== SWIPER CSS ===============-->
      <link rel="stylesheet" href="assets/css/swiper-bundle.min.css">

      <!--=============== CSS ===============-->
      <link rel="stylesheet" href="assets/css/styles.css">
      <style>
         .cart-user-form {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #eee;
}

.cart-user-form h4 {
    margin-bottom: 15px;
    color: #333;
    font-size: 16px;
    font-weight: 600;
}

.cart-input {
    width: 100%;
    padding: 12px;
    margin-bottom: 10px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
    font-family: inherit;
    transition: border-color 0.3s ease;
}

.cart-input:focus {
    outline: none;
    border-color: #8B4513;
    box-shadow: 0 0 0 2px rgba(139, 69, 19, 0.1);
}

.cart-input::placeholder {
    color: #999;
}

textarea.cart-input {
    resize: vertical;
    min-height: 80px;
}

.cart-actions {
    display: flex;
    gap: 10px;
    margin-top: 20px;
}

.cart-clear,
.cart-checkout {
    flex: 1;
    padding: 12px 16px;
    border: none;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    text-align: center;
}

.cart-clear {
    background-color: #ff4757;
    color: white;
}

.cart-clear:hover {
    background-color: #ff3838;
    transform: translateY(-2px);
}

.cart-checkout {
    background-color: #8B4513;
    color: white;
    border: none;
}

.cart-checkout:hover {
    background-color: #7a3d0f;
    transform: translateY(-2px);
}

.cart-checkout:disabled {
    background-color: #ccc;
    cursor: not-allowed;
    transform: none;
}

/* Alert Messages */
.alert {
    padding: 12px;
    margin-bottom: 15px;
    border-radius: 8px;
    font-size: 14px;
    font-weight: 500;
}

.alert-error {
    background-color: #fee;
    color: #c33;
    border: 1px solid #fcc;
}

.alert-success {
    background-color: #efe;
    color: #363;
    border: 1px solid #cfc;
}

/* Cart item styling improvements */
.cart-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    margin-bottom: 8px;
    background-color: #f9f9f9;
    border-radius: 8px;
    transition: background-color 0.3s ease;
}

.cart-item:hover {
    background-color: #f5f5f5;
}

.cart-item-info {
    flex: 1;
}

.cart-item-name {
    display: block;
    font-weight: 600;
    color: #333;
    margin-bottom: 4px;
}

.cart-item-price {
    color: #8B4513;
    font-weight: 600;
}

.cart-remove-item {
    background-color: #ff4757;
    color: white;
    border: none;
    padding: 8px;
    border-radius: 6px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.cart-remove-item:hover {
    background-color: #ff3838;
}

.cart-total {
    font-size: 18px;
    font-weight: bold;
    color: #8B4513;
    text-align: center;
    padding: 15px;
    background-color: #f5f5f5;
    border-radius: 8px;
    margin: 15px 0;
}

.cart-empty {
    text-align: center;
    color: #999;
    font-style: italic;
    padding: 40px 20px;
}

/* Responsive */
@media screen and (max-width: 480px) {
    .cart-actions {
        flex-direction: column;
    }
    
    .cart-clear,
    .cart-checkout {
        width: 100%;
        margin-bottom: 5px;
    }
}
      </style>

      <title>Responsive coffee website - Bedimcode</title>
   </head>
   <body>
      <!--==================== HEADER ====================-->
      <header class="header" id="header">
         <nav class="nav container">
            <a href="#" class="nav__logo">StarBoy</a>

            <div class="nav__menu" id="nav-menu">
               <ul class="nav__list">
                  <li>
                     <a href="#home" class="nav__link active-link">HOME</a>
                  </li>
                  <li>
                     <a href="#popular" class="nav__link">POPULAR</a>
                  </li>
                  <li>
                     <a href="#about" class="nav__link">ABOUT US</a>
                  </li>
                  <li>
                     <a href="#products" class="nav__link">PRODUCTS</a>
                  </li>
                  <li>
                     <a href="#contact" class="nav__link">CONTACT</a>
                  </li>
                  <li>
                     <a href="#" class="nav__link cart-toggle">
                     <i class="ri-shopping-cart-2-line"></i>
                     <span class="cart-count" id="cart-count">
            <?php 
              echo isset($_SESSION['keranjang']) 
                ? array_sum(array_column($_SESSION['keranjang'],'qty')) 
                : 0; 
            ?>
          </span>
        </a>
      </li>
               </ul>

               <!--close button-->
               <div class="nav__close" id="nav-close">
                  <i class="ri-close-large-line"></i>
               </div>    
            </div>

               <!--toggle button-->
               <div class="nav__toggle" id="nav-toggle">
                  <i class="ri-apps-2-fill"></i>
               </div>

         </nav>
      </header>
         <!--keranjang-->
         <div class="cart-sidebar" id="cart-sidebar">
         <div class="cart-header">
            <h3>Keranjang Saya</h3>
            <button class="cart-close" id="cart-close">
               <i class="ri-close-large-line"></i>
            </button>
         </div>
         
         <div class="cart-content">
            <div class="cart-items" id="cart-items">
               <?php
               $grandTotal = 0;

               if (!empty($_SESSION['keranjang'])) {
                  foreach ($_SESSION['keranjang'] as $id => $item) {
                     $total = $item['harga'] * $item['qty'];
                     $grandTotal += $total;
                     echo "<div class='cart-item' data-id='{$id}'>
                              <div class='cart-item-info'>
                                 <span class='cart-item-name'>{$item['nama']} ({$item['qty']})</span>
                                 <span class='cart-item-price'>Rp " . number_format($total,0,',','.') . "</span>
                              </div>
                              <button class='cart-remove-item' data-id='{$id}'>
                                 <i class='ri-delete-bin-line'></i>
                              </button>
                           </div>";
                  }
                  echo "<div class='cart-total'>Total: Rp " . number_format($grandTotal,0,',','.') . "</div>";
               } else {
                  echo "<p class='cart-empty'>Keranjang kosong.</p>";
               }
               ?>
            </div>

            <!-- Form checkout selalu kelihatan -->
            <div class="cart-checkout-form">
               <form id="checkout-form" action="checkout.php" method="POST" class="cart-user-form">
               <h4>Data Pelanggan:</h4>
               <input type="text" name="nama" placeholder="Nama Lengkap*" class="cart-input" required>
               <input type="email" name="email" placeholder="Email*" class="cart-input" required>
               <input type="text" name="telepon" placeholder="Nomor Telepon*" class="cart-input" required>
               <textarea name="alamat" placeholder="Alamat Lengkap*" class="cart-input" required></textarea>
               
               <div class="cart-actions">
                  <button type="button" class="cart-clear" id="cart-clear">Kosongkan Keranjang</button>
                  
                  <!-- Tombol submit selalu ada, tapi disable kalau keranjang kosong -->
                  <button type="submit" class="cart-checkout"
                     <?php echo empty($_SESSION['keranjang']) ? 'disabled' : ''; ?>>
                     Buat Pesanan
                  </button>
               </div>
               </form>
            </div>
         </div>
      </div>
      <!-- Cart Overlay -->
      <div class="cart-overlay" id="cart-overlay"></div>

      <!--==================== MAIN ====================-->
      <main class="main">
         <!--==================== HOME ====================-->
         <section class="home section" id="home">
            <div class="home__container container grid">
               <h1 class="home__title">
                  COLD COFFEE
               </h1>

               <div class="home__image">
                  <div class="home__shape"></div>
                     <img src="assets/img/home-splash.png" alt="image" class="home__splash">
                     <img src="assets/img/bean-img.png" alt="image" class="home__bean-2">
                     <img src="assets/img/home-coffee.png" alt="image" class="home__coffee">
                     <img src="assets/img/bean-img.png" alt="image" class="home__bean-1">
                     <img src="assets/img/ice-img.png" alt="image" class="home__ice-1">
                     <img src="assets/img/ice-img.png" alt="image" class="home__ice-2">
                     <img src="assets/img/leaf-img.png" alt="image" class="home__leaf">
               </div>

               <img src="assets/img/home-sticker.svg" alt="image" class="home__sticker">

               <div class="home__data">
                  <p class="home__description">
                     Find delicious coffee drinks, made with the finest ingredients and served with a smile. Our cold coffee is perfect for any time of day, whether you're looking for a refreshing pick-me-up or a sweet treat to enjoy.
                  </p>

                  <a href="#about" class="button">Learn More</a>
               </div>
            </div>
         </section>

         <!--==================== POPULAR ====================-->
         <section class="popular section" id="popular">
            <div class="popular__container">
               <h2 class="section__title">POPULAR <br> CREATIONS</h2>

               <div class="popular__swiper swiper">
                  <div class="swiper-wrapper">
                     <article class="popular__card swiper-slide">
                        <div class="popular__images">
                           <div class="popular__shape"></div>
                           <img src="assets/img/bean-img.png" alt="image" class="popular__bean-1">
                           <img src="assets/img/bean-img.png" alt="image" class="popular__bean-2">
                           <img src="assets/img/popular-coffee-1.png" alt="image" class="popular__coffee">
                        </div>

                        <div class="popular__data">
                           <h2 class="popular__name">MOCHA COFFEE</h2>

                           <p class="popular__description">
                              Indulge in the simplicity of our delicious cold brew coffee.
                           </p>

                           <button class="button button-dark add-to-cart-popular" 
                                   data-id="mocha" 
                                   data-name="MOCHA COFFEE" 
                                   data-price="20000">
                             Order Now Rp20k
                           </button>
                        </div>
                     </article>
                     <article class="popular__card swiper-slide">
                        <div class="popular__images">
                           <div class="popular__shape"></div>
                           <img src="assets/img/bean-img.png" alt="" class="popular__bean-1">
                           <img src="assets/img/bean-img.png" alt="" class="popular__bean-2">
                           <img src="assets/img/popular-coffee-2.png" alt="" class="popular__coffee">
                        </div>

                        <div class="popular__data">
                           <h2 class="popular__name">VANILLA LATTE</h2>

                           <p class="popular__description">
                              Indulge in the simplicity of our delicious cold brew coffee.
                           </p>

                           <button class="button button-dark add-to-cart-popular" 
                                   data-id="vanilla" 
                                   data-name="VANILLA LATTE" 
                                   data-price="20000">
                             Order Now Rp20k
                           </button>
                        </div>
                     </article>
                     <article class="popular__card swiper-slide">
                        <div class="popular__images">
                           <div class="popular__shape"></div>
                           <img src="assets/img/bean-img.png" alt="" class="popular__bean-1">
                           <img src="assets/img/bean-img.png" alt="" class="popular__bean-2">
                           <img src="assets/img/popular-coffee-3.png" alt="" class="popular__coffee">
                        </div>

                        <div class="popular__data">
                           <h2 class="popular__name">CLASSIC COFFEE</h2>

                           <p class="popular__description">
                              Indulge in the simplicity of our delicious cold brew coffee.
                           </p>

                           <button class="button button-dark add-to-cart-popular" 
                                   data-id="classic" 
                                   data-name="CLASSIC COFFEE" 
                                   data-price="20000">
                             Order Now Rp20k
                           </button>
                        </div>
                     </article>
                  </div>
               </div>
            </div>
         </section>

         <!--==================== ABOUT ====================-->
         <section class="about section" id="about">
            <div class="about__container container grid">
               <div class="about__data">
                  <h2 class="section__title">LEARN MORE <br> ABOUT US</h2>
                  <p class="about__description">
                     Welcome to StarCoffee, where coffee is pure passion. From bean to cup, we are dedicated to delivering excellence in every sip. Join us on a journey of flavor and quality, crafted with love to create the ultimate coffee experience.
                  </p>

                  <a href="#popular" class="button">The Best Coffees</a>
               </div>

               <div class="about__images">
                  <div class="about__shape"></div>
                  <img src="assets/img/leaf-img.png" alt="" class="about__leaf-1">
                  <img src="assets/img/leaf-img.png" alt="" class="about__leaf-2">
                  <img src="assets/img/about-coffee.png" alt="" class="about__coffee">
               </div>
            </div>
         </section>

         <!--==================== PRODUCTS ====================-->
       <?php include "koneksi.php"; ?>
<section class="products section" id="products">
   <h2 class="section__title">THE MOST <br> REQUESTED</h2>

   <div class="products__container container grid">
      <?php
      $sql = "SELECT * FROM produk";
      $result = mysqli_query($conn, $sql);

      if (!$result) {
         die("Query error: " . mysqli_error($conn));
      }

      if (mysqli_num_rows($result) > 0) {
         while ($product = mysqli_fetch_assoc($result)) {
            ?>
            <article class="products__card">
               <div class="products__image">
                  <div class="products__shape"></div>
                  <img src="assets/img/ice-img.png" alt="image" class="products__ice-1">
                  <img src="assets/img/ice-img.png" alt="image" class="products__ice-2">
                  <img src="assets/img/<?php echo $product['gambar']; ?>.png" 
                       alt="<?php echo $product['nama_produk']; ?>" 
                       class="products__coffee">
               </div>

               <div class="products__data">
                  <h3 class="products__name"><?php echo $product['nama_produk']; ?></h3>
                  <span class="products__price">Rp <?php echo number_format($product['harga'],0,',','.'); ?></span>
                  <button class="products__button add-to-cart" 
                          data-id="<?php echo $product['id_produk']; ?>" 
                          data-name="<?php echo $product['nama_produk']; ?>" 
                          data-price="<?php echo $product['harga']; ?>">
                     <i class="ri-shopping-bag-3-fill"></i>
                  </button>
               </div>
            </article>
            <?php
         }
      } else {
         echo "<p>Tidak ada produk tersedia.</p>";
      }
      ?>
   </div>
</section>

         <!--==================== CONTACT ====================-->
         <section class="contact section" id="contact">
            <h2 class="section__title">CONTACT US</h2>

            <div class="contact__container container grid">
               <div class="contact__info grid">
                  <div>
                     <h3 class="contact__title">Write Us</h3>
                  </div>

                  <div class="contact__social">
                     <a href="https://api.whatsapp.com/send?phone=6288991565091&text=Hello, more information!" target="_blank" class="contact__social-link">
                        <i class="ri-whatsapp-fill"></i>
                     </a>

                     <a href="https://m.me/" target="_blank" class="contact__social-link">
                        <i class="ri-messenger-fill"></i>
                     </a>

                     <a href="https://t.me/telegram" target="_blank" class="contact__social-link">
                        <i class="ri-telegram-2-fill"></i>
                     </a>
                  </div>
                  <div>
                     <h3 class="contact__title">Location</h3>
                     <address class="contact__address">
                        JL. In Aja Dulu - Indonesia <br>
                        Kediri #4321
                     </address>
                     <a href="https://maps.app.goo.gl/iffhpco5TvnF39aKA" class="contact__map">
                        <i class="ri-map-pin-fill"></i>
                        <span>View on Map</span>
                     </a>
                  </div>
               </div>

               <div class="contact__info grid">
                  <div>
                     <h3 class="contact__title">Customer Services</h3>

                     <address class="contact__address">
                        +62-8560-8744-845 <br>
                        +62-123456
                     </address>
                  </div>

                  <div>
                     <h3 class="contact__title">Attention</h3>

                     <address class="contact__address">
                        Monday - Saturday <br> 
                        9AM - 10PM
                     </address>
                  </div>
               </div>

               <div class="contact__image">
                  <div class="contact__shape"></div>
                  <img src="assets/img/contact-delivery.png" alt="image" class="contact__delivery">
               </div>
            </div>
         </section>
      </main>

      <!--==================== FOOTER ====================-->
      <footer class="footer">
         <div class="footer__container container grid">
            <div class="footer__info">
         <div>
            <h3 class="footer__title"></h3>
            <div class="footer__social">
               <a href="https://facebook.com/starcoffee" target="_blank" class="footer__social-link">
                  <i class="ri-facebook-circle-fill"></i>
               </a>
               <a href="https://instagram.com/starcoffee" target="_blank" class="footer__social-link">
                  <i class="ri-instagram-fill"></i>
               </a>
               <a href="https://twitter.com/starcoffee" target="_blank" class="footer__social-link">
                  <i class="ri-twitter-x-line"></i>
               </a>
            </div>
         </div>
      </div>

            <div>
               <h3 class="footer__title">Payment Methods</h3>

               <div class="footer__pay">
                  <img src="assets/img/footer-card-1.png" alt="image" class="footer__pay-card">

                  <img src="assets/img/footer-card-2.png" alt="image" class="footer__pay-card">

                  <img src="assets/img/footer-card-3.png" alt="image" class="footer__pay-card">

                  <img src="assets/img/footer-card-4.png" alt="image" class="footer__pay-card">
               </div>
            </div>

            <div>
               <h3 class="footer__title">Subscribe For Discount</h3>

               <form action="" class="footer__form">
                  <input type="email" placeholder="Email" class="footer__input">
                  <button type="submit" class="footer__button">Subscribe</button>
               </form>
            </div>
         </div>

         <span class="footer__copy">
            &#169; All Rights Reserved By Onerrr
         </span>
      </footer>

      <!--========== SCROLL UP ==========-->
      <a href="#" class="scrollup" id="scroll-up">
         <i class="ri-arrow-up-line"></i>
      </a>

      <!--=============== SCROLLREVEAL ===============-->
      <script src="assets/js/scrollreveal.min.js"></script>

      <!--=============== SWIPER JS ===============-->
      <script src="assets/js/swiper-bundle.min.js"></script>

      <!--=============== MAIN JS ===============-->
      <script src="assets/js/main.js"></script>
   </body>
</html>