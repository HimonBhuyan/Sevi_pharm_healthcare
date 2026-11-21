<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div class="footer-brand-col">
                <div class="brand-lockup footer-lockup">
                    <span class="brand-emblem" aria-hidden="true">
                        <svg viewBox="0 0 40 40" width="38" height="38" fill="none">
                            <rect x="2" y="2" width="36" height="36" rx="11" fill="#0d9488"/>
                            <path d="M17 10h6v7h7v6h-7v7h-6v-7h-7v-6h7v-7z" fill="#ffffff"/>
                        </svg>
                    </span>
                    <span class="brand-text">
                        <span class="brand-name">{{ $company['name'] ?? 'SERVI PHARM HEALTHCARE PRIVATE LIMITED' }}</span>
                        <span class="brand-sub">Incorporated June 29, 2026 • ROC Mumbai II</span>
                    </span>
                </div>
                <p class="footer-desc">
                    An active, non-government private company registered in Maharashtra, India, dedicated to the retail sale of pharmaceuticals, medical goods, orthopedic goods, and toilet articles.
                </p>
                <div class="footer-badges">
                    <span class="mono-chip"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Status: {{ $company['status'] ?? 'Active' }}</span>
                    <span class="mono-chip">CIN: {{ $company['cin'] ?? 'U47721MR2026PTC477954' }}</span>
                    <span class="mono-chip">Reg No: {{ $company['registration_number'] ?? '477954' }}</span>
                </div>
            </div>

            <div class="footer-links-col">
                <h3 class="footer-heading">Navigation</h3>
                <ul class="footer-list">
                    <li><a href="#divisions">4 Healthcare Divisions</a></li>
                    <li><a href="#catalog">Product Catalog</a></li>
                    <li><a href="#corporate">MCA Corporate Dossier</a></li>
                    <li><a href="#leadership">Board of Directors</a></li>
                    <li><a href="#location">Registered Office</a></li>
                    <li><a href="#contact">Contact &amp; Inquiries</a></li>
                </ul>
            </div>

            <div class="footer-links-col">
                <h3 class="footer-heading">Leadership &amp; Governance</h3>
                <ul class="footer-list">
                    @foreach(($directors ?? []) as $dir)
                        <li>
                            <strong>{{ $dir['name'] }}</strong><br>
                            <span class="muted-small">{{ $dir['role'] }} • Appointed {{ $dir['appointed'] }}</span>
                        </li>
                    @endforeach
                    <li class="muted-small">Auth. Capital: {{ $company['authorized_capital'] ?? '₹5,00,000' }}</li>
                    <li class="muted-small">Paid-Up Capital: {{ $company['paid_up_capital'] ?? '₹1,00,000' }}</li>
                </ul>
            </div>

            <div class="footer-links-col">
                <h3 class="footer-heading">Registered Office</h3>
                <p class="footer-address">
                    <i class="fa-solid fa-location-dot" aria-hidden="true"></i>
                    <span>{{ $company['address'] }}</span>
                </p>
                <p class="footer-address">
                    <i class="fa-solid fa-envelope" aria-hidden="true"></i>
                    <a href="mailto:{{ $company['email'] }}">{{ $company['email'] }}</a>
                </p>
                <p class="footer-note">
                    <i class="fa-solid fa-phone-slash" aria-hidden="true"></i>
                    <span>Direct consumer phone lines are being provisioned; email &amp; web inquiry portal is live 24/7.</span>
                </p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; {{ $current_year ?? '2026' }} {{ $company['name'] ?? 'SERVI PHARM HEALTHCARE PRIVATE LIMITED' }}. All rights reserved.</p>
            <p class="footer-sources">Corporate Registry Verified via MCA • ROC Mumbai II • Tracxn • FalconeBiz • Delve</p>
        </div>
    </div>
</footer>

