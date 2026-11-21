@extends('layouts.app')

@section('title', 'SERVI PHARM HEALTHCARE PRIVATE LIMITED | Online Pharmacy, Medical & Orthopedic E-Commerce Store')

@section('content')
<!-- =========================================================
     FULL-SCREEN E-COMMERCE HERO SECTION
========================================================= -->
<section class="hero-section" id="top">
    <div class="container hero-grid">
        <div class="hero-copy-container">
            <div class="hero-eyebrow">
                <span class="pulse-dot" aria-hidden="true"></span>
                <span>Official E-Commerce Store • Incorporated {{ $company['incorporation_date'] }} • {{ $company['roc'] }}</span>
            </div>

            <h1 class="hero-title">
                Shop Genuine
                <span class="gradient-text">Pharmaceuticals, Medical Devices &amp; Orthopedic Care</span>
            </h1>

            <p class="hero-lead">
                Welcome to the official digital retail storefront of <strong>{{ $company['name'] }}</strong> (CIN: <code>{{ $company['cin'] }}</code>). Order batch-verified prescription &amp; OTC pharmaceuticals, clinical diagnostic monitors, rehabilitation orthopedic supports, and medicated toilet articles directly from our Ambarnath, Thane hub.
            </p>

            <!-- Quick Category Jump Pills -->
            <div class="hero-cat-strip">
                <a href="#catalog" class="hero-cat-pill" data-filter-jump="pharmaceuticals">
                    <i class="fa-solid fa-capsules" aria-hidden="true"></i>
                    <span>Pharmaceuticals</span>
                    <small>Up to 26% OFF</small>
                </a>
                <a href="#catalog" class="hero-cat-pill" data-filter-jump="medical-goods">
                    <i class="fa-solid fa-heart-pulse" aria-hidden="true"></i>
                    <span>Medical Goods</span>
                    <small>Up to 33% OFF</small>
                </a>
                <a href="#catalog" class="hero-cat-pill" data-filter-jump="orthopedic-goods">
                    <i class="fa-solid fa-bone" aria-hidden="true"></i>
                    <span>Orthopedic Goods</span>
                    <small>Up to 32% OFF</small>
                </a>
                <a href="#catalog" class="hero-cat-pill" data-filter-jump="toilet-articles">
                    <i class="fa-solid fa-pump-soap" aria-hidden="true"></i>
                    <span>Toilet Articles</span>
                    <small>Up to 22% OFF</small>
                </a>
            </div>

            <div class="hero-cta-row">
                <a href="?page=catalog" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
                    <span>Browse Full Catalogue</span>
                </a>
                <button type="button" class="btn btn-outline btn-lg open-order-modal" command="show-modal" commandfor="order-modal" data-open-order>
                    <i class="fa-solid fa-file-prescription" aria-hidden="true"></i>
                    <span>Upload Prescription / Bulk Inquiry</span>
                </button>
            </div>

            <div class="hero-trust-strip">
                <div class="trust-chip">
                    <i class="fa-solid fa-tag" aria-hidden="true"></i>
                    <span>Coupon: <code>SERVI10</code> (Extra 10% OFF)</span>
                    <button type="button" class="copy-inline-btn" data-copy="SERVI10" aria-label="Copy coupon SERVI10">
                        <i class="fa-regular fa-copy" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="trust-chip">
                    <i class="fa-solid fa-building-shield" aria-hidden="true"></i>
                    <span>CIN: <code>{{ $company['cin'] }}</code></span>
                    <button type="button" class="copy-inline-btn" data-copy="{{ $company['cin'] }}" aria-label="Copy CIN">
                        <i class="fa-regular fa-copy" aria-hidden="true"></i>
                    </button>
                </div>
                <div class="trust-chip">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    <span>Kulgaon, Ambarnath, Thane – {{ $company['pin_code'] }}</span>
                </div>
            </div>
        </div>

        <!-- Right Column: Featured E-Commerce Deal Showcase + 4 Category Visual Thumbnails -->
        <div class="hero-visual">
            <div class="hero-stage tilt-card" data-tilt>
                <div class="hero-card-glow" aria-hidden="true"></div>

                <div class="stage-topbar">
                    <span class="deal-flash-badge">
                        <i class="fa-solid fa-bolt" aria-hidden="true"></i> FEATURED BESTSELLER • 32% OFF
                    </span>
                    <span class="stage-status">
                        <span class="pulse-dot"></span> IN STOCK • READY TO SHIP
                    </span>
                </div>

                <!-- Featured Hero Product Spotlight -->
                <div class="hero-featured-product">
                    <div class="hero-featured-img-wrap">
                        <img src="assets/images/products/medical-bp-monitor.png" alt="PulseGuard Pro Digital Upper-Arm BP Monitor" class="hero-featured-img">
                    </div>
                    <div class="hero-featured-details">
                        <span class="stage-kicker">Medical Goods • SKU: SPH-MED-201</span>
                        <h2 class="stage-title">PulseGuard Pro Digital BP Monitor</h2>
                        <div class="hero-rating-row">
                            <span class="stars" aria-label="Rated 4.9 out of 5">★★★★★</span>
                            <span>4.9 (318 verified buyers)</span>
                        </div>
                        <div class="hero-price-row">
                            <strong class="hero-price-now">₹1,499</strong>
                            <span class="hero-price-mrp">MRP ₹2,199</span>
                            <span class="hero-price-save">Save ₹700</span>
                        </div>
                        <div class="hero-featured-actions">
                            <button type="button" class="btn btn-primary btn-sm" data-add-cart="sp-med-01" data-qty="1">
                                <i class="fa-solid fa-cart-plus" aria-hidden="true"></i>
                                <span>Add to Cart</span>
                            </button>
                            <button type="button" class="btn btn-soft btn-sm" data-buy-now="sp-med-01">
                                <span>Buy Now</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- 4 Visual Category Mini-Cards with Generated Images -->
                <div class="stage-pillars">
                    @foreach(($divisions ?? []) as $div)
                        <a href="#catalog" class="stage-pillar stage-pillar-link" data-filter-jump="{{ $div['id'] }}">
                            <img src="{{ $div['image'] }}" alt="{{ $div['title'] }}" class="pillar-thumb" loading="lazy">
                            <div>
                                <strong>{{ $div['title'] }}</strong>
                                <span>From {{ $div['starting_price'] }} • {{ $div['item_count'] }}</span>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="stage-footer-bar">
                    <div>
                        <span class="stage-meta-label">MCA Reg. / ROC Mumbai II</span>
                        <strong class="stage-meta-val">#{{ $company['registration_number'] }} (Active)</strong>
                    </div>
                    <div>
                        <span class="stage-meta-label">Same-Day Local Dispatch</span>
                        <strong class="stage-meta-val">Ambarnath (421503)</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Animated Key Metrics Bar -->
    <div class="container">
        <div class="metrics-bar">
            @foreach(($metrics ?? []) as $metric)
                <div class="metric-card spotlight-card">
                    <div class="metric-value"
                         data-counter="{{ $metric['value'] }}"
                         data-prefix="{{ $metric['prefix'] }}"
                         data-suffix="{{ $metric['suffix'] }}"
                         data-format="{{ $metric['format'] }}">
                        {{ $metric['display'] }}
                    </div>
                    <div class="metric-label">{{ $metric['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- =========================================================
     INFINITE ANIMATED MARQUEE STRIP
========================================================= -->
<div class="marquee-strip" aria-label="ServiPharm Healthcare Credentials and Offerings">
    <div class="marquee-track">
        @for($i = 0; $i < 2; $i++)
            <span class="marquee-item"><i class="fa-solid fa-capsules"></i> Ethical Pharmaceuticals</span>
            <span class="marquee-sep">•</span>
            <span class="marquee-item"><i class="fa-solid fa-stethoscope"></i> Clinical Medical Goods</span>
            <span class="marquee-sep">•</span>
            <span class="marquee-item"><i class="fa-solid fa-bone"></i> Orthopedic &amp; Rehab Appliances</span>
            <span class="marquee-sep">•</span>
            <span class="marquee-item"><i class="fa-solid fa-pump-soap"></i> Medicated Toilet Articles</span>
            <span class="marquee-sep">•</span>
            <span class="marquee-item"><i class="fa-solid fa-certificate"></i> CIN: {{ $company['cin'] }}</span>
            <span class="marquee-sep">•</span>
            <span class="marquee-item"><i class="fa-solid fa-landmark"></i> ROC Mumbai II • Active Status</span>
            <span class="marquee-sep">•</span>
            <span class="marquee-item"><i class="fa-solid fa-truck-fast"></i> Free Ambarnath Delivery Over ₹499</span>
            <span class="marquee-sep">•</span>
        @endfor
    </div>
</div>

<!-- =========================================================
     E-COMMERCE PRODUCT STOREFRONT (#catalog)
========================================================= -->
<section class="section section-alt" id="catalog">
    <div class="container">
        <div class="storefront-header">
            <div class="section-head" style="margin-bottom: 0;">
                <span class="section-tag"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i> Official Online Storefront</span>
                <h2 class="section-title">Shop Pharmaceuticals, Medical, Orthopedic &amp; Hygiene Products</h2>
                <p class="section-subtitle">
                    All products are sourced through authorized manufacturers with verified batch numbers and cold-chain compliance. Add items to your cart for instant checkout.
                </p>
            </div>

            <div class="storefront-promo-card">
                <div>
                    <span class="promo-kicker">INTRODUCTORY STORE OFFER</span>
                    <strong>Extra 10% OFF Storewide</strong>
                    <p>Apply code <code>SERVI10</code> in your cart</p>
                </div>
                <button type="button" class="btn btn-soft btn-sm" data-copy="SERVI10">
                    <i class="fa-regular fa-copy" aria-hidden="true"></i> Copy Code
                </button>
            </div>
        </div>

        <div class="catalog-controls">
            <div class="filter-pills" role="tablist" aria-label="Filter store by category">
                <button type="button" class="filter-pill is-active" data-filter="all" role="tab" aria-selected="true">
                    <i class="fa-solid fa-border-all" aria-hidden="true"></i> All Products ({{ count($catalog ?? []) }})
                </button>
                <button type="button" class="filter-pill" data-filter="pharmaceuticals" role="tab" aria-selected="false">
                    <i class="fa-solid fa-capsules" aria-hidden="true"></i> Pharmaceuticals
                </button>
                <button type="button" class="filter-pill" data-filter="medical-goods" role="tab" aria-selected="false">
                    <i class="fa-solid fa-heart-pulse" aria-hidden="true"></i> Medical Goods
                </button>
                <button type="button" class="filter-pill" data-filter="orthopedic-goods" role="tab" aria-selected="false">
                    <i class="fa-solid fa-bone" aria-hidden="true"></i> Orthopedic Goods
                </button>
                <button type="button" class="filter-pill" data-filter="toilet-articles" role="tab" aria-selected="false">
                    <i class="fa-solid fa-pump-soap" aria-hidden="true"></i> Toilet Articles
                </button>
            </div>

            <div class="catalog-right-controls">
                <div class="catalog-sort-wrap">
                    <label for="catalog-sort" class="sr-only">Sort products</label>
                    <select id="catalog-sort" class="catalog-sort">
                        <option value="featured">Sort: Featured Bestsellers</option>
                        <option value="price-asc">Price: Low to High</option>
                        <option value="price-desc">Price: High to Low</option>
                        <option value="discount-desc">Biggest Discount %</option>
                        <option value="rating-desc">Highest Customer Rating</option>
                    </select>
                </div>

                <div class="catalog-search-wrap">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <input type="search" id="catalog-search" class="catalog-search" placeholder="Filter by medicine, brace, device, SKU..." aria-label="Search healthcare products">
                </div>
            </div>
        </div>

        <div class="catalog-grid" id="catalog-grid">
            @foreach(array_slice($catalog ?? [], 0, 8) as $index => $item)
                @include('partials.product-card', ['item' => $item, 'index' => $index])
            @endforeach
        </div>

        <!-- Horizontal View-All Catalogue Bar -->
        <div class="catalog-view-all-bar">
            <div class="catalog-view-all-info">
                <span class="pill-badge pill-badge-sm">
                    <i class="fa-solid fa-boxes-stacked" aria-hidden="true"></i>
                    Showing 8 of {{ count($catalog ?? []) }} Storefront Products
                </span>
                <strong>Want to explore our full range of Pharmaceuticals, Medical, Orthopedic &amp; Hygiene products?</strong>
            </div>
            <a href="?page=catalog" class="btn btn-primary btn-lg catalog-view-all-btn">
                <span>Click Here to View Full Catalogue ({{ count($catalog ?? []) }} Products)</span>
                <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div id="catalog-empty" class="catalog-empty" hidden>
            <i class="fa-solid fa-kit-medical" aria-hidden="true"></i>
            <h3>No matching product found</h3>
            <p>Looking for a specific prescription medicine or custom orthopedic size? Submit a direct request and our pharmacy desk will arrange it.</p>
            <button type="button" class="btn btn-primary open-order-modal" data-open-order>Request Custom Medicine / Appliance</button>
        </div>
    </div>
</section>

<!-- =========================================================
     4 CORE HEALTHCARE DIVISIONS (BENTO GRID WITH VISUALS)
========================================================= -->
<section class="section" id="divisions">
    <div class="container">
        <div class="section-head">
            <span class="section-tag"><i class="fa-solid fa-layer-group" aria-hidden="true"></i> Primary Business Activity (NIC 47721)</span>
            <h2 class="section-title">Four Specialized Healthcare Retail Verticals</h2>
            <p class="section-subtitle">
                Incorporated in June 2026 in Maharashtra, SERVI PHARM HEALTHCARE PRIVATE LIMITED operates across four core retail pillars: pharmaceuticals, medical goods, orthopedic goods, and toilet articles.
            </p>
        </div>

        <div class="divisions-grid">
            @foreach(($divisions ?? []) as $index => $div)
                <article class="bento-card division-card accent-{{ $div['accent'] }} spotlight-card scroll-reveal" style="--stagger: {{ $index * 80 }}ms;">
                    <div class="division-banner-img">
                        <img src="{{ $div['image'] }}" alt="{{ $div['title'] }}" loading="lazy">
                        <span class="division-price-badge">From {{ $div['starting_price'] }}</span>
                    </div>

                    <div class="division-head">
                        <div class="division-icon">
                            <i class="{{ $div['icon'] }}" aria-hidden="true"></i>
                        </div>
                        <span class="division-index">{{ $div['item_count'] }}</span>
                    </div>
                    <div class="division-meta">{{ $div['subtitle'] }}</div>
                    <h3 class="division-title">{{ $div['title'] }}</h3>
                    <p class="division-desc">{{ $div['description'] }}</p>

                    <ul class="division-list">
                        @foreach($div['highlights'] as $item)
                            <li><i class="fa-solid fa-check" aria-hidden="true"></i><span>{{ $item }}</span></li>
                        @endforeach
                    </ul>

                    <div class="division-footer">
                        <a href="#catalog" class="division-link" data-filter-jump="{{ $div['id'] }}">
                            <span>Shop {{ $div['title'] }}</span>
                            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                        </a>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- =========================================================
     CORPORATE REGISTRATION DETAILS (MCA DOSSIER)
========================================================= -->
<section class="section section-alt" id="corporate">
    <div class="container">
        <div class="section-head">
            <span class="section-tag"><i class="fa-solid fa-landmark" aria-hidden="true"></i> Ministry of Corporate Affairs</span>
            <h2 class="section-title">Verified Corporate Registration Dossier</h2>
            <p class="section-subtitle">
                Official incorporation records indexed with the Ministry of Corporate Affairs (MCA), ROC Mumbai II, Tracxn, and FalconeBiz.
            </p>
        </div>

        <div class="dossier-layout">
            <div class="bento-card dossier-primary spotlight-card scroll-reveal">
                <div class="dossier-header">
                    <div>
                        <span class="dossier-kicker">Legal Entity Name</span>
                        <h3 class="dossier-entity">{{ $company['name'] }}</h3>
                    </div>
                    <span class="status-badge-active">
                        <span class="pulse-dot" aria-hidden="true"></span>
                        {{ $company['status'] }}
                    </span>
                </div>

                <div class="cin-showcase">
                    <div>
                        <span class="cin-label">Corporate Identification Number (CIN)</span>
                        <div class="cin-value" id="cin-text">{{ $company['cin'] }}</div>
                    </div>
                    <button type="button" class="btn btn-soft btn-sm" data-copy="{{ $company['cin'] }}">
                        <i class="fa-regular fa-copy" aria-hidden="true"></i>
                        <span>Copy CIN</span>
                    </button>
                </div>

                <div class="dossier-grid">
                    <div class="dossier-item">
                        <span class="d-label">Registration Number</span>
                        <strong class="d-value">{{ $company['registration_number'] }}</strong>
                        <span class="d-sub">Registered with {{ $company['roc'] }}</span>
                    </div>
                    <div class="dossier-item">
                        <span class="d-label">Date of Incorporation</span>
                        <strong class="d-value">{{ $company['incorporation_date'] }}</strong>
                        <span class="d-sub">Maharashtra, India</span>
                    </div>
                    <div class="dossier-item">
                        <span class="d-label">Company Classification</span>
                        <strong class="d-value">Private Company</strong>
                        <span class="d-sub">Non-government entity</span>
                    </div>
                    <div class="dossier-item">
                        <span class="d-label">Primary Business Activity</span>
                        <strong class="d-value">Retail Healthcare</strong>
                        <span class="d-sub">Pharmaceuticals, medical, orthopedic &amp; toilet articles</span>
                    </div>
                </div>
            </div>

            <div class="bento-card capital-card spotlight-card scroll-reveal">
                <span class="dossier-kicker">Share Capital Structure</span>
                <h3 class="capital-heading">Capitalization Overview</h3>

                <div class="capital-stat">
                    <div class="capital-row">
                        <span>Authorized Share Capital</span>
                        <strong>{{ $company['authorized_capital'] }}</strong>
                    </div>
                    <div class="capital-bar">
                        <div class="capital-fill capital-fill-full"></div>
                    </div>
                </div>

                <div class="capital-stat">
                    <div class="capital-row">
                        <span>Paid-Up Share Capital</span>
                        <strong>{{ $company['paid_up_capital'] }}</strong>
                    </div>
                    <div class="capital-bar">
                        <div class="capital-fill capital-fill-paid"></div>
                    </div>
                    <span class="capital-ratio">20% subscribed &amp; paid-up upon incorporation</span>
                </div>

                <div class="registry-sources">
                    <span class="d-label">Public Corporate Registry Indexes</span>
                    <div class="source-pills">
                        @foreach(($company['registry_sources'] ?? []) as $src)
                            <span class="source-pill"><i class="fa-solid fa-check-double" aria-hidden="true"></i> {{ $src }}</span>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================
     DIRECTORS & LEADERSHIP
========================================================= -->
<section class="section" id="leadership">
    <div class="container">
        <div class="section-head">
            <span class="section-tag"><i class="fa-solid fa-users-gear" aria-hidden="true"></i> Governance &amp; Stewardship</span>
            <h2 class="section-title">Directors &amp; Leadership</h2>
            <p class="section-subtitle">
                Guided from incorporation by our founding Board of Directors committed to ethical pharmaceutical distribution and responsive local care.
            </p>
        </div>

        <div class="directors-grid">
            @foreach(($directors ?? []) as $index => $director)
                <article class="bento-card director-card spotlight-card scroll-reveal" style="--stagger: {{ $index * 120 }}ms;">
                    <div class="director-top">
                        <div class="director-avatar" aria-hidden="true">
                            <span>{{ $director['initials'] }}</span>
                        </div>
                        <div class="director-meta">
                            <span class="director-role-badge">{{ $director['role'] }}</span>
                            <h3 class="director-name">{{ $director['name'] }}</h3>
                            <p class="director-appointed">
                                <i class="fa-regular fa-calendar-check" aria-hidden="true"></i>
                                Appointed {{ $director['appointed'] }}
                            </p>
                        </div>
                    </div>
                    <div class="director-focus">
                        <span>Leadership Portfolio:</span>
                        <strong>{{ $director['focus'] }}</strong>
                    </div>
                    <p class="director-bio">{{ $director['bio'] }}</p>
                </article>
            @endforeach
        </div>
    </div>
</section>

<!-- =========================================================
     QUALITY & DISPENSING WORKFLOW
========================================================= -->
<section class="section section-alt" id="workflow">
    <div class="container">
        <div class="section-head">
            <span class="section-tag"><i class="fa-solid fa-shield-virus" aria-hidden="true"></i> Clinical Standards</span>
            <h2 class="section-title">How We Safeguard Quality &amp; Authenticity</h2>
            <p class="section-subtitle">
                Every pharmaceutical batch, diagnostic instrument, and orthopedic brace follows a four-stage verification protocol.
            </p>
        </div>

        <div class="workflow-grid">
            @foreach(($workflow ?? []) as $step)
                <div class="bento-card workflow-card spotlight-card scroll-reveal">
                    <div class="workflow-top">
                        <span class="workflow-num">{{ $step['step'] }}</span>
                        <span class="workflow-icon"><i class="{{ $step['icon'] }}" aria-hidden="true"></i></span>
                    </div>
                    <h3 class="workflow-title">{{ $step['title'] }}</h3>
                    <p class="workflow-desc">{{ $step['detail'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- =========================================================
     REGISTERED OFFICE, MAP & DIRECT INQUIRY
========================================================= -->
<section class="section" id="location">
    <div class="container">
        <div class="section-head">
            <span class="section-tag"><i class="fa-solid fa-map-location-dot" aria-hidden="true"></i> Location &amp; Contact</span>
            <h2 class="section-title">Registered Office &amp; Direct Inquiry Desk</h2>
            <p class="section-subtitle">
                Incorporated in June 2026 in Kulgaon, Ambarnath (Thane, Maharashtra). Connect directly via official email or our instant digital inquiry portal.
            </p>
        </div>

        <div class="contact-layout" id="contact">
            <div class="contact-info-stack">
                <div class="bento-card contact-card spotlight-card scroll-reveal">
                    <div class="contact-item">
                        <div class="contact-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></div>
                        <div>
                            <span class="contact-label">Registered Office Address</span>
                            <p class="contact-value">{{ $company['address'] }}</p>
                            <button type="button" class="copy-text-btn" data-copy="{{ $company['address'] }}">
                                <i class="fa-regular fa-copy" aria-hidden="true"></i> Copy Address
                            </button>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon"><i class="fa-solid fa-envelope-open-text" aria-hidden="true"></i></div>
                        <div>
                            <span class="contact-label">Official Corporate Email</span>
                            <p class="contact-value">
                                <a href="mailto:{{ $company['email'] }}" class="email-link">{{ $company['email'] }}</a>
                            </p>
                            <span class="contact-hint">Listed on public corporate directories including Delve</span>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon"><i class="fa-solid fa-headset" aria-hidden="true"></i></div>
                        <div>
                            <span class="contact-label">Telephone &amp; Catalog Notice</span>
                            <p class="contact-value-muted">{{ $company['phone_status'] }}</p>
                        </div>
                    </div>
                </div>

                <div class="bento-card map-card scroll-reveal">
                    <div class="map-header">
                        <span><i class="fa-solid fa-map-pin" aria-hidden="true"></i> Kulgaon, Ambarnath, Thane – 421503</span>
                        <a href="https://maps.google.com/?q=Manorama+Nagar,+Kulgaon,+Ambarnath,+Thane,+Maharashtra+421503" target="_blank" rel="noopener noreferrer">Open Maps <i class="fa-solid fa-arrow-up-right-from-square" aria-hidden="true"></i></a>
                    </div>
                    <iframe
                        title="Registered Office Location of SERVI PHARM HEALTHCARE PRIVATE LIMITED in Ambarnath, Thane"
                        class="map-frame"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        src="https://maps.google.com/maps?q=Manorama%20Nagar%20Kulgaon%20Ambarnath%20Thane%20Maharashtra%20421503&t=&z=15&ie=UTF8&iwloc=&output=embed">
                    </iframe>
                </div>
            </div>

            <!-- Interactive AJAX Inquiry Form -->
            <div class="bento-card form-card spotlight-card scroll-reveal">
                <div class="form-card-head">
                    <h3>Send a Prescription / Bulk Inquiry</h3>
                    <p>For custom medicine requests, orthopedic sizing advice, or clinic/distributor orders.</p>
                </div>

                <form id="contact-inquiry-form" class="sp-form" action="?action=inquiry" method="POST">
                    <input type="hidden" name="csrf_token" value="{{ $csrf_token ?? '' }}">

                    <div class="form-grid-2">
                        <div class="form-field">
                            <label for="contact-name">Your Name <span class="req">*</span></label>
                            <input type="text" id="contact-name" name="name" required placeholder="Full name" autocomplete="name">
                        </div>
                        <div class="form-field">
                            <label for="contact-email">Email Address <span class="req">*</span></label>
                            <input type="email" id="contact-email" name="email" required placeholder="you@example.com" autocomplete="email">
                        </div>
                    </div>

                    <div class="form-grid-2">
                        <div class="form-field">
                            <label for="contact-phone">Phone / WhatsApp</label>
                            <input type="tel" id="contact-phone" name="phone" placeholder="+91 98XXX XXXXX" autocomplete="tel">
                        </div>
                        <div class="form-field">
                            <label for="contact-category">Inquiry Type</label>
                            <select id="contact-category" name="category">
                                <option value="Pharmaceuticals">Retail Pharmaceuticals</option>
                                <option value="Medical Goods">Medical &amp; Diagnostic Goods</option>
                                <option value="Orthopedic Goods">Orthopedic &amp; Rehab Goods</option>
                                <option value="Toilet Articles">Toilet Articles &amp; Hygiene</option>
                                <option value="B2B / Vendor Partnership">Distributor / Corporate Inquiry</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-field">
                        <label for="contact-product">Product / Subject</label>
                        <input type="text" id="contact-product" name="product" placeholder="e.g., BP Monitor, Lumbar Support, Prescription List">
                    </div>

                    <div class="form-field">
                        <label for="contact-message">Message <span class="req">*</span></label>
                        <textarea id="contact-message" name="message" rows="4" required placeholder="Tell us what medicines, medical devices, or orthopedic products you require..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg btn-block" id="contact-submit-btn">
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                        <span>Dispatch Inquiry to ServiPharm</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
