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
                <h2><b>SIGN IN</b></h2>
            </div>

            <div class="signupContainerlog">

                <div class="inputGrouplog">
                    <input type="email" required>
                    <label>EMAIL*</label>
                </div>

                <div class="inputGrouplogPas">
                    <input type="password" required>
                    <label>PASSWORD*</label>
                </div>

                <div class="inputGroupCheckboxlog">
                    <input type="checkbox" required>
                    <label> Remember Me</label>
                    
                </div>

                <div class="forgotP">
                    <a href="forgotP.php">Forgot Password</a>

                </div>

                
                <div class="logBtn">
                    <button>LOGIN</button>

                </div>
                
                <div class="orContainer">
                    <div class="line1"></div>
                    <div class="line2"></div>

                    <p>or</p>
                    
                    <h2><i><b>Member Exclusive</b></i></h2>

                    <div class="logParagraph">
                        <p>Unlock a world of scent. Gain early access to 
                        limited-edition releases and private events. 
                        Members enjoy priority access to limited releases, bespoke consultations, and 
                        signature rewards tailored to your unique profile.</p>
                    </div>
                    
                    <div class="discover">
                        <a href="index.php">DISCOVER</a>
                    </div>

                </div>

            </div>
        </main>

        <?php include '../components/footer.php'; ?>
        
    </body>
</html>