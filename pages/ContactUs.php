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
        <?php include '../components/header.php'; ?>

        <main class="mainBG">
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
                    </div>

                    <div class="profileInfo">
                        <div class="infoGroup">
                            <label>Name</label>
                            <p id="displayName">John Doe</p>
                        </div>

                        <div class="infoGroup">
                            <label>Birthday</label>
                            <p id="displayBirthday">January 16, 1995</p>
                        </div>

                        <div class="infoGroup">
                            <label>Contact Number</label>
                            <p id="displayContact">+63 912 345 6789</p>
                        </div>

                        <div class="infoGroup">
                            <label>Email</label>
                            <p id="displayEmail">johndoe@gmail.com</p>
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
                                <div class="orderIcon">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="orderDetails">
                                    <h4>Processing</h4>
                                    <p>3 orders</p>
                                </div>
                            </div>

                            <div class="orderItem">
                                <div class="orderIcon">
                                    <i class="fas fa-star"></i>
                                </div>
                                <div class="orderDetails">
                                    <h4>To Review</h4>
                                    <p>2 orders</p>
                                </div>
                            </div>

                            <div class="orderItem">
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
                            <button class="viewAllBtn" onclick="window.location.href='viewWishlist.php'">View All</button>
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

                        
                    </div>
                    <button class="signOutBtn">Sign Out</button>

                </div>
            </div>
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
                                <img src="../assets/images/default-profile.png" alt="Profile" id="modalProfileImage">
                            </div>
                            <input type="file" id="profilePhotoInput" accept="image/*" style="display: none;">
                            <button type="button" class="uploadPhotoBtn" onclick="document.getElementById('profilePhotoInput').click()">
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
                                <label>Full Name</label>
                                <input type="text" id="editName" placeholder="Enter full name">
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
                            <h3>Change Password</h3>
                            <p class="passwordNote">Leave blank if you don't want to change your password</p>
                            
                            <div class="editModalFormGroup">
                                <label>Current Password</label>
                                <input type="password" id="currentPassword" placeholder="Enter current password">
                            </div>

                            <div class="editModalFormGroup">
                                <label>New Password</label>
                                <input type="password" id="newPassword" placeholder="Enter new password">
                            </div>

                            <div class="editModalFormGroup">
                                <label>Confirm New Password</label>
                                <input type="password" id="confirmPassword" placeholder="Confirm new password">
                            </div>

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

        <!-- Toast msg -->
        <div id="toastMessage" class="toastMessage"></div>

        <?php include '../components/footer.php'; ?>

        <script src="../assets/js/editProfile.js"></script>
        
    
    </body>
</html>