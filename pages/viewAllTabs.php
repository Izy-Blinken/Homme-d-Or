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
            </div>

            <div class="tab-content active" id="processing">
                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 1</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,500.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-cancel" class="btn-open" onclick="openCancelModal()">Cancel Order</button>
                        </div>
                    </div>
                </div>


                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 2</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱2,500.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-cancel">Cancel Order</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 3</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱1,800.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-cancel">Cancel Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="review">
                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 1</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱2,500.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-rate" class="btn-open" onclick="openReviewModal()">Rate Order</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 2</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,200.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-rate">Rate Order</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content" id="completed">
                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 1</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱2,800.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 2</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,200.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 3</p>
                            <small class="v-desc">50ml • Variant ng perfume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱1,500.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Order Again</button>
                        </div>
                    </div>
                </div>

                <div class="v-orders">
                    <div class="v-left">
                        <img src="../assets/images/products_images/nocturne.png" alt="" width="60">
                        <div class="v-ordersinfo">
                            <p class="v-name">Perfume 4</p>
                            <small class="v-desc">50ml • Variant ng pefume</small>
                        </div>
                    </div>
                    <div class="v-right">
                        <p class="v-price">₱3,500.00</p>
                        <div class="v-actions">
                            <button class="v-view">View</button>
                            <button class="v-again">Order Again</button>
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
                    <div class="romcomdivider"></div>
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
                            <button type="submit" class="romcomBtnSubmit">Confirm</button>
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
        
    

    <?php include '../components/footer.php'; ?>

    <script src="../assets/js/viewAllTabs.js"></script>
    <script src="../assets/js/ReviewCancelOrder.js"></script>

</body>
</html>
