@extends('layouts.app')

@section('title', 'Full Product Catalogue | SERVI PHARM HEALTHCARE PRIVATE LIMITED')

@section('content')
<section class="section section-alt catalog-page-section" id="catalog">
    <div class="container">
        <div class="checkout-top-bar">
            <a href="./" class="back-store-link">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Back to Home</span>
            </a>

            <div class="catalog-breadcrumb-actions">
                <a href="?page=checkout" class="btn btn-soft btn-sm">
                    <i class="fa-solid fa-cart-shopping" aria-hidden="true"></i>
                    <span>Go to Checkout</span>
                </a>
                <a href="?page=orders" class="btn btn-outline btn-sm">
                    <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                    <span>Track Orders</span>
                </a>
            </div>
        </div>

        <div class="storefront-header">
            <div class="section-head" style="margin-bottom: 0;">
                <span class="section-tag"><i class="fa-solid fa-bag-shopping" aria-hidden="true"></i> Complete Product Catalogue</span>
                <h1 class="section-title">All Healthcare, Medical, Orthopedic &amp; Hygiene Products</h1>
                <p class="section-subtitle">
                    Browse all {{ count($catalog ?? []) }} batch-verified items across Pharmaceuticals, Medical Goods, Orthopedic Goods, and Toilet Articles from {{ $company['name'] }}.
                </p>
            </div>

            <div class="storefront-promo-card">
                <div>
                    <span class="promo-kicker">STOREWIDE DISCOUNT</span>
                    <strong>Extra 10% OFF</strong>
                    <p>Apply code <code>SERVI10</code> at checkout</p>
                </div>
                <button type="button" class="btn btn-soft btn-sm" data-copy="SERVI10">
                    <i class="fa-regular fa-copy" aria-hidden="true"></i> Copy Code
                </button>
            </div>
        </div>

        <div class="catalog-controls">
            @php $cat = $initial_category ?? 'all'; @endphp
            <div class="filter-pills" role="tablist" aria-label="Filter store by category">
                <button type="button" class="filter-pill {{ $cat === 'all' ? 'is-active' : '' }}" data-filter="all" role="tab" aria-selected="{{ $cat === 'all' ? 'true' : 'false' }}">
                    <i class="fa-solid fa-border-all" aria-hidden="true"></i> All Products ({{ count($catalog ?? []) }})
                </button>
                <button type="button" class="filter-pill {{ $cat === 'pharmaceuticals' ? 'is-active' : '' }}" data-filter="pharmaceuticals" role="tab" aria-selected="{{ $cat === 'pharmaceuticals' ? 'true' : 'false' }}">
                    <i class="fa-solid fa-capsules" aria-hidden="true"></i> Pharmaceuticals
                </button>
                <button type="button" class="filter-pill {{ $cat === 'medical-goods' ? 'is-active' : '' }}" data-filter="medical-goods" role="tab" aria-selected="{{ $cat === 'medical-goods' ? 'true' : 'false' }}">
                    <i class="fa-solid fa-heart-pulse" aria-hidden="true"></i> Medical Goods
                </button>
                <button type="button" class="filter-pill {{ $cat === 'orthopedic-goods' ? 'is-active' : '' }}" data-filter="orthopedic-goods" role="tab" aria-selected="{{ $cat === 'orthopedic-goods' ? 'true' : 'false' }}">
                    <i class="fa-solid fa-bone" aria-hidden="true"></i> Orthopedic Goods
                </button>
                <button type="button" class="filter-pill {{ $cat === 'toilet-articles' ? 'is-active' : '' }}" data-filter="toilet-articles" role="tab" aria-selected="{{ $cat === 'toilet-articles' ? 'true' : 'false' }}">
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
            @foreach(($catalog ?? []) as $index => $item)
                @include('partials.product-card', ['item' => $item, 'index' => $index])
            @endforeach
        </div>

        <div id="catalog-empty" class="catalog-empty" hidden>
            <i class="fa-solid fa-kit-medical" aria-hidden="true"></i>
            <h3>No matching product found</h3>
            <p>Looking for a specific prescription medicine or custom orthopedic size? Submit a direct request and our pharmacy desk will arrange it.</p>
            <button type="button" class="btn btn-primary open-order-modal" data-open-order>Request Custom Medicine / Appliance</button>
        </div>
    </div>
</section>
@endsection

