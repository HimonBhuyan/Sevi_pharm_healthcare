<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use eftec\bladeone\BladeOne;

$dataDir = __DIR__ . '/storage/data';
$isServerless = isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL']) || getenv('VERCEL') || !is_writable(__DIR__);

if ($isServerless) {
    $tempBase = sys_get_temp_dir() . '/servipharm';
    $cacheDir = $tempBase . '/cache';
    $runtimeDataDir = $tempBase . '/data';
} else {
    $cacheDir = __DIR__ . '/storage/cache';
    $runtimeDataDir = $dataDir;
}

if (!is_dir($cacheDir)) {
    @mkdir($cacheDir, 0777, true);
}
if (!is_dir($runtimeDataDir)) {
    @mkdir($runtimeDataDir, 0777, true);
}

if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrfToken = $_SESSION['csrf_token'];
if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_ACTIVE) {
    session_write_close();
}

$action = $_GET['action'] ?? '';
$page = $_GET['page'] ?? '';
$requestUri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Load Corporate & Product Dataset
$companyDataPath = $dataDir . '/company.json';
$siteData = [];
if (is_file($companyDataPath)) {
    $decoded = json_decode((string) file_get_contents($companyDataPath), true);
    if (is_array($decoded)) {
        $siteData = $decoded;
    }
}

