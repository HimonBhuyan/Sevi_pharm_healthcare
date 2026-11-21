@extends('layouts.app')

@section('title', 'Shopping Cart & Secure Checkout | SERVI PHARM HEALTHCARE PRIVATE LIMITED')

@section('content')
<section class="section checkout-page-section">
    <div class="container">
        <!-- Breadcrumb & Progress Stepper -->
        <div class="checkout-top-bar">
            <a href="./#catalog" class="back-store-link">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Product Storefront</span>
            </a>

            <div class="checkout-steps-indicator" aria-label="Checkout progress">
                <div class="chk-step is-active">
                    <span class="chk-step-num">1</span>
                    <span>Shopping Cart</span>
                </div>
                <span class="chk-step-line"></span>
                <div class="chk-step is-active">
                    <span class="chk-step-num">2</span>
                    <span>Delivery &amp; Payment</span>
                </div>
                <span class="chk-step-line"></span>
                <div class="chk-step">
                    <span class="chk-step-num">3</span>
                    <span>Order Invoice</span>
                </div>
            </div>
        </div>

        <div class="section-head" style="margin-bottom: 1.75rem;">
            <span class="section-tag"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Direct Retail Checkout</span>
            <h1 class="section-title">Review Your Cart &amp; Complete Checkout</h1>
            <p class="section-subtitle">
                Dispatched directly from <strong>{{ $company['name'] }}</strong> (CIN: <code>{{ $company['cin'] }}</code>) • Kulgaon, Ambarnath, Thane – {{ $company['pin_code'] }}.
            </p>
        </div>

        <!-- Empty Cart Notice on Checkout Page -->
        <div id="page-cart-empty" class="bento-card cart-empty-state" hidden>
            <div class="cart-empty-icon">
                <i class="fa-solid fa-basket-shopping" aria-hidden="true"></i>
            </div>
            <h2>Your Shopping Cart is Currently Empty</h2>
            <p>Add pharmaceuticals, diagnostic devices, orthopedic supports, or clinical toilet articles to proceed with checkout.</p>
            <div style="margin-top: 1.25rem; display: flex; justify-content: center; gap: 0.75rem; flex-wrap: wrap;">
                <a href="./#catalog" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
                    <span>Browse All 12 Store Products</span>
                </a>
                <a href="?page=orders" class="btn btn-outline btn-lg">
                    <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                    <span>View Past Orders / Invoices</span>
                </a>
            </div>
        </div>

        <!-- Full-Screen 2-Column Cart + Checkout Workspace -->
        <div id="page-checkout-workspace" class="checkout-page-grid">
            <!-- Left Column: Cart Items + Upsell Strip + Customer & Payment Form -->
            <div class="checkout-main-col">
                <!-- 1. Cart Items Card -->
                <div class="bento-card checkout-block">
                    <div class="checkout-block-head">
                        <div>
                            <span class="step-pill">STEP 01</span>
                            <h2 id="page-cart-heading">Items in Your Healthcare Cart (0)</h2>
                        </div>
                        <button type="button" class="clear-cart-btn" id="page-clear-cart-btn">
                            <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Empty Cart
                        </button>
                    </div>

                    <!-- Free Delivery Progress -->
                    <div class="free-delivery-banner" style="margin-bottom: 1rem;">
                        <div class="free-delivery-text">
                            <i class="fa-solid fa-truck-fast" aria-hidden="true"></i>
                            <span id="page-free-delivery-msg">Add ₹499 of items for FREE Express Local Delivery!</span>
                        </div>
                        <div class="free-delivery-track">
                            <div class="free-delivery-fill" id="page-free-delivery-fill" style="width: 0%;"></div>
                        </div>
                    </div>

                    <div id="page-cart-items-list" class="page-cart-items-list"></div>

                    <!-- Quick Cross-Sell / Frequently Added Together -->
                    <div class="upsell-strip">
                        <div class="upsell-title">
                            <i class="fa-solid fa-sparkles" aria-hidden="true"></i>
                            <span>Frequently Added Healthcare Essentials</span>
                        </div>
                        <div class="upsell-grid">
                            @foreach(array_slice($catalog ?? [], 0, 4) as $up)
                                <div class="upsell-item">
                                    <img src="{{ $up['image'] }}" alt="{{ $up['name'] }}" class="upsell-thumb">
                                    <div class="upsell-info">
                                        <strong>{{ $up['name'] }}</strong>
                                        <span>₹{{ number_format((int) $up['price']) }} <del>₹{{ number_format((int) $up['mrp']) }}</del></span>
                                    </div>
                                    <button type="button" class="btn btn-soft btn-sm" data-add-cart="{{ $up['id'] }}" data-qty="1">
                                        <i class="fa-solid fa-plus" aria-hidden="true"></i> Add
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- 2. Delivery & Payment Form Card -->
                <div class="bento-card checkout-block">
                    <div class="checkout-block-head">
                        <div>
                            <span class="step-pill">STEP 02</span>
                            <h2>Patient / Receiver Delivery &amp; Payment</h2>
                        </div>
                        <span class="mono-chip"><i class="fa-solid fa-lock" aria-hidden="true"></i> 256-Bit Encrypted Checkout</span>
                    </div>

                    <form id="page-checkout-form" class="sp-form">
                        <input type="hidden" name="csrf_token" value="{{ $csrf_token ?? '' }}">

                        <div class="form-grid-2">
                            <div class="form-field">
                                <label for="pchk-name">Full Name <span class="req">*</span></label>
                                <input type="text" id="pchk-name" name="customer_name" required placeholder="e.g., Sneha Kulkarni" autocomplete="name">
                            </div>
                            <div class="form-field">
                                <label for="pchk-email">Email Address (for Tax Invoice) <span class="req">*</span></label>
                                <input type="email" id="pchk-email" name="customer_email" required placeholder="you@example.com" autocomplete="email">
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-field">
                                <label for="pchk-phone">Mobile / WhatsApp Number <span class="req">*</span></label>
                                <input type="tel" id="pchk-phone" name="customer_phone" required placeholder="+91 98XXX XXXXX" autocomplete="tel">
                            </div>
                            <div class="form-field">
                                <label for="pchk-pincode">PIN Code (Serviceability Check) <span class="req">*</span></label>
                                <div class="pincode-check-row">
                                    <input type="text" id="pchk-pincode" name="pincode" required value="421503" placeholder="421503" maxlength="6" autocomplete="postal-code">
                                    <span class="pincode-badge" id="pincode-status-badge">
                                        <i class="fa-solid fa-circle-check" aria-hidden="true"></i> Ambarnath Express Zone
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-field">
                                <label for="pchk-address">Flat / House No., Building &amp; Street <span class="req">*</span></label>
                                <input type="text" id="pchk-address" name="delivery_address" required placeholder="e.g., Flat 302, Shivsundar Society, Manorama Nagar" autocomplete="street-address">
                            </div>
                            <div class="form-field">
                                <label for="pchk-city">Area, City &amp; State</label>
                                <input type="text" id="pchk-city" name="city" value="Kulgaon, Ambarnath, Thane, Maharashtra" autocomplete="address-level2">
                            </div>
                        </div>

                        <!-- Delivery Speed Selector -->
                        <div class="form-field">
                            <label>Select Delivery Speed</label>
                            <div class="radio-card-grid">
                                <label class="radio-card is-selected">
                                    <input type="radio" name="delivery_speed" value="standard" checked>
                                    <div>
                                        <strong>Standard Local Delivery</strong>
                                        <span>Same-Day / Next-Day • FREE on orders ₹499+ (else ₹49)</span>
                                    </div>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="delivery_speed" value="express">
                                    <div>
                                        <strong><i class="fa-solid fa-bolt" aria-hidden="true"></i> 2-Hour Ambarnath Priority</strong>
                                        <span>Express cold-chain &amp; urgent dispatch • ₹79 (FREE with code AMBARNATH)</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Payment Method Cards -->
                        <div class="form-field">
                            <label>Select Payment Mode</label>
                            <div class="radio-card-grid radio-card-grid-3">
                                <label class="radio-card is-selected">
                                    <input type="radio" name="payment_method" value="UPI / QR Scan (GPay, PhonePe, Paytm)" checked>
                                    <div>
                                        <strong><i class="fa-solid fa-qrcode" aria-hidden="true"></i> UPI / QR Pay</strong>
                                        <span>GPay, PhonePe, Paytm</span>
                                    </div>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="payment_method" value="Cash on Delivery (COD)">
                                    <div>
                                        <strong><i class="fa-solid fa-money-bill-wave" aria-hidden="true"></i> Cash on Delivery</strong>
                                        <span>Pay cash at doorstep</span>
                                    </div>
                                </label>
                                <label class="radio-card">
                                    <input type="radio" name="payment_method" value="Card on Delivery (POS Machine)">
                                    <div>
                                        <strong><i class="fa-regular fa-credit-card" aria-hidden="true"></i> Card on Delivery</strong>
                                        <span>Swipe/Tap on POS</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="form-grid-2">
                            <div class="form-field">
                                <label for="pchk-upi">UPI ID (Optional for Instant Payment Request)</label>
                                <input type="text" id="pchk-upi" name="upi_id" placeholder="yourname@okaxis or pay via QR on delivery">
                            </div>
                            <div class="form-field">
                                <label for="pchk-rx">Prescription / Doctor Reference (if ordering Rx items)</label>
                                <input type="text" id="pchk-rx" name="rx_note" placeholder="e.g., Dr. Patil Clinic Rx / Verify on delivery">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="page-place-order-btn">
                            <i class="fa-solid fa-shield-check" aria-hidden="true"></i>
                            <span id="page-place-order-text">Confirm Order &amp; Generate Invoice</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- Right Column: Sticky Order Summary & Coupon Engine -->
            <aside class="checkout-sidebar-col">
                <div class="bento-card sticky-summary-card">
                    <h3 class="summary-card-title">Order Summary &amp; Coupons</h3>

                    <!-- Clickable Promo Coupon Chips -->
                    <div class="promo-chips-box">
                        <span class="d-label">Available Store Coupons (Click to Apply):</span>
                        <div class="promo-chips-list">
                            <button type="button" class="promo-chip is-active" data-apply-coupon="SERVI10">
                                <strong>SERVI10</strong>
                                <span>Extra 10% OFF Storewide</span>
                            </button>
                            <button type="button" class="promo-chip" data-apply-coupon="AMBARNATH">
                                <strong>AMBARNATH</strong>
                                <span>10% OFF + Free Express Delivery</span>
                            </button>
                            <button type="button" class="promo-chip" data-apply-coupon="ORTHO15">
                                <strong>ORTHO15</strong>
                                <span>15% OFF on Orders ₹1,500+</span>
                            </button>
                        </div>
                    </div>

                    <div class="coupon-box">
                        <div class="coupon-input-row">
                            <i class="fa-solid fa-tag" aria-hidden="true"></i>
                            <input type="text" id="page-coupon-input" value="SERVI10" placeholder="Enter coupon code">
                            <button type="button" class="btn btn-soft btn-sm" id="page-apply-coupon-btn">Apply</button>
                        </div>
                        <small class="coupon-status" id="page-coupon-status">Coupon <strong>SERVI10</strong> active (10% extra discount)</small>
                    </div>

                    <div class="cart-summary-box">
                        <div class="summary-row">
                            <span>Total Retail MRP</span>
                            <span id="page-summary-mrp">₹0</span>
                        </div>
                        <div class="summary-row is-discount">
                            <span>Product MRP Savings</span>
                            <span id="page-summary-discount">-₹0</span>
                        </div>
                        <div class="summary-row is-discount" id="page-summary-coupon-row">
                            <span>Coupon Discount (<code id="page-summary-coupon-code">SERVI10</code>)</span>
                            <span id="page-summary-coupon-val">-₹0</span>
                        </div>
                        <div class="summary-row">
                            <span>Delivery Charges</span>
                            <span id="page-summary-delivery">FREE</span>
                        </div>
                        <div class="summary-row is-total">
                            <span>Net Payable Total</span>
                            <strong id="page-summary-grand-total">₹0</strong>
                        </div>
                    </div>

                    <div class="savings-callout" id="page-savings-callout">
                        <i class="fa-solid fa-piggy-bank" aria-hidden="true"></i>
                        <span>You are saving <strong id="page-total-savings">₹0</strong> on this healthcare order!</span>
                    </div>

                    <div class="checkout-trust-notes">
                        <div><i class="fa-solid fa-circle-check" aria-hidden="true"></i> 100% Genuine Batch-Verified Products</div>
                        <div><i class="fa-solid fa-snowflake" aria-hidden="true"></i> 2°C–8°C Cold-Chain Protected Dispatch</div>
                        <div><i class="fa-solid fa-building-columns" aria-hidden="true"></i> Official Invoice with CIN {{ $company['cin'] }}</div>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

