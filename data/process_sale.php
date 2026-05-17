<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/../database/Database.php';

$db = new Database();
$return = ['valid' => false];

try {
    if(isset($_POST['items']) && isset($_POST['customer_name'])){
        $items = json_decode($_POST['items'], true);
        $customerName = trim($_POST['customer_name']) ?: 'Walk-in';
        $paymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'Cash';
        $discount = floatval($_POST['discount'] ?? 0);
        $amountPaid = floatval($_POST['amount_paid'] ?? 0);
        
        if(empty($items)){
            $return['msg'] = 'No items in cart.';
            echo json_encode($return);
            exit;
        }

        // Calculate totals
        $subtotal = 0;
        foreach($items as $item){
            $subtotal += floatval($item['price']) * floatval($item['qty']);
        }
        $total = max(0, $subtotal - $discount);
        $change = max(0, $amountPaid - $total);

        // Generate reference number
        $reference = 'HL-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 6));
        
        $userId = isset($_SESSION['user_logged']) ? $_SESSION['user_logged'] : null;

        // Start transaction
        $db->Begin();

        // 1. Insert sale
        $db->insertRow(
            "INSERT INTO product_sales (sale_reference, sale_customer_name, sale_total, sale_discount, sale_amount_paid, sale_change, sale_payment_method, processed_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$reference, $customerName, $total, $discount, $amountPaid, $change, $paymentMethod, $userId]
        );
        $saleId = $db->lastID();

        // 2. Insert sale items & deduct stock
        foreach($items as $item){
            $itemId = (isset($item['type']) && $item['type'] == 'service') ? null : intval($item['id']);
            $qty = floatval($item['qty']);
            $unitPrice = floatval($item['price']);
            $itemSubtotal = $qty * $unitPrice;
            $itemName = $item['name'];

            // Insert sale item
            $db->insertRow(
                "INSERT INTO sale_items (sale_id, item_id, item_name, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?, ?)",
                [$saleId, $itemId, $itemName, $qty, $unitPrice, $itemSubtotal]
            );

            // Deduct inventory stock only if it's a product
            if(!isset($item['type']) || $item['type'] != 'service'){
                $db->updateRow(
                    "UPDATE inventory SET quantity = GREATEST(0, quantity - ?) WHERE id = ?",
                    [$qty, $itemId]
                );

                // Log the stock out
                $db->insertRow(
                    "INSERT INTO inventory_logs (item_id, type, quantity, reason) VALUES (?, 'Stock Out', ?, ?)",
                    [$itemId, $qty, "Sale: $reference - $customerName"]
                );
            }
        }

        $db->Commit();

        $return['valid'] = true;
        $return['sale_id'] = $saleId;
        $return['reference'] = $reference;
        $return['msg'] = 'Sale completed successfully!';
    }
} catch (Exception $ex) {
    if($db->inTransaction()){
        $db->Rollback();
    }
    $return['msg'] = 'Error: ' . $ex->getMessage();
}

echo json_encode($return);
$db->Disconnect();