// -------------------------------------------------------------
// ROUTE 1: Handle E-Commerce Order Checkout (?action=checkout or /api/checkout)
// -------------------------------------------------------------
if ($action === 'checkout' || str_ends_with($requestUri, '/api/checkout')) {
    header('Content-Type: application/json; charset=UTF-8');

    if ($requestMethod !== 'POST') {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed.']);
        exit;
    }

    $rawBody = file_get_contents('php://input');
    $payload = json_decode((string) $rawBody, true);
    if (!is_array($payload)) {
        $payload = $_POST;
    }

    $customerName = trim((string) ($payload['customer_name'] ?? ''));
    $customerEmail = trim((string) ($payload['customer_email'] ?? ''));
    $customerPhone = trim((string) ($payload['customer_phone'] ?? ''));
    $address = trim((string) ($payload['delivery_address'] ?? ''));
    $city = trim((string) ($payload['city'] ?? 'Ambarnath, Thane'));
    $pincode = trim((string) ($payload['pincode'] ?? '421503'));
    $deliverySpeed = trim((string) ($payload['delivery_speed'] ?? 'standard'));
    $paymentMethod = trim((string) ($payload['payment_method'] ?? 'UPI / QR Scan on Delivery'));
    $upiId = trim((string) ($payload['upi_id'] ?? ''));
    $couponCode = strtoupper(trim((string) ($payload['coupon_code'] ?? '')));
    $rxNote = trim((string) ($payload['rx_note'] ?? ''));
    $items = $payload['items'] ?? [];

    if (is_string($items)) {
        $items = json_decode($items, true) ?: [];
    }

    if ($customerName === '' || $customerEmail === '' || $customerPhone === '' || $address === '') {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Please complete your Name, Email, Mobile Number, and Delivery Address.'
        ]);
        exit;
    }

    if (!filter_var($customerEmail, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Please enter a valid email address for your order receipt.'
        ]);
        exit;
    }

    if (empty($items) || !is_array($items)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Your shopping cart is empty.'
        ]);
        exit;
    }

    // Index catalog by ID for authoritative server-side pricing
    $catalogById = [];
    foreach (($siteData['catalog'] ?? []) as $prod) {
        $catalogById[$prod['id']] = $prod;
    }

    $orderLines = [];
    $subtotal = 0;
    $mrpTotal = 0;

    foreach ($items as $cartItem) {
        $pid = (string) ($cartItem['id'] ?? '');
        $qty = max(1, min(50, (int) ($cartItem['qty'] ?? 1)));
        if (!isset($catalogById[$pid])) {
            continue;
        }
        $p = $catalogById[$pid];
        $lineTotal = (int) $p['price'] * $qty;
        $lineMrp = (int) $p['mrp'] * $qty;
        $subtotal += $lineTotal;
        $mrpTotal += $lineMrp;

        $orderLines[] = [
            'id' => $p['id'],
            'sku' => $p['sku'],
            'name' => $p['name'],
            'category' => $p['category'],
            'pack_size' => $p['pack_size'],
            'image' => $p['image'],
            'rx_required' => !empty($p['rx_required']),
            'unit_price' => (int) $p['price'],
            'unit_mrp' => (int) $p['mrp'],
            'qty' => $qty,
            'line_total' => $lineTotal
        ];
    }

    if (empty($orderLines)) {
        http_response_code(422);
        echo json_encode(['success' => false, 'message' => 'No valid catalog items found in order.']);
        exit;
    }

    $couponDiscount = 0;
    if ($couponCode === 'ORTHO15' && $subtotal >= 1500) {
        $couponDiscount = (int) round($subtotal * 0.15);
    } elseif (in_array($couponCode, ['SERVI10', 'AMBARNATH', 'WELCOME10', 'ORTHO15'], true)) {
        $couponDiscount = (int) round($subtotal * 0.10);
    }

    $netItemsTotal = max(0, $subtotal - $couponDiscount);
    if ($deliverySpeed === 'express') {
        $deliveryFee = $couponCode === 'AMBARNATH' ? 0 : 79;
    } else {
        $deliveryFee = $netItemsTotal >= 499 ? 0 : 49;
    }
    $grandTotal = $netItemsTotal + $deliveryFee;
    $totalSavings = ($mrpTotal - $subtotal) + $couponDiscount;

    $orderNumber = 'SPH-2026-' . strtoupper(substr(md5((string) microtime(true) . $customerEmail), 0, 5));

    $orderRecord = [
        'order_number' => $orderNumber,
        'status' => 'Confirmed & Queued for Dispatch',
        'estimated_delivery' => $deliverySpeed === 'express'
            ? 'Within 2–4 Hours (Ambarnath / Thane Priority)'
            : 'Same-Day / Next-Business-Day Delivery',
        'customer' => [
            'name' => htmlspecialchars($customerName, ENT_QUOTES, 'UTF-8'),
            'email' => htmlspecialchars($customerEmail, ENT_QUOTES, 'UTF-8'),
            'phone' => htmlspecialchars($customerPhone, ENT_QUOTES, 'UTF-8'),
            'address' => htmlspecialchars($address, ENT_QUOTES, 'UTF-8'),
            'city' => htmlspecialchars($city, ENT_QUOTES, 'UTF-8'),
            'pincode' => htmlspecialchars($pincode, ENT_QUOTES, 'UTF-8'),
        ],
        'delivery_speed' => $deliverySpeed,
        'payment_method' => htmlspecialchars($paymentMethod, ENT_QUOTES, 'UTF-8'),
        'upi_id' => htmlspecialchars($upiId, ENT_QUOTES, 'UTF-8'),
        'rx_note' => htmlspecialchars($rxNote, ENT_QUOTES, 'UTF-8'),
        'coupon_code' => $couponCode,
        'items' => $orderLines,
        'pricing' => [
            'mrp_total' => $mrpTotal,
            'subtotal' => $subtotal,
            'coupon_discount' => $couponDiscount,
            'delivery_fee' => $deliveryFee,
            'grand_total' => $grandTotal,
            'total_savings' => $totalSavings
        ],
        'created_at' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
    ];

    $ordersFile = $runtimeDataDir . '/orders.json';
    $existingOrders = [];
    if (is_file($ordersFile)) {
        $decoded = json_decode((string) file_get_contents($ordersFile), true);
        if (is_array($decoded)) {
            $existingOrders = $decoded;
        }
    }
    array_unshift($existingOrders, $orderRecord);
    @file_put_contents(
        $ordersFile,
        json_encode($existingOrders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    );

    echo json_encode([
        'success' => true,
        'order' => $orderRecord,
        'redirect_url' => '?page=orders&order=' . urlencode($orderNumber),
        'message' => "Order {$orderNumber} confirmed! Confirmation logged for {$customerEmail}."
    ]);
    exit;
}

