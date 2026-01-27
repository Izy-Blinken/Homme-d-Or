<!DOCTYPE html>
<html>
    <head>
        <title>Blog</title>
        <link rel="stylesheet" href="../assets/css/style.css">
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
    </head>

    <body>
        <?php include '../components/header.php'; ?>
        <main>

            <div class="profileContainer">
                <div class="colFirst">
                    <div class="headerRow">
                        <h3>Profile</h3>
                    </div>
                    <div class="dividerF"></div>
                    
                    <div class="profileImageSection">
                        <div class="profileImageFrame">
                            <img src="../assets/images/default-profile.png" alt="Profile" class="profileImage">
                        </div>
                        <button class="addProfileBtn">Add Profile Photo</button>
                    </div>

                    <div class="profileInfo">
                        <div class="infoGroup">
                            <label>Name</label>
                            <p>John Doe</p>
                        </div>

                        <div class="infoGroup">
                            <label>Birthday</label>
                            <p>January 16, 1995</p>
                        </div>

                        <div class="infoGroup">
                            <label>Contact Number</label>
                            <p>+63 912 345 6789</p>
                        </div>

                        <div class="infoGroup">
                            <label>Email</label>
                            <p>johndoe@gmail.com</p>
                        </div>

                        <div class="infoGroup">
                            <label>Address</label>
                            <p>123, hahaha, heaha, Province</p>
                        </div>
                    </div>

                    <div class="profileActions">
                        <button class="editProfileBtn">Edit Profile</button>
                    </div>
                    
                    
                </div>

                <div class="rightSide">

                    <div class="colSecond">
                        <div class="headerRow">
                            <h3>Orders</h3>
                            <button class="viewAllBtn" onclick="window.location.href='profileViewOrders.php'">View All</button>
                        </div>
                        <div class="divider"></div>

                        <div class="orderOverview">
                            <div class="orderedItem">
                                <div class="orderIcon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="orderDetails">
                                    <h4>Processing</h4>
                                    <p>3 orders</p>
                                </div>
                            </div>

                            <div class="orderedItem">
                                <div class="orderIcon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="orderDetails">
                                    <h4>To Review</h4>
                                    <p>2 orders</p>
                                </div>
                            </div>

                            <div class="orderedItem">
                                <div class="orderIcon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="orderDetails">
                                    <h4>Completed</h4>
                                    <p>15 orders</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="colThird">
                        <div class="headerRow">
                            <h3>History</h3>
                            <button class="viewAllBtn" onclick="window.location.href='viewHistory.php'">View All</button>
                        </div>
                        <div class="divider"></div>

                        <div class="historyList">
                            <div class="historyItem">
                                <div class="historyDate">Jan 25, 2026</div>
                                <div class="historyInfo">
                                    <p class="historyProduct">Product name</p>
                                    <p class="historyPrice">₱1,250.00</p>
                                </div>
                            </div>

                            <div class="historyItem">
                                <div class="historyDate">Jan 20, 2026</div>
                                <div class="historyInfo">
                                    <p class="historyProduct">Product Name</p>
                                    <p class="historyPrice">₱3,500.00</p>
                                </div>
                            </div>

                            <div class="historyItem">
                                <div class="historyDate">Jan 15, 2026</div>
                                <div class="historyInfo">
                                    <p class="historyProduct">Product name</p>
                                    <p class="historyPrice">₱2,800.00</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="colFourth">
                        <div class="headerRow">
                            <h3>Wishlist</h3>
                            <button class="viewAllBtn" onclick="window.location.href='wishlist.php'">View All</button>
                        </div>

                        <div class="divider"></div>
                        <div class="wishlistGrid">
                            <div class="wishlistItem">
                                <div class="wishlistImage">
                                    <img src="../assets/images/products_images/nocturne.png" alt="Product">
                                </div>
                                <p class="wishlistName">Product Name</p>
                                <p class="wishlistPrice">₱4,200.00</p>
                            </div>

                            <div class="wishlistItem">
                                <div class="wishlistImage">
                                    <img src="../assets/images/products_images/nocturne.png" alt="Product">
                                </div>
                                <p class="wishlistName">Product Name</p>
                                <p class="wishlistPrice">₱1,800.00</p>
                            </div>

                            <div class="wishlistItem">
                                <div class="wishlistImage">
                                    <img src="../assets/images/products_images/nocturne.png" alt="Product">
                                </div>
                                <p class="wishlistName">Product Name</p>
                                <p class="wishlistPrice">₱5,500.00</p>
                            </div>
                        </div>

                        <button class="signOutBtn">Sign Out</button>
                    </div>

                </div>
            </div>

            
        </main>

        <?php include '../components/footer.php'; ?>
    </body>
</html>