<?php
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'classes/Order.php';

requireLogin();

if ($_POST && isset($_POST['order_id'])) {
    $database = new Database();
    $db = $database->getConnection();
    $order = new Order($db);
    
    $order_id = (int)$_POST['order_id'];
    
    // Verify the order belongs to the current user
    $order_details = $order->getOrderDetails($order_id);
    
    if ($order_details && $order_details['user_id'] == $_SESSION['user_id']) {
        // Check if order can be cancelled
        if ($order->canCancelOrder($order_id)) {
            $order->order_id = $order_id;
            if ($order->cancelOrder()) {
                $_SESSION['success_message'] = 'Order cancelled successfully!';
            } else {
                $_SESSION['error_message'] = 'Failed to cancel order. Please try again.';
            }
        } else {
            $_SESSION['error_message'] = 'This order cannot be cancelled as it is already completed or cancelled.';
        }
    } else {
        $_SESSION['error_message'] = 'Order not found or access denied.';
    }
} else {
    $_SESSION['error_message'] = 'Invalid request.';
}

redirect('orders.php');
?>
