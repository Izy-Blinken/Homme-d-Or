<!DOCTYPE html>
<html>
    <head>
        <title>Sign Up</title>
        <link rel="stylesheet" href="../assets/icons/fontawesome/css/all.min.css">
        <link rel="stylesheet" href="../assets/css/style.css">
    </head>

    <body>

        <?php include '../components/header.php'; ?>

        <main>
            <div class="signupTitle">
                <h2><b>SIGN UP</b></h2>
            </div>

            <div class="signupContainer">

                <div class="inputGroup">
                    <input type="text" required>
                    <label>FIRST NAME*</label>
                </div>

                <div class="inputGroup">
                    <input type="text" required>
                    <label>LAST NAME*</label>
                </div>

                <div class="inputGroup">
                    <input type="date" required>
                    <label>BIRTHDAY*</label>
                </div>
                
                <div class="inputGroup">
                    <input type="text" maxlength required>
                    <label>PHONE*</label>
                </div>

            </div>
        </main>

        <?php include '../components/footer.php'; ?>
        
    </body>
</html>