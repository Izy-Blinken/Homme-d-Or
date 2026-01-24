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

                <div class="inputGroupBD">
                    <input type="date" required>
                    <label>BIRTHDAY*</label>
                </div>
                
                <div class="inputGroup">

                    <input type="text" maxlength="11" required>
                    <label>PHONE*</label>
                </div>

                <div class="inputGroup">
                    <input type="email" required>
                    <label>EMAIL*</label>
                </div>

                <div class="inputGroup">
                    <input type="password" required>
                    <label>PASSWORD*</label>
                </div>

                <div class="inputGroup">
                    <input type="password" required>
                    <label>CONFIRM PASSWORD*</label>
                </div>

                <div class="inputGroupCheckbox">
                    <input type="checkbox" required>
                    <label>I have read and agreed on <a href="terms.php">Terms and Conditions</a></label>
                    
                </div>

                
                <div class="regBtn">
                    <button>LOGIN</button>

                </div>

                <div class="regLog">
                    <label><a href="login.php">LOGIN</a>or<a href="index.php">CANCEL</a></label>

                </div>

            </div>
        </main>

        <?php include '../components/footer.php'; ?>
        
    </body>
</html>