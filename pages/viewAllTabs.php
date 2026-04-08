<?php
// ALWAYS start the session first
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// THE BOUNCER: If the user_id is empty, they are a guest.
if (empty($_SESSION['user_id'])) {
    // Kick them back to the homepage and attach a secret message to the URL
    header("Location: index.php?login_required=true");
    exit; // Stop the page from loading
}
?>
<!DOCTYPE html>
<html>

<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homme d'Or - My Orders</title>

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
            <h1 class="v-header">My Orders</h1>

            <div class="order-tabs">
                <button class="tab-btn active" data-tab="processing">Processing</button>
                <button class="tab-btn" data-tab="review">To Review</button>
                <button class="tab-btn" data-tab="completed">Completed</button> 
                <button class="tab-btn" data-tab="cancelled">Cancelled</button>
            </div>

            <div class="tab-content active" id="processing">
                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Mystique</p>
                            <small class="v-desc">50ml • Vetiver Éclipse</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,500.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                    onclick="openViewModal(
                                        '../assets/images/products_images/nocturne.png',
                                        'Homme d’Or Mystique',
                                        '50ml • Vetiver Éclipse',
                                        2,
                                        '₱3,500.00',
                                        'GCash',
                                        'April 20, 2026',
                                        'Pending'
                                    )">View</button>
                            <button class="v-cancel btn-open" onclick="openCancelModal()">Cancel Order</button>
                        </div>
                    </div>
                </div>


                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Zenith</p>
                            <small class="v-desc">50ml • Santal Imperial</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱2,500.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Zenith',
                                    '50ml • Santal Imperial',
                                    2,
                                    '₱2,500.00',
                                    'GCash',
                                    'April 28, 2026',
                                    'Pending'
                                )">View</button>
                            <button class="v-cancel btn-open" onclick="openCancelModal()">Cancel Order</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Sublime</p>
                            <small class="v-desc">50ml • Ambre Royal</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱1,800.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Sublime',
                                    '50ml • Ambre Royal',
                                    1,
                                    '₱1,800.00',
                                    'Cash on delivery',
                                    'June 4, 2026',
                                    'Pending'
                                )">View</button>
                            <button class="v-cancel btn-open" onclick="openCancelModal()">Cancel Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="review">
                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Infini</p>
                            <small class="v-desc">50ml • Cèdre Majesté</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱2,500.00</p>
                        <div class="v-actions">
                            <button class="v-view" onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Infini',
                                    '50ml • Cèdre Majesté',
                                    2,
                                    '₱2,500.00',
                                    'Cash on Delivery',
                                    'January 20, 2026',
                                    'Paid'
                                )">View</button>
                            <button class="v-rate" class="btn-open" onclick="openReviewModal()">Rate Order</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Noir</p>
                            <small class="v-desc">50ml • Oud Élégant</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,200.00</p>
                        <div class="v-actions">
                            <button class="v-view" onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Noir',
                                    '50ml • Oud Élégant',
                                    3,
                                    '₱3,200.00',
                                    'GCash',
                                    'January 14, 2026',
                                    'Paid'
                                )">View</button>
                            <button class="v-rate" class="btn-open" onclick="openReviewModal()">Rate Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="completed">
                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Luminance</p>
                            <small class="v-desc">50ml • Bergamot Doré</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱2,800.00</p>
                        <div class="v-actions">
                           <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Luminance',
                                    '50ml • Bergamot Doré',
                                    2,
                                    '₱2,800.00',
                                    'GCash',
                                    'February 4, 2026',
                                    'Completed'
                                )">View</button>
                            <button class="v-again" onclick="window.location.href='shop.php'">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Prestige</p>
                            <small class="v-desc">50ml • Fève Tonka</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,200.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Prestige',
                                    '50ml • Fève Tonka',
                                    3,
                                    '₱3,200.00',
                                    'Cash on Delivery',
                                    'January 11, 2026',
                                    'Completed'
                                )">View</button>
                            <button class="v-again" onclick="window.location.href='shop.php'">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Voyageur</p>
                            <small class="v-desc">50ml • Patchouli Mystère</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱1,500.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Voyageur',
                                    '50ml • Patchouli Mystère',
                                    1,
                                    '₱1,500.00',
                                    'GCash',
                                    'February 9, 2026',
                                    'Completed'
                                )">View</button>
                            <button class="v-again" onclick="window.location.href='shop.php'">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Royale</p>
                            <small class="v-desc">50ml • Iris Noble</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,500.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Royale',
                                    '50ml • Iris Noble',
                                    2,
                                    '₱3,500.00',
                                    'GCash',
                                    'January 5, 2026',
                                    'Cash on Delivery'
                                )">View</button>
                            <button class="v-again" onclick="window.location.href='shop.php'">Order Again</button>
                        </div>
                    </div>
                </div>
               
            </div>

            <div class="tab-content" id="cancelled">
                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Argent</p>
                            <small class="v-desc">50ml • Musc Sauvage</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱2,800.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Argent',
                                    '50ml • Musc Sauvage',
                                    1,
                                    '₱2,800.00',
                                    'GCash',
                                    'February 2, 2026',
                                    'Cancelled'
                                )">View</button>
                            <button class="v-again" onclick="window.location.href='shop.php'">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Élixir</p>
                            <small class="v-desc">50ml • Cuir Sublime</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,200.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Élixir',
                                    '50ml • Cuir Sublime',
                                    2,
                                    '₱3,200.00',
                                    'GCash',
                                    'February 11, 2026',
                                    'Cancelled'
                                )">View</button>
                            <button class="v-again" onclick="window.location.href='shop.php'">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Voyage</p>
                            <small class="v-desc">50ml • Ambre Nomade</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱1,500.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Voyage',
                                    '50ml • Ambre Nomade',
                                    1,
                                    '₱1,500.00',
                                    'GCash',
                                    'January 9, 2026',
                                    'Cancelled'
                                )">View</button>
                            <button class="v-again" onclick="window.location.href='shop.php'">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Homme d’Or Prestige</p>
                            <small class="v-desc">50ml • Bleu Royal</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,500.00</p>
                        <div class="v-actions">
                            <button class="v-view btn-open" 
                                onclick="openViewModal(
                                    '../assets/images/products_images/nocturne.png',
                                    'Homme d’Or Prestige',
                                    '50ml • Bleu Royal',
                                    2,
                                    '₱3,500.00',
                                    'Cash on Delivery',
                                    'January 7, 2026',
                                    'Cancelled'
                                )">View</button>
                            <button class="v-again" onclick="window.location.href='shop.php'">Order Again</button>
                        </div>
                    </div>
                </div>

               
            </div>
        
        </div>
    </main>

    <div id="cancelOrderModal" class="romcomOverlay">
        <div class="romcomModalContent">
            <div class="romcomHeader">
                <h2>Cancel Order</h2>
            </div>
            <div class="romcomDivider"></div>
            <form class="romcomBody" onsubmit="submitCancellation(event)">
                <p class="modal-description">Please select a reason for cancellation</p>

                <div class="romcomFormGroup">
                    <div class="radio-group">
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Found a better price elsewhere" required>
                            <span class="radio-label">Found a better price elsewhere</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Changed my mind" required>
                            <span class="radio-label">Changed my mind</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Ordered by mistake" required>
                            <span class="radio-label">Ordered by mistake</span>                                    </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Delivery taking too long" required>
                            <span class="radio-label">Delivery taking too long</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Product no longer needed" required>
                            <span class="radio-label">Product no longer needed</span>
                        </label>
                        <label class="radio-option">
                            <input type="radio" name="cancelReason" value="Other" required>
                            <span class="radio-label">Other</span>
                        </label>
                    </div>
                </div>

                <div class="romcomFormGroup" id="otherReasonGroup" style="display: none;">
                    <label for="otherReason">ADDITIONAL DETAILS</label>
                    <textarea id="otherReason" placeholder="Please specify your reason..."></textarea>
                </div>
                <div class="romcomButtonGroup">
                    <button type="button" class="romcomBtnClose" onclick="closeCancelModal()">Keep Order</button>
                    <button type="submit" class="romcomBtnSubmit" >Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <div id="reviewOrderModal" class="romcomOverlay">
        <div class="romcomModalContent">
            <div class="romcomHeader">
                <h2>Submit a Review</h2>
            </div>
            <div class="romcomDivider"></div>

            <form class="romcomBody" onsubmit="submitReview(event)">
                <div class="romcomFormGroup">
                    <label>RATING</label>
                    <div class="starRating">
                        <span class="star" onclick="setRating(1)" onmouseover="hoverRating(1)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>
                        <span class="star" onclick="setRating(2)" onmouseover="hoverRating(2)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>                                    <span class="star" onclick="setRating(3)" onmouseover="hoverRating(3)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>
                        <span class="star" onclick="setRating(4)" onmouseover="hoverRating(4)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>
                        <span class="star" onclick="setRating(5)" onmouseover="hoverRating(5)" onmouseout="resetHover()"><i class="fa-solid fa-star"></i></span>
                    </div>
                    <p class="rating-text" id="ratingText"></p>
                </div>

                <div class="romcomFormGroup">
                    <label for="reviewText">YOUR REVIEW</label>
                    <textarea id="reviewText" placeholder="Share your thoughts about this product..."></textarea>
                </div>

                <div class="romcomButtonGroup">
                    <button type="button" class="romcomBtnClose" onclick="closeReviewModal()">Cancel</button>
                    <button type="submit" class="romcomBtnSubmit" id="submitReviewBtn" disabled>Submit</button>
                </div>
                
            </form>

        </div>
    </div>

    <div id="viewOrderModal" class="romcomOverlay">
        <div class="romcomModalContent">
            <div class="romcomHeader">
            <span class="view-close-btn" onclick="closeViewModal()">&times;</span>
                <h2>Order Details</h2>
            </div>
            <div class="romcomDivider"></div>

            <div class="romcomBody">
                <!-- Product Info -->
                <div class="view-section">
                    <h4>PRODUCT</h4>
                    <div class="view-product">
                        <img id="viewImage" src="" width="70" alt="Product Image">
                        <div>
                            <p id="viewName"></p>
                            <small id="viewVariant"></small>
                        </div>
                    </div>
                </div>

                <!-- Order Info -->
                <div class="view-section">
                    <h4>ORDER INFORMATION</h4>
                    <p><strong>Quantity:</strong> <span id="viewQty"></span></p>
                    <p><strong>Total:</strong> <span id="viewTotal"></span></p>
                    <p><strong>Payment Method:</strong> <span id="viewPayment"></span></p>
                    <p><strong>Order Date:</strong> <span id="viewDate"></span></p>
                    <p><strong>Status:</strong> <span id="viewStatus"></span></p>
                </div>
            </div>
        </div>
    </div>

    <div id="generalToast" class="generalToast"></div>
    <script src="../assets/js/script.js"></script>

    <?php include '../components/footer.php'; ?>

    <script src="../assets/js/viewAllTabs.js"></script>
    <script src="../assets/js/ReviewCancelOrder.js"></script>

</body>
</html>
