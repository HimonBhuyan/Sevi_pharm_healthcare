<dialog id="order-modal" class="sp-dialog" closedby="any" aria-labelledby="order-modal-title">
    <div class="sp-dialog-card">
        <div class="sp-dialog-header">
            <div>
                <span class="pill-badge pill-badge-sm">
                    <span class="pulse-dot"></span> Official Inquiry Desk
                </span>
                <h2 id="order-modal-title" class="sp-dialog-title">Product &amp; Healthcare Inquiry</h2>
                <p class="sp-dialog-subtitle">Submit your requirement directly to <strong>{{ $company['email'] ?? 'servipharmhealthcare@gmail.com' }}</strong></p>
            </div>
            <button type="button" class="icon-btn dialog-close-btn" command="close" commandfor="order-modal" data-close-modal aria-label="Close inquiry dialog">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
            </button>
        </div>

        <form id="modal-inquiry-form" class="sp-form" action="?action=inquiry" method="POST">
            <input type="hidden" name="csrf_token" value="{{ $csrf_token ?? '' }}">

            <div class="form-grid-2">
                <div class="form-field">
                    <label for="modal-name">Full Name <span class="req">*</span></label>
                    <input type="text" id="modal-name" name="name" required autofocus placeholder="e.g., Rajesh Sharma" autocomplete="name">
                </div>
                <div class="form-field">
                    <label for="modal-email">Email Address <span class="req">*</span></label>
                    <input type="email" id="modal-email" name="email" required placeholder="you@example.com" autocomplete="email">
                </div>
            </div>

            <div class="form-grid-2">
                <div class="form-field">
                    <label for="modal-phone">Mobile / WhatsApp (Optional)</label>
                    <input type="tel" id="modal-phone" name="phone" placeholder="+91 98XXX XXXXX" autocomplete="tel">
                </div>
                <div class="form-field">
                    <label for="modal-category">Division</label>
                    <select id="modal-category" name="category">
                        <option value="Pharmaceuticals">Pharmaceuticals &amp; Rx Medicines</option>
                        <option value="Medical Goods">Medical Goods &amp; Diagnostics</option>
                        <option value="Orthopedic Goods">Orthopedic &amp; Rehab Goods</option>
                        <option value="Toilet Articles">Toilet Articles &amp; Derma-Hygiene</option>
                        <option value="Wholesale / Clinic Supply">Clinic / Institutional Supply</option>
                    </select>
                </div>
            </div>

            <div class="form-field">
                <label for="modal-product">Selected Item / Product Reference</label>
                <input type="text" id="modal-product" name="product" placeholder="e.g., Contoured Lumbar Sacral Support Belt or Medicine Name">
            </div>

            <div class="form-field">
                <label for="modal-message">Requirement Details <span class="req">*</span></label>
                <textarea id="modal-message" name="message" rows="3" required placeholder="Share medicine name, strength, orthopedic size, quantity, or delivery location in Ambarnath / Thane..."></textarea>
            </div>

            <div class="sp-dialog-actions">
                <button type="button" class="btn btn-ghost" command="close" commandfor="order-modal" data-close-modal>Cancel</button>
                <button type="submit" class="btn btn-primary" id="modal-submit-btn">
                    <i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
                    <span>Send Inquiry</span>
                </button>
            </div>
        </form>
    </div>
</dialog>

