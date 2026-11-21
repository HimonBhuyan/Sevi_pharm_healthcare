<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="color-scheme" content="light dark">

    <title>@yield('title', 'SERVI PHARM HEALTHCARE PRIVATE LIMITED | Online Pharmacy, Medical & Orthopedic Store')</title>
    <meta name="description" content="{{ $company['description'] ?? 'SERVI PHARM HEALTHCARE PRIVATE LIMITED is an active private company incorporated June 29, 2026 in Maharashtra, specializing in the retail sale of pharmaceuticals, medical goods, orthopedic goods, and toilet articles.' }}">
    <meta name="keywords" content="SERVI PHARM HEALTHCARE PRIVATE LIMITED, ServiPharm E-Commerce, Online Pharmacy Ambarnath, U47721MR2026PTC477954, Orthopedic Goods Store, Medical Devices Maharashtra, Vishal Keshav Nalage, Varun Sanjay Singh">
    <meta name="author" content="SERVI PHARM HEALTHCARE PRIVATE LIMITED">
    <meta name="theme-color" content="#06121b">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="SERVI PHARM HEALTHCARE PRIVATE LIMITED | Official E-Commerce Store">
    <meta property="og:description" content="Shop genuine pharmaceuticals, medical devices, orthopedic braces, and clinical toilet articles. CIN: {{ $company['cin'] }} | Ambarnath, Thane, Maharashtra.">
    <meta property="og:site_name" content="ServiPharm Healthcare">

    <!-- Inline SVG Favicon -->
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Cdefs%3E%3ClinearGradient id='g' x1='0' y1='0' x2='1' y2='1'%3E%3Cstop offset='0%25' stop-color='%230d9488'/%3E%3Cstop offset='100%25' stop-color='%230284c7'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='64' height='64' rx='16' fill='url(%23g)'/%3E%3Cpath d='M27 14h10v13h13v10H37v13H27V37H14V27h13V14z' fill='%23ffffff'/%3E%3C/svg%3E">

    <!-- Google Fonts: Outfit, Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Outfit:wght@500;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Structured Corporate & Store Metadata (JSON-LD) -->
    @php
        $schemaData = [
            '@context' => 'https://schema.org',
            '@type' => 'Pharmacy',
            'name' => $company['name'] ?? 'SERVI PHARM HEALTHCARE PRIVATE LIMITED',
            'alternateName' => 'ServiPharm Healthcare Store',
            'description' => $company['description'] ?? '',
            'email' => $company['email'] ?? 'servipharmhealthcare@gmail.com',
            'foundingDate' => '2026-06-29',
            'identifier' => $company['cin'] ?? 'U47721MR2026PTC477954',
            'priceRange' => '₹185 - ₹2199',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => 'E/605, Manorama Nagar, Near Shivsundar Society, Kulgaon, Ambarnath',
                'addressLocality' => 'Ambarnath',
                'addressRegion' => 'Maharashtra',
                'postalCode' => '421503',
                'addressCountry' => 'IN',
            ],
        ];
    @endphp
    <script type="application/ld+json">{!! json_encode($schemaData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}</script>

    <!-- Catalog Payload for Instant Client-Side Cart & Filtering -->
    <script>
        window.SERVIPHARM_CATALOG = {!! json_encode($catalog ?? [], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!};
    </script>

    <!-- Early Theme Preference Detection -->
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('servipharm_theme');
                if (saved === 'light' || saved === 'dark') {
                    document.documentElement.setAttribute('data-theme', saved);
                }
            } catch (e) {}
        })();
    </script>

    <link rel="stylesheet" href="assets/css/style.css?v={{ @filemtime(__DIR__ . '/../../assets/css/style.css') ?: time() }}">
</head>
<body>
    <!-- Native Scroll-Driven Progress Indicator -->
    <div id="scroll-progress" class="scroll-progress" aria-hidden="true"></div>

    <!-- Interactive Molecular Canvas & Ambient Aurora -->
    <canvas id="bio-canvas" class="bio-canvas" aria-hidden="true"></canvas>
    <div class="ambient-aurora aurora-1" aria-hidden="true"></div>
    <div class="ambient-aurora aurora-2" aria-hidden="true"></div>
    <div class="ambient-aurora aurora-3" aria-hidden="true"></div>
    <div class="ambient-grid" aria-hidden="true"></div>

    @include('partials.navbar')

    <main id="main-content">
        @yield('content')
    </main>

    @include('partials.cart-modal')
    @include('partials.order-modal')
    @include('partials.footer')

    <!-- Floating Bottom Cart Bar (Appears when items are in cart) -->
    <div id="floating-cart-bar" class="floating-cart-bar" hidden>
        <div class="floating-cart-info">
            <span class="floating-cart-badge" id="floating-cart-count">0 Items</span>
            <div>
                <strong id="floating-cart-total">₹0</strong>
                <span class="floating-cart-sub" id="floating-cart-savings"> Genuine Batch-Verified Store</span>
            </div>
        </div>
        <button type="button" class="btn btn-primary btn-sm" data-open-cart>
            <span>View Cart &amp; Checkout</span>
            <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Toast Notification Stack -->
    <div id="toast-container" class="toast-container" aria-live="polite" aria-atomic="true"></div>

    <script src="assets/js/main.js?v={{ @filemtime(__DIR__ . '/../../assets/js/main.js') ?: time() }}" defer></script>
</body>
</html>
