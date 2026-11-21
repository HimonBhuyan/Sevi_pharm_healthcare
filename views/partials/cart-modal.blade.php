<dialog id="cart-modal" class="sp-dialog cart-dialog" closedby="any" aria-labelledby="cart-modal-title">
    <div class="sp-dialog-card cart-dialog-card">
        <div class="sp-dialog-header">
            <div>
                <span class="pill-badge pill-badge-sm">
                    <span class="pulse-dot"></span> ServiPharm Direct Retail Checkout
                </span>
                <h2 id="cart-modal-title" class="sp-dialog-title">Your Healthcare Shopping Cart</h2>
                <p class="sp-dialog-subtitle">100% Genuine Batch-Verified Products • Dispatched from Kulgaon, Ambarnath (421503)</p>
            </div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <a href="?page=checkout" class="btn btn-soft btn-sm">
                    <i class="fa-solid fa-up-right-from-square" aria-hidden="true"></i>
                    <span>Full Checkout Page</span>
                </a>
                <button type="button" class="icon-btn dialog-close-btn" command="close" commandfor="cart-modal" data-close-cart aria-label="Close shopping cart">
                    <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                </button>
            </div>
        </div>

        <!-- Free Delivery Progress Banner -->
        <div class="free-delivery-banner" id="free-delivery-banner">
            <div class="free-delivery-text">
                <i class="fa-solid fa-truck-fast" aria-hidden="true"></i>
                <span id="free-delivery-msg">Add ₹499 of items for FREE Express Local Delivery!</span>
            </div>
            <div class="free-delivery-track">
                <div class="free-delivery-fill" id="free-delivery-fill" style="width: 0%;"></div>
            </div>
        </div>

        <!-- Empty Cart State -->
        <div id="cart-empty-state" class="cart-empty-state">
            <div class="cart-empty-icon">
                <i class="fa-solid fa-basket-shopping" aria-hidden="true"></i>
            </div>
            <h3>Your Healthcare Cart is Empty</h3>
            <p>Browse our Pharmaceuticals, Medical Devices, Orthopedic Braces, and Clinical Toiletries to add items.</p>
            <button type="button" class="btn btn-primary" command="close" commandfor="cart-modal" data-close-cart>
                <span>Start Shopping</span>
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </button>
        </div>

        <!-- Active Cart + Checkout Grid -->
        <div id="cart-active-layout" class="cart-active-layout" hidden>
            <div class="cart-items-col">
                <div class="cart-col-header">
                    <span id="cart-items-heading">Cart Items (0)</span>
                    <button type="button" class="clear-cart-btn" id="clear-cart-btn">
                        <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Clear Cart
                    </button>
                </div>

                <div id="cart-items-list" class="cart-items-list"></div>

                <!-- Coupon Box -->
                <div class="coupon-box">
                    <div class="coupon-input-row">
                        <i class="fa-solid fa-tag" aria-hidden="true"></i>
                        <input type="text" id="cart-coupon-input" placeholder="Enter coupon (e.g. SERVI10)" value="SERVI10">
                        <button type="button" class="btn btn-soft btn-sm" id="apply-coupon-btn">Apply</button>
                    </div>
                    <small class="coupon-status" id="coupon-status-msg">Tip: Apply <strong>SERVI10</strong> for an extra 10% introductory discount.</small>
                </div>

                <!-- Price Summary Card -->
                <div class="cart-summary-box">
                    <div class="summary-row">
                        <span>Total MRP</span>
                        <span id="summary-mrp">₹0</span>
                    </div>
                    <div class="summary-row is-discount">
                        <span>Retail MRP Discount</span>
                        <span id="summary-discount">-₹0</span>
                    </div>
                    <div class="summary-row is-discount" id="summary-coupon-row" hidden>
                        <span>Coupon Savings (<code id="summary-coupon-code">SERVI10</code>)</span>
                        <span id="summary-coupon-val">-₹0</span>
                    </div>
                    <div class="summary-row">
                        <span>Delivery Charges</span>
                        <span id="summary-delivery">₹49</span>
                    </div>
                    <div class="summary-row is-total">
                        <span>Net Payable Amount</span>
                        <strong id="summary-grand-total">₹0</strong>
                    </div>
                </div>
            </div>

            <!-- Checkout Form Column -->
            <div class="cart-checkout-col">
                <h3 class="checkout-col-title"><i class="fa-solid fa-shield-heart" aria-hidden="true"></i> Delivery &amp; Payment Details</h3>
                <form id="checkout-form" class="sp-form">
                    <input type="hidden" name="csrf_token" value="{{ $csrf_token ?? '' }}">

                    <div class="form-field">
                        <label for="chk-name">Patient / Receiver Full Name <span class="req">*</span></label>
                        <input type="text" id="chk-name" name="customer_name" required placeholder="e.g., Priya Deshmukh" autocomplete="name">
                    </div>

                    <div class="form-grid-2">
                        <div class="form-field">
                            <label for="chk-email">Email for Receipt <span class="req">*</span></label>
                            <input type="email" id="chk-email" name="customer_email" required placeholder="you@example.com" autocomplete="email">
                        </div>
                        <div class="form-field">
                            <label for="chk-phone">Mobile / WhatsApp <span class="req">*</span></label>
                            <input type="tel" id="chk-phone" name="customer_phone" required placeholder="+91 98XXX XXXXX" autocomplete="tel">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-field">
                            <label for="chk-pincode">PIN Code <span class="req">*</span></label>
                            <input type="text" id="chk-pincode" name="pincode" required value="421503" placeholder="421503" autocomplete="postal-code">
                        </div>
                        <div class="form-field">
                            <label for="chk-payment">Payment Method</label>
                            <select id="chk-payment" name="payment_method">
                                <option value="UPI / QR Scan on Delivery">UPI / QR Scan on Delivery</option>
                                <option value="Cash on Delivery (COD)">Cash on Delivery (COD)</option>
                                <option value="Card on Delivery">Credit / Debit Card on Delivery</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="chk-address">Complete Delivery Address <span class="req">*</span></label>
                        <textarea id="chk-address" name="delivery_address" rows="2" required placeholder="Flat / Building, Landmark, Kulgaon / Ambarnath, Thane..."></textarea>
                    </div>

                    <div class="form-field" id="rx-notice-field">
                        <label for="chk-rx">Doctor / Prescription Reference (For Rx Medicines)</label>
                        <input type="text" id="chk-rx" name="rx_note" placeholder="Optional: Doctor Name / Rx Ref (Verified on delivery)">
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="place-order-btn">
                        <i class="fa-solid fa-lock" aria-hidden="true"></i>
                        <span id="place-order-btn-text">Place Order</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Order Confirmed Receipt View -->
        <div id="order-confirmed-view" class="order-confirmed-view" hidden>
            <div class="confirmed-badge-icon">
                <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
            </div>
            <span class="pill-badge pill-badge-sm">Order Logged • SERVI PHARM HEALTHCARE PVT. LTD.</span>
            <h3 id="confirmed-order-number">Order #SPH-2026-XXXXX Confirmed!</h3>
            <p id="confirmed-order-msg">Your healthcare order has been registered and assigned to our Ambarnath dispatch desk.</p>
            <div class="confirmed-summary-card" id="confirmed-summary-card"></div>
            <div class="confirmed-actions" style="display:flex;justify-content:center;gap:0.75rem;flex-wrap:wrap;">
                <a href="?page=orders" id="view-invoice-link" class="btn btn-primary">
                    <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
                    <span>View &amp; Print Official Invoice</span>
                </a>
                <button type="button" class="btn btn-outline" id="continue-shopping-btn">
                    <span>Continue Shopping</span>
                </button>
            </div>
        </div>
    </div>
</dialog>
