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
                            <button class="v-cancel">Cancel Order</button>
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
                            <button class="v-rate">Rate Order</button>
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

    <?php include '../components/footer.php'; ?>

    <script src="../assets/js/viewAllTabs.js"></script>

</body>
</html>
