<?php
include(__DIR__ . '/../../backend/db_connect.php');

// Get date range from GET params, default: current month
function getDateRange($prefix) {
    $from = $_GET["{$prefix}_from"] ?? date('Y-m-01');
    $to   = $_GET["{$prefix}_to"]   ?? date('Y-m-d');
    return [$from, $to];
}

// graph grouping based on date range
function getGraphGroup($from, $to) {
    $days = (strtotime($to) - strtotime($from)) / 86400;
    return $days <= 30 ? 'WEEK' : 'MONTH';
}

// graph query label based on grouping
function graphLabelSQL($group, $dateCol = 'created_at') {
    if ($group === 'WEEK') {
        return "CONCAT('Wk ', WEEK($dateCol))";
    }
    return "DATE_FORMAT($dateCol, '%b %Y')";
}

function groupBySQL($group, $dateCol = 'created_at') {
    if ($group === 'WEEK') {
        return "WEEK($dateCol)";
    }
    return "YEAR($dateCol), MONTH($dateCol)";
}


// REVENUE TAB

[$rev_from, $rev_to] = getDateRange('rev');

$rev_total = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS val
     FROM orders
     WHERE order_status = 'completed'
     AND DATE(created_at) BETWEEN '$rev_from' AND '$rev_to'"))['val'];

$rev_avg = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(AVG(total_amount), 0) AS val
     FROM orders
     WHERE order_status = 'completed'
     AND DATE(created_at) BETWEEN '$rev_from' AND '$rev_to'"))['val'];

// Compare with previous period for growth
$period_days   = max(1, (strtotime($rev_to) - strtotime($rev_from)) / 86400);
$prev_rev_from = date('Y-m-d', strtotime($rev_from) - $period_days * 86400);
$prev_rev_to   = date('Y-m-d', strtotime($rev_from) - 86400);

$prev_rev = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(total_amount), 0) AS val
     FROM orders
     WHERE order_status = 'completed'
     AND DATE(created_at) BETWEEN '$prev_rev_from' AND '$prev_rev_to'"))['val'];

$rev_growth = $prev_rev > 0
    ? round((($rev_total - $prev_rev) / $prev_rev) * 100, 1)
    : null;

