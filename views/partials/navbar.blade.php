<!-- Top E-Commerce Announcement Bar -->
<div class="announcement-bar">
    <div class="container announcement-inner">
        <div class="announcement-left">
            <span class="anno-pill"><i class="fa-solid fa-truck-fast" aria-hidden="true"></i> Free Express Delivery above ₹499 in Ambarnath &amp; Thane (421503)</span>
            <span class="anno-sep">|</span>
            <span class="anno-promo">Use Coupon <code>SERVI10</code> for Extra 10% OFF</span>
        </div>
        <div class="announcement-right">
            <span><i class="fa-solid fa-shield-check" aria-hidden="true"></i> MCA Active • CIN: <strong>{{ $company['cin'] ?? 'U47721MR2026PTC477954' }}</strong></span>
            <span class="anno-sep">|</span>
            <span>ROC Mumbai II (#{{ $company['registration_number'] ?? '477954' }})</span>
        </div>
    </div>
</div>

<header class="site-header" id="site-header">
    <div class="container header-inner">
        <a href="./" class="brand-lockup" id="site-logo-link" aria-label="SERVI PHARM HEALTHCARE PRIVATE LIMITED Home">
            <span class="brand-emblem" aria-hidden="true">
                <svg viewBox="0 0 40 40" width="40" height="40" fill="none">
                    <defs>
                        <linearGradient id="spLogoGrad" x1="0" y1="0" x2="40" y2="40" gradientUnits="userSpaceOnUse">
                            <stop offset="0%" stop-color="#0d9488"/>
                            <stop offset="100%" stop-color="#0ea5e9"/>
                        </linearGradient>
                    </defs>
                    <rect x="2" y="2" width="36" height="36" rx="11" fill="url(#spLogoGrad)"/>
                    <path d="M17 10h6v7h7v6h-7v7h-6v-7h-7v-6h7v-7z" fill="#ffffff"/>
                    <circle cx="31" cy="9" r="3.2" fill="#34d399" stroke="#ffffff" stroke-width="1.5"/>
                </svg>
            </span>
            <span class="brand-text">
                <span class="brand-name">ServiPharm <span class="brand-accent">Healthcare</span></span>
                <span class="brand-sub">E-COMMERCE PHARMACY &amp; ORTHO STORE</span>
            </span>
        </a>

        <!-- Header Quick Search Bar -->
        <div class="header-search-box">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input type="search" id="header-search-input" placeholder="Search medicines, BP monitors, lumbar belts, antiseptics..." aria-label="Search store products">
        </div>

        <nav class="primary-nav" id="primary-nav" aria-label="Primary navigation">
            <a href="?page=catalog" class="nav-link {{ ($active_page ?? '') === 'pages.catalog' ? 'is-active' : '' }}">Catalogue</a>
            <a href="./#divisions" class="nav-link">4 Categories</a>
            <a href="?page=checkout" class="nav-link {{ ($active_page ?? '') === 'pages.checkout' ? 'is-active' : '' }}">Checkout</a>
            <a href="?page=orders" class="nav-link {{ ($active_page ?? '') === 'pages.orders' ? 'is-active' : '' }}">Track Orders</a>
            <a href="./#corporate" class="nav-link">MCA Registry</a>
            <a href="./#location" class="nav-link">Ambarnath HQ</a>
        </nav>

        <div class="header-actions">
            <button type="button" class="icon-btn fullscreen-toggle" id="fullscreen-toggle" aria-label="Toggle full screen" title="Toggle Full Screen">
                <i class="fa-solid fa-expand" id="fullscreen-icon" aria-hidden="true"></i>
            </button>

            <button type="button" class="icon-btn theme-toggle" id="theme-toggle" aria-label="Toggle color theme" title="Switch light / dark appearance">
                <i class="fa-solid fa-sun icon-sun" aria-hidden="true"></i>
                <i class="fa-solid fa-moon icon-moon" aria-hidden="true"></i>
            </button>

            <button type="button" class="btn btn-outline btn-sm open-order-modal" command="show-modal" commandfor="order-modal" data-open-order>
                <i class="fa-solid fa-file-prescription" aria-hidden="true"></i>
                <span>Rx Upload / Bulk</span>
            </button>

            <!-- E-Commerce Shopping Cart Trigger -->
            <button type="button" class="btn btn-primary btn-sm cart-trigger-btn" id="open-cart-btn" command="show-modal" commandfor="cart-modal" data-open-cart aria-label="Open shopping cart">
                <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                <span class="cart-btn-label">Cart</span>
                <span class="cart-count-pill" id="cart-count-badge">0</span>
                <span class="cart-subtotal-text" id="cart-header-total">₹0</span>
            </button>

            <button type="button" class="icon-btn mobile-menu-btn" id="mobile-menu-btn" aria-label="Toggle navigation menu" aria-expanded="false" aria-controls="mobile-drawer">
                <i class="fa-solid fa-bars" aria-hidden="true"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div class="mobile-drawer" id="mobile-drawer" hidden>
        <div class="container mobile-drawer-inner">
            <div class="mobile-search-box">
                <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                <input type="search" id="mobile-search-input" placeholder="Search medicines, devices, braces..." aria-label="Search store products">
            </div>
            <a href="?page=catalog" class="mobile-nav-link">Full Product Catalogue</a>
            <a href="./#divisions" class="mobile-nav-link">4 Retail Categories</a>
            <a href="?page=checkout" class="mobile-nav-link">Cart &amp; Checkout</a>
            <a href="?page=orders" class="mobile-nav-link">Track Orders &amp; Invoices</a>
            <a href="./#corporate" class="mobile-nav-link">MCA Corporate Registry</a>
            <a href="./#leadership" class="mobile-nav-link">Directors &amp; Leadership</a>
            <a href="./#location" class="mobile-nav-link">Registered Office Map</a>
            <div class="mobile-drawer-actions">
                <button type="button" class="btn btn-outline" command="show-modal" commandfor="order-modal" data-open-order>
                    <i class="fa-solid fa-file-prescription" aria-hidden="true"></i>
                    <span>Rx Upload / Bulk Inquiry</span>
                </button>
                <button type="button" class="btn btn-primary" data-open-cart>
                    <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                    <span>Open Shopping Cart</span>
                </button>
            </div>
        </div>
    </div>
</header>
