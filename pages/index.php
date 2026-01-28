<!DOCTYPE html>
<html lang = "en">
    <head> <!--from here-->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homme d'Or - Home</title>
        
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

    </head>
    <body> <!--Steps from starting to saving: 
            terminal -> git pull -> then work na -> (if save na:) git add . -> git commit -m "describe what you changed here pero mas better kung i-comment din like this" -> git pull -> then git push
            -->

        <?php include '../components/header.php'; ?>
        <?php 
        $title = 'Trade-in-offer';
        $subtitle = 'Super value deals';
        $heading = 'On all products';
        $description = 'Save more with coupons & up to 70% off!';
        $buttonText = 'Shop Now';
        $buttonLink = 'productdetails.php';
        include '../components/hero.php'; 
        ?> <!--Need to i-call everytime na gagamitin yung hero section. Pwede iba-iba laman ng content. Dito rin iibahin kapag cinall to. -
            However, kung gusto ibahin yung format, gawa nalang bagong hero pero make it reusable nalang din.-->

        <script src="script.js"></script>

        <main>

        

        </main>

        <?php include '../components/footer.php'; ?>

    </body>
        
</html>

<!--

    Parts na wala pa:
    1. wishlist page
    2. order history (profile page)
    3. admin pages:
        - Dashboard: Overview of total revenue, products, orders, customers
                   : Recent activities/orders
        - Product Management: Product lists with filter and categories
                            : Buttons for Adding/Updating/Deleting of products
                            : Status for each product(Low stock, Out of Stock, In Stock)
        - Order Management: Order lists with filter (Customers' info included)
        - Customers: Customers reviews with filter (Customers' info included)
        - Sales Report: Sales report (Daily, Weekly, Monthly)
                      : Top products
        - Admin Profile: Brand info
                       : Same overview ng nasa dashboard
-->