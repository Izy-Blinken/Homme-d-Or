<!DOCTYPE html>
<html>
    <head>
        <title>Profile</title>
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
        
    </head>

    <body>
        <?php
            session_start();
            $isLoggedIn = isset($_SESSION['user_id']);
        ?>

        <?php include '../components/header.php'; ?>

        <main class="mainBG">


            
            <?php if (!$isLoggedIn): ?>

                <div class="notLoggedInCTA">
                    <div class="ctaBox">
                        <div class="ctaIcon">
                            <i class="fas fa-user-circle"></i>
                        </div>
                        <h2>You're not logged in</h2>
                        <p>Sign in or create an account to view your profile, track orders, and manage your wishlist.</p>
                        <div class="ctaButtons">
                            <button class="ctaLoginBtn" onclick="openLoginModal()">Log In</button>
                            <button class="ctaRegisterBtn" onclick="openSignupModal()">Create Account</button>
                        </div>
                    </div>
                </div>
                   
            <?php else: ?>

                <div class="profileContainer">
                    <div class="colFirst">
                        <div class="headerRow">
                            <h3>Profile</h3>
                        </div>
                        <div class="dividerF"></div>

                        <div class="profileImageSection">
                            <div class="profileImageFrame">
                                <img src="../assets/images/products_images/customerPic.png" alt="Profile" class="profileImage" id="currentProfileImage">
                            </div>
                            <button class="addProfileBtn" onclick="document.getElementById('profilePhotoInput').click()">Add Profile Photo</button>
                            <input type="file" id="profilePhotoInput" accept="image/*" style="display:none;" onchange="uploadPhoto(this)">
                        </div>

                        <div class="profileInfo">
                            <div class="infoGroup">
                                <label>Name</label>
                                <p id="displayName">Loading...</p>
                            </div>

                            <div class="infoGroup">
                                <label>Birthday</label>
                                <p id="displayBirthday">—</p>
                            </div>

                            <div class="infoGroup">
                                <label>Contact Number</label>
                                <p id="displayContact">—</p>
                            </div>

                            <div class="infoGroup">
                                <label>Email</label>
                                <p id="displayEmail">—</p>
                            </div>
                        </div>

                        <div class="profileActions">
                            <button class="editProfileBtn" onclick="openEditModal()">Edit Profile</button>
                        </div>
                    </div>

                    <div class="rightSide">
                        <div class="colSecond">
                            <div class="headerRow">
                                <h3>Orders</h3>
                                <button class="viewAllBtn" onclick="window.location.href='viewAllTabs.php'">View All</button>
                            </div>
                            <div class="divider"></div>

                            <div class="orderOverview">
                                <div class="orderItem">
                                    <button class="orderIcon" onclick="window.location.href='viewAllTabs.php'">
                                        <i class="fas fa-box"></i>
                                    </button>
                                    <div class="orderDetails">
                                        <h4>Processing</h4>
                                        <p id="countProcessing">0 orders</p>
                                    </div>
                                </div>

                                <div class="orderItem">
                                    <button class="orderIcon" onclick="window.location.href='viewAllTabs.php'">
                                        <i class="fas fa-star"></i>
                                    </button>
                                    <div class="orderDetails">
                                        <h4>To Review</h4>
                                        <p id="countReview">0 orders</p>
                                    </div>
                                </div>

                                <div class="orderItem">
                                    <button class="orderIcon" onclick="window.location.href='viewAllTabs.php'">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    <div class="orderDetails">
                                        <h4>Completed</h4>
                                        <p id="countCompleted">0 orders</p>
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

                            <div class="historyList" id="historyList">
                                <p>Loading...</p>
                            </div>
                        </div>

                        <div class="colFourth">
                            <div class="headerRow">
                                <h3>Wishlist</h3>
                                <button class="viewAllBtn" onclick="window.location.href='viewWishlist.php'">View All</button>
                            </div>
                            <div class="divider"></div>

                            <div class="wishlistGrid" id="wishlistGrid">
                                <p>Loading...</p>
                            </div>
                        </div>

                        <button class="signOutBtn" onclick="signOut()">Sign Out</button>
                    </div>
                </div>

                <div class="dangerZone">
                    <h3>Danger Zone</h3>
                    <div class="divider"></div>
                    <p class="dangerDesc">Once you delete your account, all your data will be permanently removed and cannot be recovered.</p>
                    <button class="deleteAccountBtn" onclick="openDeleteModal()">Delete Account</button>
                </div> 

            <?php endif; ?>

        </main>

        <!-- Edit Profile Modal -->
        <div id="editProfileModal" class="editProfileModal">
            <div class="editModalOverlay" onclick="closeEditModal()"></div>
            <div class="editModalContent">
                <div class="editModalHeader">
                    <h2>Edit Profile</h2>
                    <button class="editModalCloseBtn" onclick="closeEditModal()">×</button>
                </div>

                <form id="editProfileForm" onsubmit="saveProfile(event)">
                    <div class="editModalBody">

                        <!-- Profile Photo -->
                        <div class="editModalPhotoSection">
                            <div class="editModalPhotoFrame">
                                <img src="../assets/images/products_images/customerPic.png" alt="Profile" id="modalProfileImage">
                            </div>
                            <input type="file" id="modalPhotoInput" accept="image/*" style="display: none;" onchange="uploadPhoto(this)">
                            <button type="button" class="uploadPhotoBtn" onclick="document.getElementById('modalPhotoInput').click()">
                                Upload Photo
                            </button>
                            <button type="button" class="removePhotoBtn" onclick="removePhoto()">
                                Remove Photo
                            </button>
                        </div>

                        <!-- Personal Information -->
                        <div class="editModalFormSection">
                            <h3>Personal Information</h3>

                            <div class="editModalFormGroup">
                                <label>First Name</label>
                                <input type="text" id="editName" placeholder="Enter first name">
                            </div>

                            <div class="editModalFormGroup">
                                <label>Last Name</label>
                                <input type="text" id="editLname" placeholder="Enter last name">
                            </div>

                            <div class="editModalFormGroup">
                                <label>Birthday</label>
                                <input type="date" id="editBirthday">
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="editModalFormSection">
                            <h3>Contact Information</h3>

                            <div class="editModalFormGroup">
                                <label>Contact Number</label>
                                <input type="tel" id="editContact" placeholder="09123456789" maxlength="11">
                            </div>

                            <div class="editModalFormGroup">
                                <label>Email Address</label>
                                <input type="email" id="editEmail" placeholder="email@example.com">
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="editModalFormSection">
                            <h3>Password</h3>
                            <button type="button" class="editModalSaveBtn" style="width:100%;" onclick="closeEditModal(); openChangePasswordModal();">
                                Change Password
                            </button>

                            <div class="forgotP" style="margin-left:80%;">
                                <a href="forgotPassword.php">Forgot Password?</a>
                            </div>
                        </div>

                    </div>

                    <div class="editModalFooter">
                        <button type="button" class="editModalCancelBtn" onclick="closeEditModal()">Cancel</button>
                        <button type="submit" class="editModalSaveBtn">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Success msg -->
        <div id="successMessage" class="profileSuccessMessage">
            Profile updated successfully!
        </div>

        <!-- Confirmation msg -->
        <div id="confirmationModal" class="confirmationModal">
            <div class="confirmationBox">
                <h3>Discard Changes?</h3>
                <p>You have unsaved changes. Are you sure you want to close?</p>
                <div class="confirmationButtons">
                    <button class="cancelConfirmBtn" onclick="cancelDiscard()">Keep Editing</button>
                    <button class="confirmBtn" onclick="confirmDiscard()">Discard</button>
                </div>
            </div>
        </div>

        <!-- Change Password Modal -->
        <div id="changePasswordModal" class="verificationModal" style="display:none;">
            <div class="verificationModalContent">
                <button class="closeModal" onclick="closeChangePasswordModal()">
                    <i class="fas fa-times"></i>
                </button>

                <div class="modalHeader">
                    <h2>Change Password</h2>
                    <p class="modalSubtitle">We'll send a verification code to your email first</p>
                </div>

                <!-- Step 1: Current password + send OTP -->
                <div id="cpStep1">
                    <div class="formGroup" style="margin-bottom:16px;">
                        <label>Current Password</label>
                        <div class="passwordInputWrapper">
                            <input type="password" id="cpCurrentPassword" placeholder="Enter current password">
                            <button type="button" class="togglePassword" onclick="toggleCpVisibility('cpCurrentPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <p id="cpStep1Error" style="color:red;font-size:13px;display:none;margin-bottom:12px;"></p>
                    <button type="button" class="verifyButton" onclick="sendChangePasswordOtp()">Send Verification Code</button>
                </div>

                <!-- Step 2: OTP -->
                <div id="cpStep2" style="display:none;">
                    <p class="modalSubtitle" style="margin-bottom:16px;">Enter the 6-digit code sent to your email</p>
                    <div class="codeInputContainer">
                        <input type="text" maxlength="1" class="cpCodeInput" inputmode="numeric" />
                        <input type="text" maxlength="1" class="cpCodeInput" inputmode="numeric" />
                        <input type="text" maxlength="1" class="cpCodeInput" inputmode="numeric" />
                        <input type="text" maxlength="1" class="cpCodeInput" inputmode="numeric" />
                        <input type="text" maxlength="1" class="cpCodeInput" inputmode="numeric" />
                        <input type="text" maxlength="1" class="cpCodeInput" inputmode="numeric" />
                    </div>
                    <p id="cpOtpError" style="color:red;text-align:center;font-size:13px;display:none;margin-top:8px;"></p>
                    <button type="button" class="verifyButton" style="margin-top:16px;" onclick="verifyCpOtp()">Verify Code</button>
                    <div class="resendSection">
                        <p>Didn't receive the code?</p>
                        <button type="button" class="resendButton" id="cpResendBtn" onclick="sendChangePasswordOtp(true)">Resend Code</button>
                    </div>
                </div>

                <!-- Step 3: New password -->
                <div id="cpStep3" style="display:none;">
                    <div class="passwordRequirements">
                        <p class="requirementsTitle">Password must contain:</p>
                        <ul>
                            <li id="cp-req-length"><i class="fas fa-circle"></i> At least 8 characters</li>
                            <li id="cp-req-uppercase"><i class="fas fa-circle"></i> One uppercase letter</li>
                            <li id="cp-req-lowercase"><i class="fas fa-circle"></i> One lowercase letter</li>
                            <li id="cp-req-number"><i class="fas fa-circle"></i> One number</li>
                        </ul>
                    </div>

                    <div class="formGroup" style="margin-bottom:16px;">
                        <label>New Password</label>
                        <div class="passwordInputWrapper">
                            <input type="password" id="cpNewPassword" placeholder="Enter new password">
                            <button type="button" class="togglePassword" onclick="toggleCpVisibility('cpNewPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <div class="passwordStrength">
                            <div class="strengthBar">
                                <div class="strengthBarFill" id="cpStrengthBarFill"></div>
                            </div>
                            <span class="strengthText" id="cpStrengthText"></span>
                        </div>
                    </div>

                    <div class="formGroup" style="margin-bottom:16px;">
                        <label>Confirm New Password</label>
                        <div class="passwordInputWrapper">
                            <input type="password" id="cpConfirmPassword" placeholder="Confirm new password">
                            <button type="button" class="togglePassword" onclick="toggleCpVisibility('cpConfirmPassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                        <span class="passwordMatch" id="cpPasswordMatch"></span>
                    </div>

                    <p id="cpResetError" style="color:red;text-align:center;font-size:13px;display:none;margin-bottom:12px;"></p>
                    <button type="button" class="verifyButton" onclick="submitNewPassword()">Save New Password</button>
                </div>

                <!-- Step 4: Success -->
                <div id="cpStep4" style="display:none;">
                    <div class="modalHeader">
                        <h2>Password Changed!</h2>
                        <p class="modalSubtitle">Your password has been updated successfully.</p>
                    </div>
                    <button type="button" class="verifyButton" onclick="closeChangePasswordModal()">Done</button>
                </div>

            </div>
        </div>

        <!-- Delete Account Modal -->
        <div id="deleteAccountModal" class="editProfileModal" style="display:none;">
            <div class="editModalOverlay" onclick="closeDeleteModal()"></div>
            <div class="editModalContent" style="max-width:420px;">
        
                <!-- Step 1: Confirm intent -->
                <div id="deleteStep1">
                    <div class="editModalHeader">
                        <h2>Delete Account</h2>
                        <button class="editModalCloseBtn" onclick="closeDeleteModal()">×</button>
                    </div>
                    <div class="editModalBody">
                        <p style="margin-bottom:12px;">Are you sure you want to delete your account?</p>
                        <p style="color:#c0392b;font-size:14px;">This will permanently delete all your data including orders, wishlist, and reviews. This cannot be undone.</p>
                    </div>
                    <div class="editModalFooter">
                        <button type="button" class="editModalCancelBtn" onclick="closeDeleteModal()">Cancel</button>
                        <button type="button" class="deleteAccountBtn" onclick="sendDeleteOtp()">Yes, Delete My Account</button>
                    </div>
                </div>
        
                <!-- Step 2: Enter OTP -->
                <div id="deleteStep2" style="display:none;">
                    <div class="editModalHeader">
                        <h2>Enter Verification Code</h2>
                        <button class="editModalCloseBtn" onclick="closeDeleteModal()">×</button>
                    </div>
                    <div class="editModalBody">
                        <p style="margin-bottom:16px;">We sent a 6-digit code to your email. Enter it below to confirm account deletion.</p>
                        <div class="editModalFormGroup">
                            <label>Verification Code</label>
                            <input type="text" id="deleteOtpInput" placeholder="Enter 6-digit code" maxlength="6">
                        </div>
                        <p id="deleteOtpError" style="color:#c0392b;font-size:13px;margin-top:8px;display:none;"></p>
                        <button type="button" class="uploadPhotoBtn" style="margin-top:10px;" onclick="sendDeleteOtp()">Resend Code</button>
                    </div>
                    <div class="editModalFooter">
                        <button type="button" class="editModalCancelBtn" onclick="closeDeleteModal()">Cancel</button>
                        <button type="button" class="deleteAccountBtn" onclick="confirmDeleteAccount()">Confirm Delete</button>
                    </div>
                </div>
        
            </div>
        </div>
 

        <!-- Toast msg -->
        <div id="generalToast" class="generalToast"></div>

        <?php include '../components/footer.php'; ?>

        <script src="../assets/js/editProfile.js"></script>
    </body>
</html>