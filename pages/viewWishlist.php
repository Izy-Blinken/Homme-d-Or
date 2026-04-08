<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homme d'Or - Wishlist</title>

    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
    <link rel="stylesheet" href="../assets/css/CheckoutPageStyle.css">
    <link rel="stylesheet" href="../assets/css/OrderAgainStyle.css">
    <link rel="stylesheet" href="../assets/css/BlogPageStyle.css">
    <link rel="stylesheet" href="../assets/css/AboutUsPageStyle.css">
    <link rel="stylesheet" href="../assets/css/ProductDetailsStyle.css">
    <link rel="stylesheet" href="../assets/css/CartPageStyle.css">
    <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
    <link rel="stylesheet" href="../assets/css/ProfilePageStyle.css">
    <link rel="stylesheet" href="../assets/css/viewTabs.css">
    <link rel="stylesheet" href="../assets/css/ReviewCancelOrderStyle.css">
</head>

<body> 
    <?php include '../components/header.php'; ?>

    <main class="mainBG">
        
        <div class="v-tabs">
            <h1 class="v-header">My Wishlist</h1>

            <div class="wishlist-controls">
                <div class="filter-dropdown">
                    <button class="filter-btn">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <div class="filter-menu">
                        <button class="filter-option">Alphabetical</button>
                        <button class="filter-option">By Price</button>
                    </div>
                </div>
            </div>

            <div class="tab-content active">
                
                <div class="empty-wishlist-state">
                    <i class="fa-regular fa-heart"></i>
                    <h2>Your wishlist is empty</h2>
                    <p>Save items you love here to buy them later.</p>
                    <a href="shop.php" class="continue-shopping-btn">Continue Shopping</a>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Nocturne Perfume">
                        <div class="v-ordersinfo">
                            <p class="v-name">Noir Élégance</p>
                            <small class="v-desc">50ml • Eau de Toilette</small>
                        </div>
                    </div>

                    <div class="v-right">
                        <p class="v-price">₱4,850</p>
                        <div class="v-actions">
                                <button class="v-view btn-open"
                                    data-img="../assets/images/products_images/nocturne.png"
                                    data-name="Noir Élégance"
                                    data-variant="50ml • Eau de Toilette"
                                    data-desc="A refined blend of bergamot, leather, and amber, crafted for the modern gentleman."
                                    data-category="Premium"
                                    data-price="4,850"
                                    data-status="In Stock">
                                    View
                                </button>
                            <button class="v-again" onclick="window.location.href='../pages/cart.php'">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Nocturne Perfume">
                        <div class="v-ordersinfo">
                            <p class="v-name">Crimson Verve</p>
                            <small class="v-desc">75ml • Parfum</small>
                        </div>
                    </div>

                    <div class="v-right">
                        <p class="v-price">₱4,700</p>
                        <div class="v-actions">
                                <button class="v-view btn-open"
                                    data-img="../assets/images/products_images/nocturne.png"
                                    data-name="Crimson Verve"
                                    data-variant="75ml • Parfum"
                                    data-desc="A bold fusion of spicy cinnamon, red berries, and warm woods for a confident aura."
                                    data-category="Top Picks"
                                    data-price="4,700"
                                    data-status="In Stock">
                                    View
                                </button>
                            <button class="v-again" onclick="window.location.href='../pages/cart.php'">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Nocturne Perfume">
                        <div class="v-ordersinfo">
                            <p class="v-name">Azure Homme</p>
                            <small class="v-desc">75ml • Parfum</small>
                        </div>
                    </div>

                    <div class="v-right">
                        <p class="v-price">₱3,750</p>
                        <div class="v-actions">
                                <button class="v-view btn-open"
                                    data-img="../assets/images/products_images/nocturne.png"
                                    data-name="Azure Homme"
                                    data-variant="75ml • Parfum"
                                    data-desc="Fresh aquatic notes combined with citrus and musk for an effortlessly clean aroma."
                                    data-category="Daily Wear"
                                    data-price="3,750"
                                    data-status="Low Stock">
                                    View
                                </button>
                            <button class="v-again" onclick="window.location.href='../pages/cart.php'">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Another Product">
                        <div class="v-ordersinfo">
                            <p class="v-name">Sterling Nightfall</p>
                            <small class="v-desc">100ml • Eau de Parfum</small>
                        </div>
                    </div>

                    <div class="v-right">
                        <p class="v-price">₱5,400</p>
                        <div class="v-actions">
                                <button class="v-view btn-open"
                                    data-img="../assets/images/products_images/nocturne.png"
                                    data-name="Sterling Nightfall"
                                    data-variant="100ml • Eau de Parfum"
                                    data-desc="Smooth blend of lavender, tonka bean, and amber for a refined evening scent."
                                    data-category="Premium"
                                    data-price="5,400"
                                    data-status="In Stock">
                                    View
                                </button>
                            <button class="v-again" onclick="window.location.href='../pages/cart.php'">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="Premium Scent">
                        <div class="v-ordersinfo">
                            <p class="v-name">Platinum Essence</p>
                            <small class="v-desc">150ml • Exclusive</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱4,990</p>
                        <div class="v-actions">
                                <button class="v-view btn-open"
                                    data-img="../assets/images/products_images/nocturne.png"
                                    data-name="Platinum Essence"
                                    data-variant="150ml • Exclusive"
                                    data-desc="A powerful mix of tobacco, spice, and deep woody notes for a commanding presence."
                                    data-category="Top Picks"
                                    data-price="4,990"
                                    data-status="In Stock">
                                    View
                                </button>
                            <button class="v-again" onclick="window.location.href='../pages/cart.php'">Add to Cart</button>
                            <button class="v-cancel">Remove</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <div id="viewWishlistModal" class="romcomOverlay">
        <div class="romcomModalContent">
            <div class="romcomHeader">
                <h2>Product Details</h2>
            </div>
            <div class="romcomDivider"></div>

            <div class="romcomBody">
                <div class="view-section">
                    <h4>PRODUCT</h4>
                    <div class="view-product">
                        <img id="WImage" src="" width="70" alt="Product Image">
                        <div>
                            <p id="WName" style="color: #c9a961; font-weight: bold;"></p>
                            <small id="WVariant" style="color: gray; font-weight:bold"></small>
                        </div>
                    </div>
                </div>

                <div class="view-section" style="margin-top: 1rem; color: white;">
                    <p><strong></strong> <span id="WDescription" style="color:#c9a961; font-style:italic"></span></p>
                    <p><strong style="color: gray;">Category:</strong> <span id="WCategory" style="color: #c9a961; font-weight:bold"></span></p>
                    <p><strong style="color: gray;">Price:</strong> <span id="WPrice" style="color: #c9a961; font-weight:bold"></span></p>
                    <p><strong style="color: gray;">Stock status:</strong> <span id="WStatus" style="color: #c9a961; font-weight:bold"></span></p>

                    <div class="WishlistButtonGroup">
                        <button type="button" class="WishlistBtnClose" onclick="closeWishlistModal()">Cancel</button>
                        <button type="submit" class="WishlistBtnSubmit" id="submitReviewBtn" onclick="window.location.href='../pages/checkout.php'">Buy Now</button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <?php include '../components/footer.php'; ?>

    <script src="../assets/js/filterJS.js"></script>
    <script src="../assets/js/viewAllTabs.js"></script>

    <!-- Wishlist Modal -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        
        document.querySelectorAll('.btn-open').forEach(btn => {
            btn.addEventListener('click', function() {
                openViewWishlist(
                    this.dataset.img,
                    this.dataset.name,
                    this.dataset.variant,
                    this.dataset.desc,
                    this.dataset.category,
                    this.dataset.price,
                    this.dataset.status
                );
            });
        });

        document.getElementById("viewWishlistModal").addEventListener("click", function(e) {
            if (e.target === this) closeWishlistModal();
        });

    });

    function openViewWishlist(img, wname, wvariant, wdes, wcategory, wprice, wstatus) {
        document.getElementById("WImage").src = img;
        document.getElementById("WName").textContent = wname;
        document.getElementById("WVariant").textContent = wvariant;
        document.getElementById("WDescription").textContent = wdes;
        document.getElementById("WCategory").textContent = wcategory;
        document.getElementById("WPrice").textContent = "₱" + wprice;
        document.getElementById("WStatus").textContent = wstatus;

        const modal = document.getElementById("viewWishlistModal");
        modal.classList.remove("closing");
        modal.classList.add("show");
    }

    function closeWishlistModal() {
        const modal = document.getElementById("viewWishlistModal");
        modal.classList.add("closing");
        setTimeout(() => {
            modal.classList.remove("show", "closing");
        }, 200);
    }
</script>
</body>
</html>