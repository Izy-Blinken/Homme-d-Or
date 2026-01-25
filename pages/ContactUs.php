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
                <!--left side-->
                <div class="colFirst">

                    <h3>Profile</h3>
                    <div class="dividerF"></div>
                    
                </div>
                
                <!--right side-->
                <div class="rightSide">

                    <div class="colSecond">
                        <div class="headerRow">
                            <h3>Orders</h3>
                            <button class="viewAllBtn" onclick="window.location.href='viewOrders.php'">View All</button>
                        </div>
                        <div class="divider"></div>
                    </div>

                    <div class="colThird">
                        <div class="headerRow">
                            <h3>History</h3>
                            <button class="viewAllBtn" onclick="window.location.href='viewOrders.php'">View All</button>
                        </div>
                        <div class="divider"></div>
                    </div>

                    <div class="colFourth">
                        <div class="headerRow">
                            <h3>Wishlist</h3>
                            <button class="viewAllBtn" onclick="window.location.href='viewOrders.php'">View All</button>
                        </div>
                        <div class="divider"></div>
                    </div>

                </div>
            </div>

            
        </main>

        <?php include '../components/footer.php'; ?>
    </body>
</html>