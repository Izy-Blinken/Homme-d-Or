<!DOCTYPE html>
<html lang="en">
    <head> 
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Customer Reviews - Homme d'Or</title>
        
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/css/HeaderHeroFooterStyle.css">
        <link rel="stylesheet" href="../assets/css/CheckoutPageStyle.css">
        <link rel="stylesheet" href="../assets/css/OrderAgainStyle.css">
        <link rel="stylesheet" href="../assets/css/BlogPageStyle.css">
        <link rel="stylesheet" href="../assets/css/AboutUsPageStyle.css">
        <link rel="stylesheet" href="../assets/css/ProductDetailsStyle.css">
        <link rel="stylesheet" href="../assets/css/CartPageStyle.css">
        <link rel="stylesheet" href="../assets/css/RegLoginModalStyle.css">
        <link rel="stylesheet" href="../assets/css/ProfilePageStyle.css">
        <link rel="stylesheet" href="../assets/css/HomepageStyle.css">
        <link rel="stylesheet" href="../assets/css/ViewReviewStyle.css"> 
    </head>
    <body> 
        <?php include '../components/header.php'; ?>

        <main class="mainBG">
            <a href="javascript:history.back()" class="back-button">
                <i class="fa-solid fa-chevron-left"></i> 
            </a>

            <section id="all-reviews-section">
                <div class="all-reviews-container">
                    
                    <div class="reviews-sidebar">
                        <img src="../assets/images/products_images/nocturne.png" alt="Nocturne Perfume" class="sidebar-img">
                        <h2>Nocturne</h2>
                        <div class="sidebar-rating">
                            <span class="score">4.8</span>
                            <div class="stars">
                                <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star-half-stroke"></i>
                            </div>
                        </div>
                        <p class="review-count">Based on 24 reviews</p>
                    </div>

                    <div class="reviews-feed">
                        <div class="feed-header">
                            <h3>What our clients are saying</h3>
                            <select class="review-filter">
                                <option value="newest">Newest First</option>
                                <option value="highest">Highest Rated</option>
                                <option value="lowest">Lowest Rated</option>
                            </select>
                        </div>

                        <div class="full-review-card">
                            <div class="card-header">
                                <div class="user-info">
                                    <div class="avatar">W</div>
                                    <div class="name-date">
                                        <h4>Wally B. <i class="fa-solid fa-circle-check verified-badge" title="Verified Buyer"></i></h4>
                                        <span>October 12, 2025</span>
                                    </div>
                                </div>
                                <div class="card-stars">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>
                                </div>
                            </div>
                            <h5 class="review-title">A true masterpiece.</h5>
                            <p class="review-body">Long lasting smell. Worth the price. I wore this to a gala and received compliments all night. The amber base notes really linger on the skin well into the next morning.</p>
                        </div>

                        <div class="full-review-card">
                            <div class="card-header">
                                <div class="user-info">
                                    <div class="avatar">B</div>
                                    <div class="name-date">
                                        <h4>Bayola W. <i class="fa-solid fa-circle-check verified-badge" title="Verified Buyer"></i></h4>
                                        <span>September 28, 2025</span>
                                    </div>
                                </div>
                                <div class="card-stars">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>
                                </div>
                            </div>
                            <h5 class="review-title">Great everyday scent.</h5>
                            <p class="review-body">Yiz galing. Highly recommended for evening wear, though it might be a bit strong for the office. Packaging feels incredibly premium.</p>
                        </div>
                        
                        </div>
                </div>
            </section>
        </main>

        <?php include '../components/footer.php'; ?>
    </body>
</html>