// -------------------------------------------------------------
// ROUTE 2: Handle General / Prescription Inquiry (?action=inquiry or /api/inquiry)
// -------------------------------------------------------------
if ($action === 'inquiry' || str_ends_with($requestUri, '/api/inquiry')) {
    header('Content-Type: application/json; charset=UTF-8');

    if ($requestMethod !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Method not allowed. Please submit via POST.'
        ]);
        exit;
    }

    $name = trim((string) ($_POST['name'] ?? ''));
    $email = trim((string) ($_POST['email'] ?? ''));
    $phone = trim((string) ($_POST['phone'] ?? ''));
    $category = trim((string) ($_POST['category'] ?? 'Pharmaceutical Inquiry'));
    $product = trim((string) ($_POST['product'] ?? ''));
    $message = trim((string) ($_POST['message'] ?? ''));
    $submittedToken = (string) ($_POST['csrf_token'] ?? '');

    if (!empty($csrfToken) && !empty($submittedToken) && !hash_equals($csrfToken, $submittedToken)) {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'message' => 'Security token mismatch. Please refresh the page and try again.'
        ]);
        exit;
    }

    if ($name === '' || $email === '' || $message === '') {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Please complete your Name, Email Address, and Inquiry Details.'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(422);
        echo json_encode([
            'success' => false,
            'message' => 'Please enter a valid email address.'
        ]);
        exit;
    }

    $record = [
        'id' => uniqid('sp_inq_', false),
        'reference' => 'SP-' . strtoupper(substr(md5((string) microtime(true) . $email), 0, 6)),
        'name' => htmlspecialchars($name, ENT_QUOTES, 'UTF-8'),
        'email' => htmlspecialchars($email, ENT_QUOTES, 'UTF-8'),
        'phone' => htmlspecialchars($phone, ENT_QUOTES, 'UTF-8'),
        'category' => htmlspecialchars($category, ENT_QUOTES, 'UTF-8'),
        'product' => htmlspecialchars($product, ENT_QUOTES, 'UTF-8'),
        'message' => htmlspecialchars($message, ENT_QUOTES, 'UTF-8'),
        'submitted_at' => date('Y-m-d H:i:s'),
        'ip' => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1'
    ];

    $inquiriesFile = $runtimeDataDir . '/inquiries.json';
    $existing = [];
    if (is_file($inquiriesFile)) {
        $decoded = json_decode((string) file_get_contents($inquiriesFile), true);
        if (is_array($decoded)) {
            $existing = $decoded;
        }
    }
    $existing[] = $record;
    @file_put_contents(
        $inquiriesFile,
        json_encode($existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
    );

    echo json_encode([
        'success' => true,
        'reference' => $record['reference'],
        'message' => "Thank you, {$name}! Inquiry {$record['reference']} has been logged for servipharmhealthcare@gmail.com."
    ]);
    exit;
}

$siteData['csrf_token'] = $csrfToken;
$siteData['current_year'] = date('Y');

// Load Stored Orders for Order Tracking / Invoice View
$ordersFile = $runtimeDataDir . '/orders.json';
$allOrders = [];
if (is_file($ordersFile)) {
    $decoded = json_decode((string) file_get_contents($ordersFile), true);
    if (is_array($decoded)) {
        $allOrders = $decoded;
    }
}

$lookupQuery = trim((string) ($_GET['order'] ?? ''));
$selectedOrder = null;
if ($lookupQuery !== '') {
    foreach ($allOrders as $ord) {
        if (
            strcasecmp((string) ($ord['order_number'] ?? ''), $lookupQuery) === 0 ||
            strcasecmp((string) ($ord['customer']['email'] ?? ''), $lookupQuery) === 0
        ) {
            $selectedOrder = $ord;
            break;
        }
    }
} elseif (!empty($allOrders)) {
    $selectedOrder = $allOrders[0];
}

$siteData['orders'] = array_slice($allOrders, 0, 10);
$siteData['selected_order'] = $selectedOrder;
$siteData['lookup_query'] = $lookupQuery;

// Determine Target Blade View
$targetView = 'pages.home';
if ($page === 'catalog' || str_ends_with($requestUri, '/catalog')) {
    $targetView = 'pages.catalog';
} elseif ($page === 'checkout' || str_ends_with($requestUri, '/checkout') || str_ends_with($requestUri, '/cart')) {
    $targetView = 'pages.checkout';
} elseif ($page === 'orders' || str_ends_with($requestUri, '/orders')) {
    $targetView = 'pages.orders';
}

$siteData['active_page'] = $targetView;
$siteData['initial_category'] = preg_replace('/[^a-z\-]/', '', strtolower((string) ($_GET['category'] ?? 'all'))) ?: 'all';

// Render Blade View
try {
    $blade = new BladeOne(__DIR__ . '/views', $cacheDir, BladeOne::MODE_AUTO);
    echo $blade->run($targetView, $siteData);
} catch (\Throwable $e) {
    http_response_code(500);
    echo '<div style="font-family:system-ui,sans-serif;padding:2rem;background:#09111a;color:#fda4af;">';
    echo '<h2>Blade Render Error</h2>';
    echo '<p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>';
    echo '</div>';
}
