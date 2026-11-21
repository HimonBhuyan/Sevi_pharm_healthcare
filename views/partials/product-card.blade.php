@php
    $savings = (int) $item['mrp'] - (int) $item['price'];
@endphp
<article class="catalog-card ecom-product-card spotlight-card scroll-reveal"
         data-id="{{ $item['id'] }}"
         data-category="{{ $item['filter_key'] }}"
         data-price="{{ $item['price'] }}"
         data-discount="{{ $item['discount_pct'] }}"
         data-rating="{{ $item['rating'] }}"
         data-index="{{ $index }}"
         data-search="{{ strtolower($item['name'] . ' ' . $item['brand'] . ' ' . $item['sku'] . ' ' . $item['category'] . ' ' . $item['spec'] . ' ' . $item['description']) }}"
         style="--stagger: {{ ($index % 4) * 50 }}ms;">

    <!-- Product Studio Image Box -->
    <div class="product-media">
        <img src="{{ $item['image'] }}"
             alt="{{ $item['name'] }}"
             class="product-img"
             loading="lazy"
             width="480"
             height="380">

        <div class="product-badges-top">
            <span class="discount-ribbon">-{{ $item['discount_pct'] }}% OFF</span>
            @if(!empty($item['rx_required']))
                <span class="rx-pill"><i class="fa-solid fa-prescription" aria-hidden="true"></i> Rx</span>
            @else
                <span class="otc-pill"><i class="fa-solid fa-shield-check" aria-hidden="true"></i> {{ $item['badge'] }}</span>
            @endif
        </div>

        <button type="button" class="wishlist-btn" data-wishlist="{{ $item['id'] }}" aria-label="Save {{ $item['name'] }} to wishlist" title="Add to Wishlist">
            <i class="fa-regular fa-heart" aria-hidden="true"></i>
        </button>
    </div>

    <!-- Product Content -->
    <div class="product-body">
        <div class="product-meta-row">
            <span class="catalog-cat-label">{{ $item['category'] }}</span>
            <span class="product-sku">SKU: {{ $item['sku'] }}</span>
        </div>

        <h3 class="catalog-title">{{ $item['name'] }}</h3>
        <p class="product-pack-size"><i class="fa-solid fa-box-open" aria-hidden="true"></i> {{ $item['pack_size'] }}</p>
        <p class="catalog-spec">{{ $item['spec'] }}</p>
        <p class="catalog-desc">{{ $item['description'] }}</p>

        <div class="product-rating-bar">
            <span class="rating-pill">
                <i class="fa-solid fa-star" aria-hidden="true"></i> {{ $item['rating'] }}
            </span>
            <span class="review-count">({{ $item['reviews'] }} verified ratings)</span>
            <span class="stock-pill">
                <span class="pulse-dot pulse-dot-sm" aria-hidden="true"></span>
                {{ $item['stock'] }}
            </span>
        </div>

        <!-- E-Commerce Pricing Block -->
        <div class="product-price-block">
            <div class="price-main-group">
                <span class="price-current">₹{{ number_format((int) $item['price']) }}</span>
                <span class="price-mrp">MRP <del>₹{{ number_format((int) $item['mrp']) }}</del></span>
            </div>
            <span class="price-save-tag">Save ₹{{ number_format($savings) }}</span>
        </div>

        <!-- Quantity Stepper + Add to Cart + Buy Now -->
        <div class="product-actions-row">
            <div class="qty-stepper" aria-label="Select quantity">
                <button type="button" class="qty-btn" data-qty-dec aria-label="Decrease quantity">−</button>
                <input type="number" class="qty-input" value="1" min="1" max="20" aria-label="Quantity">
                <button type="button" class="qty-btn" data-qty-inc aria-label="Increase quantity">+</button>
            </div>

            <button type="button" class="btn btn-primary btn-sm add-to-cart-btn" data-add-cart="{{ $item['id'] }}">
                <i class="fa-solid fa-cart-plus" aria-hidden="true"></i>
                <span>Add to Cart</span>
            </button>

            <button type="button" class="btn btn-soft btn-sm buy-now-btn" data-buy-now="{{ $item['id'] }}" title="Instant Buy Now">
                <i class="fa-solid fa-bolt" aria-hidden="true"></i>
            </button>
        </div>
    </div>
</article>

