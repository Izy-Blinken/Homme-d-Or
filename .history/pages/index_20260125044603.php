<!DOCTYPE html>
<html lang = "en">
    <head> <!--from here-->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homme d'Or - Home</title>
        
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">

    </head>
    <body> <!--to here ay standard ng system ang code. 
            Then, the following ay nasa tutorial na sesend ko.
            And nagdagdag ako mga files for navigation bar options(yung nasa pages folder). 
            You can still edit here since this is the homepage file. Lahat ng need sa homepage, dito eedit, and kung ibang page naman, you can add it sa Pages folder.
            Basta be careful sa mga ciniclick and watch tutorial din how to do everything.
            You can also use Claude(.)ai for help or other AIs as long as naiintindihan niyu mga nangyayari.
            Just dont forget the step: terminal -> git pull -> then work na -> (if save na:) git add . -> git commit -m "describe what you changed here pero mas better kung i-comment din like this" -> git pull -> then git push
            Yun lng thankiess
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
1. Done reusable footer and header. Design not final.
2. Changed pages files from .html to .php for reusability. Now, di na marrun yung html directly sa vs code.
3. Kapag magrrun, direct sa browser: localhost/Homme_dor/pages/index.php or kung paano yung structure ng folders sa inyo.

12/29/25
1. Frontend: Checkout and order again page done.
2. Dropdown menu for shop and country/currency options done. 

For all changes: Design not final. Placeholders pa lahat.

12/31/25
1. Blog Page done.

For all changes: Design not final. Placeholders pa lahat.

01/20/2026
1. About Us page in done.

For all changes: Design not final. Placeholders pa lahat.

01/25/26

-->