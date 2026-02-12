<!DOCTYPE html>
<html lang="en">
<head> 
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homme d'Or - History</title>

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
        <div class="h-tabs">
            <h1 class="v-header">Purchase History</h1>

            <div class="history-controls">
                <input type="text" placeholder="Search by product name">
                <button class="search-btn">Search</button>
                <div class="filter-dropdown">
                    <button class="filter-btn">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <div class="filter-menu">
                        <button class="filter-option">Purchase Date</button>
                        <button class="filter-option">Alphabetical</button>
                    </div>
                </div>
            </div>

            <div class="history-table-container">
                <div class="history-table">
                    <div class="history-row history-head">
                        <span>No</span>
                        <span>Purchase Date</span>
                        <span>Product Name</span>
                        <span>Unit Price</span>
                        <span>Quantity</span>
                        <span>Subtotal</span>
                        <span>Actions</span>
                    </div>

                    <div class="history-row">
                        <span>1</span>
                        <span>04-10-2026<br><small>14:31</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Perfume 1
                        </span>
                        <span>₱1,250.00</span>
                        <span>1</span>
                        <span>₱1,250.00</span>
                        <span><a href="productDetails.php" class="view-link">View</a></span>
                    </div>

                    <div class="history-row">
                        <span>2</span>
                        <span>04-10-2026<br><small>14:15</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Perfume 2
                        </span>
                        <span>₱3,500.00</span>
                        <span>2</span>
                        <span>₱7,000.00</span>
                        <span><a href="#" class="view-link">View</a></span>
                    </div>

                    <div class="history-row">
                        <span>3</span>
                        <span>04-10-2026<br><small>14:15</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Perfume 3
                        </span>
                        <span>₱2,800.00</span>
                        <span>1</span>
                        <span>₱2,800.00</span>
                        <span><a href="#" class="view-link">View</a></span>
                    </div>

                    <div class="history-row">
                        <span>4</span>
                        <span>04-09-2026<br><small>10:20</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Perfume 4
                        </span>
                        <span>₱1,500.00</span>
                        <span>3</span>
                        <span>₱4,500.00</span>
                        <span><a href="#" class="view-link">View</a></span>
                    </div>

                    <div class="history-row">
                        <span>5</span>
                        <span>04-08-2026<br><small>16:45</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Perfume 5
                        </span>
                        <span>₱2,200.00</span>
                        <span>1</span>
                        <span>₱2,200.00</span>
                        <span><a href="#" class="view-link">View</a></span>
                    </div>

                    <div class="history-row">
                        <span>6</span>
                        <span>04-07-2026<br><small>11:30</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Perfume 6
                        </span>
                        <span>₱3,000.00</span>
                        <span>2</span>
                        <span>₱6,000.00</span>
                        <span><a href="#" class="view-link">View</a></span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script src="../assets/js/filterJS.js"></script>
    
</body>
</html>