<?php
// Enable error reporting untuk debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

try {
    error_log('=== ITEM.PHP DEBUG ===');
    error_log('Session keranjang: ' . print_r($_SESSION['keranjang'] ?? 'not set', true));

    if (!empty($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
        $grandTotal = 0;
        $itemCount = 0;
        
        foreach ($_SESSION['keranjang'] as $id => $item) {
            // Validasi data item
            if (!isset($item['nama']) || !isset($item['harga']) || !isset($item['qty'])) {
                error_log("Invalid item data for ID $id: " . print_r($item, true));
                continue;
            }
            
            $nama = htmlspecialchars($item['nama']);
            $harga = floatval($item['harga']);
            $qty = intval($item['qty']);
            $total = $harga * $qty;
            $grandTotal += $total;
            $itemCount++;
            
            echo "<div class='cart-item' data-id='" . htmlspecialchars($id) . "'>
                    <div class='cart-item-info'>
                      <span class='cart-item-name'>{$nama} ({$qty})</span>
                      <span class='cart-item-price'>Rp " . number_format($total, 0, ',', '.') . "</span>
                    </div>
                    <button class='cart-remove-item' data-id='" . htmlspecialchars($id) . "'>
                      <i class='ri-delete-bin-line'></i>
                    </button>
                  </div>";
        }
        
        if ($itemCount > 0) {
            echo "<div class='cart-total'>Total: Rp " . number_format($grandTotal, 0, ',', '.') . "</div>";
            echo "<div class='cart-actions'>
                    <button class='cart-clear' id='cart-clear'>Kosongkan Keranjang</button>
                    <button class='cart-checkout'>Checkout</button>
                  </div>";
            
            error_log("Cart displayed: $itemCount items, total: $grandTotal");
        } else {
            echo "<p class='cart-empty'>Keranjang kosong (tidak ada item valid).</p>";
            error_log("Cart is empty (no valid items)");
        }
    } else {
        echo "<p class='cart-empty'>Keranjang kosong.</p>";
        error_log("Cart is empty (session not set or not array)");
    }
    
} catch (Exception $e) {
    error_log('Error in item.php: ' . $e->getMessage());
    echo "<p class='cart-error'>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
} catch (Error $e) {
    error_log('Fatal error in item.php: ' . $e->getMessage());
    echo "<p class='cart-error'>Sistem error dalam menampilkan keranjang.</p>";
}
?>