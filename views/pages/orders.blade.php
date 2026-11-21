@extends('layouts.app')

@section('title', 'Order Confirmation, Tracking & Tax Invoice | SERVI PHARM HEALTHCARE PRIVATE LIMITED')

@section('content')
<section class="section orders-page-section">
    <div class="container">
        <div class="checkout-top-bar no-print">
            <a href="./#catalog" class="back-store-link">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                <span>Continue Shopping Storefront</span>
            </a>

            <form action="" method="GET" class="order-lookup-form">
                <input type="hidden" name="page" value="orders">
                <input type="search" name="order" value="{{ $lookup_query ?? '' }}" placeholder="Search Order # (e.g. SPH-2026-...) or Email" aria-label="Search order by number or email">
                <button type="submit" class="btn btn-soft btn-sm">
                    <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                    <span>Find Order</span>
                </button>
            </form>
        </div>

        @if(!empty($selected_order))
            @php $ord = $selected_order; @endphp
            <!-- Printable Official Retail Pharmacy Invoice Card -->
            <div class="bento-card invoice-card" id="printable-invoice">
                <div class="invoice-top-banner">
                    <div>
                        <span class="status-badge-active">
                            <span class="pulse-dot" aria-hidden="true"></span>
                            {{ $ord['status'] ?? 'Confirmed' }}
                        </span>
                        <h1 class="invoice-heading">Order #{{ $ord['order_number'] }}</h1>
                        <p class="invoice-date">Placed on {{ $ord['created_at'] }} • Est. Delivery: <strong>{{ $ord['estimated_delivery'] ?? 'Same-Day Local Dispatch' }}</strong></p>
                    </div>
                    <div class="invoice-actions no-print">
                        <button type="button" class="btn btn-primary btn-sm" onclick="window.print()">
                            <i class="fa-solid fa-print" aria-hidden="true"></i>
                            <span>Print / Save PDF Invoice</span>
                        </button>
                        <a href="./#catalog" class="btn btn-outline btn-sm">
                            <i class="fa-solid fa-bag-shopping" aria-hidden="true"></i>
                            <span>Shop More</span>
                        </a>
                    </div>
                </div>

                <!-- Order Tracking Timeline -->
                <div class="tracking-timeline no-print">
                    <div class="track-node is-done">
                        <span class="track-dot"><i class="fa-solid fa-check"></i></span>
                        <strong>Order Confirmed</strong>
                        <small>{{ $ord['created_at'] }}</small>
                    </div>
                    <div class="track-node is-done">
                        <span class="track-dot"><i class="fa-solid fa-microscope"></i></span>
                        <strong>Batch &amp; Expiry Verified</strong>
                        <small>Pharmacist Desk</small>
                    </div>
                    <div class="track-node is-current">
                        <span class="track-dot"><i class="fa-solid fa-box-open"></i></span>
                        <strong>Packed at Ambarnath Hub</strong>
                        <small>Kulgaon – 421503</small>
                    </div>
                    <div class="track-node">
                        <span class="track-dot"><i class="fa-solid fa-truck-fast"></i></span>
                        <strong>Out for Delivery</strong>
                        <small>{{ $ord['customer']['pincode'] ?? '421503' }}</small>
                    </div>
                </div>

                <!-- Seller & Buyer Meta Columns -->
                <div class="invoice-parties-grid">
                    <div class="party-box">
                        <span class="d-label">Sold &amp; Dispatched By (Registered Entity)</span>
                        <strong>{{ $company['name'] }}</strong>
                        <p>{{ $company['address'] }}</p>
                        <p class="mono-line">CIN: {{ $company['cin'] }} | Reg No: {{ $company['registration_number'] }} ({{ $company['roc'] }})</p>
                        <p class="mono-line">Email: {{ $company['email'] }}</p>
                    </div>
                    <div class="party-box">
                        <span class="d-label">Billed &amp; Shipped To Customer</span>
                        <strong>{{ $ord['customer']['name'] }}</strong>
                        <p>{{ $ord['customer']['address'] }}, {{ $ord['customer']['city'] ?? 'Ambarnath, Thane' }} – {{ $ord['customer']['pincode'] }}</p>
                        <p class="mono-line">Phone: {{ $ord['customer']['phone'] }} | Email: {{ $ord['customer']['email'] }}</p>
                        <p class="mono-line">Payment Mode: <strong>{{ $ord['payment_method'] }}</strong></p>
                        @if(!empty($ord['rx_note']))
                            <p class="mono-line">Rx Reference: {{ $ord['rx_note'] }}</p>
                        @endif
                    </div>
                </div>

                <!-- Itemized Invoice Table -->
                <div class="invoice-table-wrap">
                    <table class="invoice-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Item &amp; Pack Description</th>
                                <th>Category / SKU</th>
                                <th>Unit MRP</th>
                                <th>Selling Price</th>
                                <th>Qty</th>
                                <th>Line Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(($ord['items'] ?? []) as $idx => $line)
                                <tr>
                                    <td>{{ $idx + 1 }}</td>
                                    <td>
                                        <strong>{{ $line['name'] }}</strong>
                                        @if(!empty($line['pack_size']))
                                            <div class="muted-small">{{ $line['pack_size'] }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="mono-chip">{{ $line['sku'] ?? 'SPH' }}</span>
                                        <div class="muted-small">{{ $line['category'] }}</div>
                                    </td>
                                    <td><del>₹{{ number_format((int) $line['unit_mrp']) }}</del></td>
                                    <td>₹{{ number_format((int) $line['unit_price']) }}</td>
                                    <td><strong>{{ $line['qty'] }}</strong></td>
                                    <td><strong>₹{{ number_format((int) $line['line_total']) }}</strong></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Invoice Totals Footer -->
                <div class="invoice-bottom-grid">
                    <div class="invoice-declaration">
                        <strong>Declaration &amp; Quality Guarantee:</strong>
                        <p>All pharmaceutical formulations, diagnostic medical goods, orthopedic supports, and toilet articles listed above are genuine batch-verified products supplied by SERVI PHARM HEALTHCARE PRIVATE LIMITED.</p>
                    </div>
                    <div class="cart-summary-box">
                        <div class="summary-row">
                            <span>Total MRP</span>
                            <span>₹{{ number_format((int) ($ord['pricing']['mrp_total'] ?? 0)) }}</span>
                        </div>
                        <div class="summary-row is-discount">
                            <span>MRP Discount</span>
                            <span>-₹{{ number_format((int) (($ord['pricing']['mrp_total'] ?? 0) - ($ord['pricing']['subtotal'] ?? 0))) }}</span>
                        </div>
                        @if(!empty($ord['pricing']['coupon_discount']))
                            <div class="summary-row is-discount">
                                <span>Coupon ({{ $ord['coupon_code'] ?? 'PROMO' }})</span>
                                <span>-₹{{ number_format((int) $ord['pricing']['coupon_discount']) }}</span>
                            </div>
                        @endif
                        <div class="summary-row">
                            <span>Delivery Fee</span>
                            <span>{{ empty($ord['pricing']['delivery_fee']) ? 'FREE' : '₹' . number_format((int) $ord['pricing']['delivery_fee']) }}</span>
                        </div>
                        <div class="summary-row is-total">
                            <span>Net Payable Total</span>
                            <strong>₹{{ number_format((int) ($ord['pricing']['grand_total'] ?? 0)) }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="bento-card cart-empty-state">
                <div class="cart-empty-icon">
                    <i class="fa-solid fa-receipt" aria-hidden="true"></i>
                </div>
                <h2>No Orders Found</h2>
                <p>You haven't placed an order yet, or no order matched your search query.</p>
                <div style="margin-top: 1.25rem; display: flex; justify-content: center; gap: 0.75rem;">
                    <a href="./#catalog" class="btn btn-primary">Shop Healthcare Products</a>
                    <a href="?page=checkout" class="btn btn-outline">Go to Checkout</a>
                </div>
            </div>
        @endif

        @if(!empty($orders) && count($orders) > 1)
            <div class="recent-orders-block no-print" style="margin-top: 2rem;">
                <h3 class="footer-heading">Recent Store Orders</h3>
                <div class="recent-orders-grid">
                    @foreach($orders as $recent)
                        <a href="?page=orders&order={{ urlencode($recent['order_number']) }}" class="bento-card recent-order-card">
                            <div style="display:flex;justify-content:space-between;align-items:center;">
                                <strong>#{{ $recent['order_number'] }}</strong>
                                <span class="mono-chip">₹{{ number_format((int) ($recent['pricing']['grand_total'] ?? 0)) }}</span>
                            </div>
                            <div class="muted-small" style="margin-top:0.35rem;">
                                {{ $recent['customer']['name'] ?? 'Customer' }} • {{ count($recent['items'] ?? []) }} item(s) • {{ $recent['created_at'] }}
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</section>
@endsection