// Graph data
$rev_group = getGraphGroup($rev_from, $rev_to);
$rev_graph = mysqli_query($conn,
    "SELECT " . graphLabelSQL($rev_group) . " AS label,
            COALESCE(SUM(total_amount), 0) AS val
     FROM orders
     WHERE order_status = 'completed'
     AND DATE(created_at) BETWEEN '$rev_from' AND '$rev_to'
     GROUP BY " . groupBySQL($rev_group) . "
     ORDER BY " . groupBySQL($rev_group));

$rev_labels = []; $rev_values = [];
while ($r = mysqli_fetch_assoc($rev_graph)) {
    $rev_labels[] = $r['label'];
    $rev_values[] = (float) $r['val'];
}

// Table
$rev_table = mysqli_query($conn,
    "SELECT o.order_id, o.fname, o.lname, o.total_amount,
            p.method, o.order_status, o.created_at
     FROM orders o
     LEFT JOIN payments p ON o.order_id = p.order_id
     WHERE o.order_status = 'completed'
     AND DATE(o.created_at) BETWEEN '$rev_from' AND '$rev_to'
     ORDER BY o.created_at DESC");


// SALES TAB

[$sal_from, $sal_to] = getDateRange('sal');

$sal_total = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM orders
     WHERE DATE(created_at) BETWEEN '$sal_from' AND '$sal_to'"))['val'];

$sal_units = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(SUM(oi.quantity), 0) AS val
     FROM order_items oi
     JOIN orders o ON oi.order_id = o.order_id
     WHERE DATE(o.created_at) BETWEEN '$sal_from' AND '$sal_to'"))['val'];

$sal_avg = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(AVG(total_amount), 0) AS val
     FROM orders
     WHERE DATE(created_at) BETWEEN '$sal_from' AND '$sal_to'"))['val'];

$sal_top_cat = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT c.category_name, SUM(oi.quantity) AS total_sold
     FROM order_items oi
     JOIN orders o ON oi.order_id = o.order_id
     JOIN products p ON oi.product_id = p.product_id
     JOIN categories c ON p.category_id = c.category_id
     WHERE DATE(o.created_at) BETWEEN '$sal_from' AND '$sal_to'
     GROUP BY c.category_id
     ORDER BY total_sold DESC
     LIMIT 1"));

$sal_group = getGraphGroup($sal_from, $sal_to);
$sal_graph = mysqli_query($conn,
    "SELECT " . graphLabelSQL($sal_group) . " AS label, COUNT(*) AS val
     FROM orders
     WHERE DATE(created_at) BETWEEN '$sal_from' AND '$sal_to'
     GROUP BY " . groupBySQL($sal_group) . "
     ORDER BY " . groupBySQL($sal_group));

$sal_labels = []; $sal_values = [];
while ($r = mysqli_fetch_assoc($sal_graph)) {
    $sal_labels[] = $r['label'];
    $sal_values[] = (int) $r['val'];
}

$sal_table = mysqli_query($conn,
    "SELECT o.order_id, o.fname, o.lname, o.total_amount,
            o.order_status, o.created_at,
            SUM(oi.quantity) AS total_items
     FROM orders o
     LEFT JOIN order_items oi ON o.order_id = oi.order_id
     WHERE DATE(o.created_at) BETWEEN '$sal_from' AND '$sal_to'
     GROUP BY o.order_id
     ORDER BY o.created_at DESC");


// ORDERS TAB

[$ord_from, $ord_to] = getDateRange('ord');

$ord_total     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE DATE(created_at) BETWEEN '$ord_from' AND '$ord_to'"))['val'];
$ord_completed = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'completed' AND DATE(created_at) BETWEEN '$ord_from' AND '$ord_to'"))['val'];
$ord_pending   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'pending' AND DATE(created_at) BETWEEN '$ord_from' AND '$ord_to'"))['val'];
$ord_cancelled = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM orders WHERE order_status = 'cancelled' AND DATE(created_at) BETWEEN '$ord_from' AND '$ord_to'"))['val'];

$ord_group = getGraphGroup($ord_from, $ord_to);
$ord_graph = mysqli_query($conn,
    "SELECT " . graphLabelSQL($ord_group) . " AS label, COUNT(*) AS val
     FROM orders
     WHERE DATE(created_at) BETWEEN '$ord_from' AND '$ord_to'
     GROUP BY " . groupBySQL($ord_group) . "
     ORDER BY " . groupBySQL($ord_group));

$ord_labels = []; $ord_values = [];
while ($r = mysqli_fetch_assoc($ord_graph)) {
    $ord_labels[] = $r['label'];
    $ord_values[] = (int) $r['val'];
}

$ord_table = mysqli_query($conn,
    "SELECT o.order_id, o.fname, o.lname, o.total_amount,
            o.order_status, o.created_at,
            COUNT(oi.item_id) AS item_count
     FROM orders o
     LEFT JOIN order_items oi ON o.order_id = oi.order_id
     WHERE DATE(o.created_at) BETWEEN '$ord_from' AND '$ord_to'
     GROUP BY o.order_id
     ORDER BY o.created_at DESC");


// PRODUCTS TAB

[$prod_from, $prod_to] = getDateRange('prod');

$prod_total     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products"))['val'];
$prod_low_stock = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE product_status = 'low-stock'"))['val'];
$prod_out       = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS val FROM products WHERE product_status = 'out-of-stock'"))['val'];

$prod_best = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT p.product_name, SUM(oi.quantity) AS total_sold
     FROM order_items oi
     JOIN orders o ON oi.order_id = o.order_id
     JOIN products p ON oi.product_id = p.product_id
     WHERE DATE(o.created_at) BETWEEN '$prod_from' AND '$prod_to'
     GROUP BY p.product_id
     ORDER BY total_sold DESC
     LIMIT 1"));

$prod_graph = mysqli_query($conn,
    "SELECT p.product_name AS label, SUM(oi.quantity) AS val
     FROM order_items oi
     JOIN orders o ON oi.order_id = o.order_id
     JOIN products p ON oi.product_id = p.product_id
     WHERE DATE(o.created_at) BETWEEN '$prod_from' AND '$prod_to'
     GROUP BY p.product_id
     ORDER BY val DESC
     LIMIT 10");

$prod_labels = []; $prod_values = [];
while ($r = mysqli_fetch_assoc($prod_graph)) {
    $prod_labels[] = $r['label'];
    $prod_values[] = (int) $r['val'];
}

$prod_table = mysqli_query($conn,
    "SELECT p.product_name, c.category_name,
            SUM(oi.quantity) AS units_sold,
            SUM(oi.quantity * oi.price_at_purchase) AS revenue,
            p.stock_qty, p.product_status
     FROM order_items oi
     JOIN orders o ON oi.order_id = o.order_id
     JOIN products p ON oi.product_id = p.product_id
     LEFT JOIN categories c ON p.category_id = c.category_id
     WHERE DATE(o.created_at) BETWEEN '$prod_from' AND '$prod_to'
     GROUP BY p.product_id
     ORDER BY units_sold DESC");


// CUSTOMERS TAB

[$cust_from, $cust_to] = getDateRange('cust');

$cust_total = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM users
     WHERE DATE(created_at) BETWEEN '$cust_from' AND '$cust_to'"))['val'];

$cust_returning = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COUNT(*) AS val FROM (
        SELECT user_id FROM orders
        WHERE user_id IS NOT NULL
        AND DATE(created_at) BETWEEN '$cust_from' AND '$cust_to'
        GROUP BY user_id
        HAVING COUNT(*) > 1
     ) AS t"))['val'];

$cust_ltv = mysqli_fetch_assoc(mysqli_query($conn,
    "SELECT COALESCE(AVG(total_spent), 0) AS val FROM (
        SELECT user_id, SUM(total_amount) AS total_spent
        FROM orders
        WHERE user_id IS NOT NULL
        GROUP BY user_id
     ) AS t"))['val'];

$cust_group = getGraphGroup($cust_from, $cust_to);
$cust_graph = mysqli_query($conn,
    "SELECT " . graphLabelSQL($cust_group) . " AS label, COUNT(*) AS val
     FROM users
     WHERE DATE(created_at) BETWEEN '$cust_from' AND '$cust_to'
     GROUP BY " . groupBySQL($cust_group) . "
     ORDER BY " . groupBySQL($cust_group));

$cust_labels = []; $cust_values = [];
while ($r = mysqli_fetch_assoc($cust_graph)) {
    $cust_labels[] = $r['label'];
    $cust_values[] = (int) $r['val'];
}

$cust_table = mysqli_query($conn,
    "SELECT u.fname, u.lname, u.email,
            COUNT(o.order_id) AS total_orders,
            COALESCE(SUM(o.total_amount), 0) AS total_spent,
            MAX(o.created_at) AS last_order
     FROM users u
     LEFT JOIN orders o ON u.user_id = o.user_id
     WHERE DATE(u.created_at) BETWEEN '$cust_from' AND '$cust_to'
     GROUP BY u.user_id
     ORDER BY total_spent DESC");