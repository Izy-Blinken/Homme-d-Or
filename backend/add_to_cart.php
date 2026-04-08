<?php
// Start session and connect to DB
session_start();
include_once 'db_connect.php'; 

// Prepare to send JSON back to the javascript
header('Content-Type: application/json');

$identity = getCurrentUserId();

// 1. BOUNCER: Stop strangers
if ($identity['type'] === 'stranger') {
    ob_clean();
    echo json_encode([
        'status' => 'error', 
        'message' => 'Please login or continue as guest to shop.'
    ]);
    exit;
}

if (isset($_POST['product_id'])) {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;

    $id_column = ($identity['type'] === 'user_id') ? 'user_id' : 'guest_id';
    $id_value = $identity['id'];

    // --- FAILSAFE: Ensure guest exists in the database before proceeding ---
    if ($id_column === 'guest_id') {

        // The session stores a string like "guest_abc123"
        // We look up the real integer guest_id using the session_id column
        $check_guest = $conn->prepare("SELECT guest_id FROM guests WHERE session_id = ?");
        $check_guest->bind_param("s", $id_value);
        $check_guest->execute();
        $check_result = $check_guest->get_result();
        $check_guest->close();

        if ($check_result->num_rows === 0) {
            // Guest not found — insert a new guest row using the session string
            $insert_guest = $conn->prepare("INSERT INTO guests (session_id) VALUES (?)");
            $insert_guest->bind_param("s", $id_value);

            if (!$insert_guest->execute()) {
                ob_clean();
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to create guest session: ' . $insert_guest->error
                ]);
                $insert_guest->close();
                exit;
            }

            // Get the real auto-incremented guest_id
            $real_guest_id = $conn->insert_id;
            $insert_guest->close();
        } else {
            // Guest already exists — fetch their real integer guest_id
            $real_guest_id = $check_result->fetch_assoc()['guest_id'];
        }

        // Override id_value with the real integer guest_id for the cart queries
        $id_value = $real_guest_id;
    }

    // 2. Check if product is already in cart
    $check_stmt = $conn->prepare("SELECT cart_id, quantity FROM cart WHERE product_id = ? AND $id_column = ?");
    $check_stmt->bind_param("ii", $product_id, $id_value);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    $check_stmt->close();

    if ($result->num_rows > 0) {
        // Product already in cart — update quantity
        $row = $result->fetch_assoc();
        $new_quantity = $row['quantity'] + $quantity;
        
        $update_stmt = $conn->prepare("UPDATE cart SET quantity = ? WHERE cart_id = ?");
        $update_stmt->bind_param("ii", $new_quantity, $row['cart_id']);
        
        if ($update_stmt->execute()) {
            $update_stmt->close();
            ob_clean();
            echo json_encode(['status' => 'success', 'message' => 'Cart updated!']);
            exit;
        } else {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Failed to update cart: ' . $update_stmt->error]);
            $update_stmt->close();
            exit;
        }

    } else {
        // Product not in cart — insert new row
        $insert_stmt = $conn->prepare("INSERT INTO cart (product_id, quantity, $id_column) VALUES (?, ?, ?)");
        $insert_stmt->bind_param("iii", $product_id, $quantity, $id_value);
        
        if ($insert_stmt->execute()) {
            $insert_stmt->close();
            ob_clean();
            echo json_encode(['status' => 'success', 'message' => 'Added to cart!']);
            exit;
        } else {
            ob_clean();
            echo json_encode(['status' => 'error', 'message' => 'Failed to add to cart: ' . $insert_stmt->error]);
            $insert_stmt->close();
            exit;
        }
    }

} else {
    ob_clean();
    echo json_encode(['status' => 'error', 'message' => 'Invalid product request.']);
    exit;
}
?>