<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../backend/db_connect.php';

$identity = getCurrentUserId();

// Redirect strangers
if ($identity['type'] === 'stranger') {
    header("Location: index.php?login_required=true");
    exit;
}
?>
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
    <?php 
    include '../backend/db_connect.php';

    $identity = getCurrentUserId();

    // Redirect strangers
    if ($identity['type'] === 'stranger') {
        header("Location: index.php?login_required=true");
        exit;
    }

    $id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
    $id_value = $identity['id'];

    // Fetch all order items for this user
    $query = "
        SELECT oi.*, o.order_id, o.order_status, o.created_at, o.total_amount,
               p.product_name, p.product_id,
               pi.image_url,
               py.method as payment_method
        FROM order_items oi
        JOIN orders o ON o.order_id = oi.order_id
        JOIN products p ON p.product_id = oi.product_id
        LEFT JOIN product_images pi ON pi.product_id = p.product_id AND pi.is_primary = 1
        LEFT JOIN payments py ON py.order_id = o.order_id
        WHERE o.$id_column = ?
        ORDER BY o.created_at DESC
    ";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Query preparation failed: " . $conn->error);
    }

    $bind_type = ($id_column === 'user_id') ? 'i' : 's';

    // Guests: resolve session string → real integer guest_id
    if ($id_column === 'guest_id') {
        $g = $conn->prepare("SELECT guest_id FROM guests WHERE session_id = ?");
        $g->bind_param("s", $id_value);
        $g->execute();
        $g_result = $g->get_result();
        $g->close();
        if ($g_result->num_rows > 0) {
            $id_value  = intval($g_result->fetch_assoc()['guest_id']);
            $bind_type = 'i';
        }
    }

    $stmt->bind_param($bind_type, $id_value);
    $stmt->execute();
    $result = $stmt->get_result();

    if (!$result) {
        die("Query execution failed: " . $conn->error);
    }

    $order_items = [];
    while ($row = $result->fetch_assoc()) {
        $order_items[] = $row;
    }
    $stmt->close();
    ?>
    <?php include '../components/header.php'; ?>

    <main class="mainBG">
        <button class="back-btn" onclick="history.back()" title="Go back"><i class="fas fa-arrow-left"></i> Back</button>
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
                        <button class="filter-option" onclick="sortByPrice()">By Price</button>

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
                    <div class="history-row history-head">
                        <span>No</span>
                        <span>Purchase Date</span>
                        <span>Product Name</span>
                        <span>Unit Price</span>
                        <span>Quantity</span>
                        <span>Subtotal</span>
                        <span style="text-align: center;">Status</span>
                        <span style="text-align: center;">Actions</span>
                    </div>

                    <!-- ROWS -->
                    <?php 
                    if (empty($order_items)) {
                        echo '<div style="grid-column: 1 / -1; text-align: center; padding: 40px 0; color: #aaa;">No order history found.</div>';
                    } else {
                        $index = 1;
                        foreach ($order_items as $item): 
                            $order_status = (!empty($item['order_status'])) ? $item['order_status'] : 'Pending';
                            $order_date = date('m-d-Y', strtotime($item['created_at']));
                            $order_time = date('H:i', strtotime($item['created_at']));
                            $product_name = htmlspecialchars($item['product_name']);
                            $price_at_purchase = $item['price_at_purchase'];
                            $quantity = $item['quantity'];
                            $subtotal = $price_at_purchase * $quantity;
                            
                            $img_url = $item['image_url'] 
                                ? '../assets/images/products/' . htmlspecialchars($item['image_url'])
                                : '../assets/images/brand_images/nocturne.png';
                            
                            $payment_method = ucfirst($item['payment_method'] ?? 'N/A');
                    ?>
                    <div class="history-row" data-name="<?= $product_name ?>" data-date="<?= $item['created_at'] ?>" data-status="<?= ucfirst($order_status) ?>">
                        <span><?= $index ?></span>
                        <span><?= $order_date ?><br><small><?= $order_time ?></small></span>
                        <span class="product-col">
                            <img src="<?= $img_url ?>" alt="<?= $product_name ?>">
                            <?= $product_name ?>
                        </span>
                        <span>₱<?= number_format($price_at_purchase, 2) ?></span>
                        <span><?= $quantity ?></span>
                        <span>₱<?= number_format($subtotal, 2) ?></span>
                        <span style="text-align: center;">
                            <span class="badge badge-<?= strtolower($order_status) ?>" style="padding: 6px 12px; border-radius: 4px; font-size: 0.85rem; display: inline-block;">
                                <?= ucfirst($order_status) ?>
                            </span>
                        </span>
                        <span>
                            <button class="view-link"
                                onclick="viewTransaction(this)"
                                data-title="Transaction Details"
                                data-img="<?= htmlspecialchars($img_url) ?>"
                                data-product="<?= $product_name ?>"
                                data-variant="<?= htmlspecialchars($item['variant'] ?? 'Variant Info') ?>"
                                data-qty="<?= $quantity ?>"
                                data-price="₱<?= number_format($price_at_purchase, 2) ?>"
                                data-subtotal="₱<?= number_format($subtotal, 2) ?>"
                                data-payment="<?= $payment_method ?>"
                                data-date="<?= $order_date ?>"
                                data-status="<?= ucfirst($order_status) ?>">
                                View
                            </button>
                        </span>
                    </div>
                    <?php 
                        $index++;
                        endforeach; 
                    } 
                    ?>

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