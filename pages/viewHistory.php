    <!DOCTYPE html>
    <html lang="en">
    <head> 
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Homme d'Or - History</title>

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
        <link rel="stylesheet" href="../assets/css/viewTabs.css">

        <style>
            .filter-dropdown {
                position: relative;
            }

            .filter-menu {
                display: none;
                position: absolute;
                top: 45px;
                right: 0;
                background: #222;
                padding: 10px;
                min-width: 150px;
                z-index: 9999;
                border: 1px solid #c9a961;
            }

            .filter-menu.show {
                display: block;
            }

            .filter-option {
                display: block;
                width: 100%;
                background: none;
                border: none;
                color: #fff;
                padding: 8px;
                text-align: left;
            }

            .filter-option:hover {
                background: #333;
            }

            #statusFilter {
                width: 100%;
                padding: 8px;
                border: none;
                background: #222;
                color: #fff;
            }

            #statusFilter option {
                color: white; 
                background-color: #222;
                border: 1px solid #c9a961;
            }

            .view-link{
                background-color: black;
                border: none;
            }
        </style>
    </head>

    <body> 
    <?php include '../components/header.php'; ?>

    <main class="mainBG">
        <div class="h-tabs">
            <h1 class="v-header">Purchase History</h1>

            <!-- CONTROLS -->
            <div class="history-controls">
                <input type="text" id="searchInput" placeholder="Search by product name">
                <button class="search-btn" onclick="searchProduct()">Search</button>

                <div class="filter-dropdown">
                    <button class="filter-btn" onclick="toggleFilter()">
                        <i class="fa-solid fa-filter"></i>
                    </button>
                    <div class="filter-menu">
                        <button class="filter-option" onclick="sortByDate()">Purchase Date</button>
                        <button class="filter-option" onclick="sortAZ()">Alphabetical</button>
                        <button class="filter-option"  onclick="sortByPrice()">By Price</button>

                        <!-- STATUS DROPDOWN -->
                        <select id="statusFilter" class="filter-option" onchange="filterStatus()">
                            <option value="">All Orders</option>
                            <option value="Delivered">Delivered</option>
                            <option value="Pending">Pending</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                </div>
            </div>

            <!-- TABLE -->
            <div class="history-table-container">
                <div class="history-table">
                    <!-- HEADER -->
                    <div class="history-row history-head">
                        <span>No</span>
                        <span>Purchase Date</span>
                        <span>Product Name</span>
                        <span>Unit Price</span>
                        <span>Quantity</span>
                        <span>Subtotal</span>
                        <span>Actions</span>
                    </div>

                    <!-- ROWS -->
                    <div class="history-row" data-name="Homme d’Or Éternel" data-date="2026-03-10" data-status="Delivered">
                        <span>1</span>
                        <span>03-10-2026<br><small>14:31</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Homme d’Or Éternel
                        </span>
                        <span>₱1,250.00</span>
                        <span>1</span>
                        <span>₱1,250.00</span>
                        <span>
                            <button class="view-link" 
                                onclick="viewTransaction(this)"
                                data-title="Transaction Details"
                                data-img="../assets/images/products_images/nocturne.png"
                                data-product="Homme d’Or Éternel"
                                data-variant="50ml • Oud Majestueux"
                                data-qty="1"
                                data-price='₱1,250.00'
                                data-subtotal='₱1,250.00'
                                data-payment="Credit Card"
                                data-date="2026-03-10"
                                data-status="Delivered">
                                View
                            </button>
                        </span>
                    </div>

                    <div class="history-row" data-name="Homme d’Or Voyage" data-date="2026-02-11" data-status="Delivered">
                        <span>2</span>
                        <span>02-11-2026<br><small>14:15</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Homme d’Or Voyage
                        </span>
                        <span>₱3,500.00</span>
                        <span>2</span>
                        <span>₱7,000.00</span>
                        <span>
                            <button class="view-link" 
                                onclick="viewTransaction(this)"
                                data-title="Transaction Details"
                                data-img="../assets/images/products_images/nocturne.png"
                                data-product="Homme d’Or Voyage"
                                data-variant="100ml • Ambre Nomade"
                                data-qty="2"
                                data-price='₱3,500.00'
                                data-subtotal='₱7,000.00'
                                data-payment="Cash on Delivery"
                                data-date="2026-02-11"
                                data-status="Delivered">
                                View
                            </button>
                        </span>
                    </div>

                    <div class="history-row" data-name="Homme d’Or Élixir" data-date="2026-04-10" data-status="Cancelled">
                        <span>3</span>
                        <span>04-10-2026<br><small>14:15</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Homme d’Or Élixir
                        </span>
                        <span>₱2,800.00</span>
                        <span>1</span>
                        <span>₱2,800.00</span>
                        <span>
                            <button class="view-link" 
                                onclick="viewTransaction(this)"
                                data-title="Transaction Details"
                                data-img="../assets/images/products_images/nocturne.png"
                                data-product="Homme d’Or Élixir"
                                data-variant="100ml • Cuir Sublime"
                                data-qty="1"
                                data-price='₱2,800.00'
                                data-subtotal='₱2,800.00'
                                data-payment="GCash"
                                data-date="2026-04-10"
                                data-status="Cancelled">
                                View
                            </button>
                        </span>
                    </div>

                    <div class="history-row" data-name="Homme d’Or Zenith" data-date="2026-02-14" data-status="Delivered">
                        <span>4</span>
                        <span>02-14-2026<br><small>10:20</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Homme d’Or Zenith
                        </span>
                        <span>₱1,500.00</span>
                        <span>3</span>
                        <span>₱4,500.00</span>
                        <span>
                            <button class="view-link" 
                                onclick="viewTransaction(this)"
                                data-title="Transaction Details"
                                data-img="../assets/images/products_images/nocturne.png"
                                data-product="Homme d’Or Zenith"
                                data-variant="100ml • Santal Imperial"
                                data-qty="3"
                                data-price='₱1,500.00'
                                data-subtotal='₱4,500.00'
                                data-payment="Credit Card"
                                data-date="2026-02-14"
                                data-status="Delivered">
                                View
                            </button>
                        </span>
                    </div>

                    <div class="history-row" data-name="Homme d’Or Mystique" data-date="2026-04-12" data-status="Pending">
                        <span>5</span>
                        <span>04-12-2026<br><small>16:45</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Homme d’Or Mystique
                        </span>
                        <span>₱2,200.00</span>
                        <span>1</span>
                        <span>₱2,200.00</span>
                        <span>
                            <button class="view-link" 
                                onclick="viewTransaction(this)"
                                data-title="Transaction Details"
                                data-img="../assets/images/products_images/nocturne.png"
                                data-product="Homme d’Or Mystique"
                                data-variant="50ml • Vetiver Éclipse"
                                data-qty="1"
                                data-price='₱2,200.00'
                                data-subtotal='₱2,200.00'
                                data-payment="Cash on Delivery"
                                data-date="2026-04-12"
                                data-status="Pending">
                                View
                            </button>
                        </span>
                    </div>

                    <div class="history-row" data-name="Homme d’Or Voyageur" data-date="2026-03-07" data-status="Delivered">
                        <span>6</span>
                        <span>03-07-2026<br><small>11:30</small></span>
                        <span class="product-col">
                            <img src="../assets/images/products_images/nocturne.png">
                            Homme d’Or Voyageur
                        </span>
                        <span>₱3,000.00</span>
                        <span>2</span>
                        <span>₱6,000.00</span>
                        <span>
                            <button class="view-link" 
                                onclick="viewTransaction(this)"
                                data-title="Transaction Details"
                                data-img="../assets/images/products_images/nocturne.png"
                                data-product="Homme d’Or Voyageur"
                                data-variant="100ml • Patchouli Mystère"
                                data-qty="2"
                                data-price='₱3,000.00'
                                data-subtotal='₱6,000.00'
                                data-payment="Cash on Delivery"
                                data-date="2026-03-07"
                                data-status="Delivered">
                                View
                            </button>
                        </span>
                    </div>

                </div>
            </div>
        </div>
    </main>

    <?php include '../components/footer.php'; ?>

    <script>
        const table = document.querySelector(".history-table");
        const header = document.querySelector(".history-head");
        const originalRows = Array.from(document.querySelectorAll(".history-row:not(.history-head)"));
        const modal = document.getElementById("transactionModal");

        // SEARCH
        function searchProduct() {
            const input = document.getElementById("searchInput").value.toLowerCase();
            originalRows.forEach(row => {
                const name = row.dataset.name.toLowerCase();
                row.style.display = name.includes(input) ? "grid" : "none";
            });
        }

        // SEARCH ON ENTER KEY
        document.getElementById("searchInput").addEventListener("keyup", function(e) {
            if (e.key === "Enter") searchProduct();
        });

        // SORT A-Z
        function sortAZ() {
            const rows = [...originalRows];
            rows.sort((a, b) => a.dataset.name.localeCompare(b.dataset.name));
            renderRows(rows);
        }

        // SORT BY DATE (newest first)
        function sortByDate() {
            const rows = [...originalRows];
            rows.sort((a, b) => new Date(b.dataset.date) - new Date(a.dataset.date));
            renderRows(rows);
        }

        // SORT BY PRICE (lowest to highest)
        function sortByPrice() {
            const rows = [...originalRows];
            rows.sort((a, b) => {
                const priceA = parseFloat(a.children[3].textContent.replace(/[₱,]/g, ''));
                const priceB = parseFloat(b.children[3].textContent.replace(/[₱,]/g, ''));
                return priceA - priceB; // ascending order
            });
            renderRows(rows);
        }

        // FILTER BY STATUS
        function filterStatus() {
            const status = document.getElementById("statusFilter").value;
            const rows = originalRows.filter(row => !status || row.dataset.status === status);
            renderRows(rows);
        }

        // RESET TABLE
        function resetTable() {
            renderRows(originalRows);
            document.getElementById("statusFilter").value = "";
        }

        // TOGGLE FILTER MENU
        function toggleFilter() {
            document.querySelector(".filter-menu").classList.toggle("show");
        }

        // CLOSE FILTER MENU ON OUTSIDE CLICK
        document.addEventListener("click", function(e) {
            const dropdown = document.querySelector(".filter-dropdown");
            if (!dropdown.contains(e.target)) {
                document.querySelector(".filter-menu").classList.remove("show");
            }
        });

        // RENDER ROWS — moves DOM nodes instead of wiping innerHTML
        // Preserves originalRows references and onclick handlers
        function renderRows(rows) {
            Array.from(table.querySelectorAll(".history-row:not(.history-head)")).forEach(r => r.remove());
            rows.forEach((row, index) => {
                row.style.display = "grid";
                row.children[0].textContent = index + 1;
                table.appendChild(row);
            });
        }

        // OPEN MODAL — modal lives in footer.php, always outside stacking context
        function openModal(btn) {
            const row = btn.closest(".history-row");

            document.getElementById("modalDate").textContent = row.children[1].innerText;
            document.getElementById("modalProduct").textContent = row.dataset.name;
            document.getElementById("modalPrice").textContent = row.children[3].innerText;
            document.getElementById("modalQty").textContent = row.children[4].innerText;
            document.getElementById("modalSubtotal").textContent = row.children[5].innerText;
            document.getElementById("modalStatus").textContent = row.dataset.status;

            modal.style.display = "block";
            document.body.style.overflow = "hidden";
        }

        // CLOSE MODAL
        function closeModal() {
            modal.style.display = "none";
            document.body.style.overflow = "";
        }

        // CLOSE ON BACKDROP CLICK
        modal.addEventListener("click", function(e) {
            if (e.target === modal) closeModal();
        });

        // CLOSE ON × BUTTON
        document.querySelector(".trans-close-btn").addEventListener("click", closeModal);

        // CLOSE ON ESC KEY
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") closeModal();
        });
    </script>

    </body>
    </html>