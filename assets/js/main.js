(function () {
  'use strict';

  const root = document.documentElement;
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)');
  const catalogList = Array.isArray(window.SERVIPHARM_CATALOG) ? window.SERVIPHARM_CATALOG : [];
  const catalogMap = new Map(catalogList.map((item) => [item.id, item]));

  // ---------------------------------------------------------
  // 0. Branded Page Loader Dismiss & Back-to-Top Control
  // ---------------------------------------------------------
  const pageLoader = document.getElementById('page-loader');
  if (pageLoader) {
    const dismissLoader = () => {
      if (!pageLoader.classList.contains('is-done')) {
        pageLoader.classList.add('is-done');
        setTimeout(() => {
          pageLoader.style.display = 'none';
        }, 480);
      }
    };

    if (prefersReducedMotion.matches) {
      dismissLoader();
    } else {
      window.addEventListener('load', () => {
        setTimeout(dismissLoader, 420);
      });
      // Safety timeout so loader never stays stuck
      setTimeout(dismissLoader, 1100);
    }
  }

  const backToTopBtn = document.getElementById('back-to-top');
  if (backToTopBtn) {
    window.addEventListener(
      'scroll',
      () => {
        backToTopBtn.hidden = window.scrollY < 420;
      },
      { passive: true }
    );
    backToTopBtn.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: prefersReducedMotion.matches ? 'auto' : 'smooth' });
    });
  }

  const formatINR = (num) => '₹' + new Intl.NumberFormat('en-IN').format(Math.round(Number(num) || 0));

  // ---------------------------------------------------------
  // 1. Toast Notification Helper
  // ---------------------------------------------------------
  function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast is-${type}`;
    const icon = document.createElement('i');
    icon.className = type === 'success' ? 'fa-solid fa-circle-check' : 'fa-solid fa-circle-exclamation';
    const text = document.createElement('span');
    text.textContent = message;

    toast.append(icon, text);
    container.appendChild(toast);

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(8px)';
      toast.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
      setTimeout(() => toast.remove(), 260);
    }, 4000);
  }

  // ---------------------------------------------------------
  // 2. Theme Toggle & Fullscreen Toggle
  // ---------------------------------------------------------
  const fullscreenBtn = document.getElementById('fullscreen-toggle');
  const fullscreenIcon = document.getElementById('fullscreen-icon');
  if (fullscreenBtn) {
    fullscreenBtn.addEventListener('click', async () => {
      try {
        if (!document.fullscreenElement) {
          await document.documentElement.requestFullscreen();
        } else {
          await document.exitFullscreen();
        }
      } catch (e) {}
    });

    document.addEventListener('fullscreenchange', () => {
      if (fullscreenIcon) {
        fullscreenIcon.className = document.fullscreenElement ? 'fa-solid fa-compress' : 'fa-solid fa-expand';
      }
    });
  }

  const themeBtn = document.getElementById('theme-toggle');
  if (themeBtn) {
    themeBtn.addEventListener('click', () => {
      const currentAttr = root.getAttribute('data-theme');
      const systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
      const isCurrentlyDark = currentAttr ? currentAttr === 'dark' : systemDark;
      const nextTheme = isCurrentlyDark ? 'light' : 'dark';
      root.setAttribute('data-theme', nextTheme);
      try {
        localStorage.setItem('servipharm_theme', nextTheme);
      } catch (e) {}
    });
  }

  // ---------------------------------------------------------
  // 3. Sticky Header, Logo Home Link & Mobile Drawer
  // ---------------------------------------------------------
  const logoLink = document.getElementById('site-logo-link');
  if (logoLink) {
    logoLink.addEventListener('click', (e) => {
      const params = new URLSearchParams(window.location.search);
      if (!params.has('page')) {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
        if (window.location.hash) {
          history.replaceState(null, '', window.location.pathname);
        }
      }
    });
  }

  const header = document.getElementById('site-header');
  if (header) {
    window.addEventListener('scroll', () => {
      header.classList.toggle('is-scrolled', window.scrollY > 18);
    }, { passive: true });
  }

  const mobileBtn = document.getElementById('mobile-menu-btn');
  const mobileDrawer = document.getElementById('mobile-drawer');
  if (mobileBtn && mobileDrawer) {
    mobileBtn.addEventListener('click', () => {
      const willOpen = mobileDrawer.hidden;
      mobileDrawer.hidden = !willOpen;
      mobileBtn.setAttribute('aria-expanded', String(willOpen));
    });

    mobileDrawer.querySelectorAll('a, button').forEach((el) => {
      el.addEventListener('click', () => {
        mobileDrawer.hidden = true;
        mobileBtn.setAttribute('aria-expanded', 'false');
      });
    });
  }

  // ---------------------------------------------------------
  // 4. Scroll Progress Fallback (Only when CSS scroll() unsupported)
  // ---------------------------------------------------------
  const progressBar = document.getElementById('scroll-progress');
  if (progressBar && !CSS.supports('animation-timeline', 'scroll()')) {
    const updateProgress = () => {
      const scrollable = document.documentElement.scrollHeight - window.innerHeight;
      const ratio = scrollable > 0 ? Math.min(1, Math.max(0, window.scrollY / scrollable)) : 0;
      progressBar.style.transform = `scaleX(${ratio})`;
    };
    window.addEventListener('scroll', updateProgress, { passive: true });
    updateProgress();
  }

  // ---------------------------------------------------------
  // 5. Scroll Entry Reveal Fallback (IntersectionObserver)
  // ---------------------------------------------------------
  const supportsViewTimeline = CSS.supports('(animation-timeline: view()) and (animation-range: entry)');
  if (!supportsViewTimeline && !prefersReducedMotion.matches) {
    const revealEls = document.querySelectorAll('.scroll-reveal');
    if (revealEls.length > 0 && 'IntersectionObserver' in window) {
      const observer = new IntersectionObserver(
        (entries, obs) => {
          for (const entry of entries) {
            if (entry.isIntersecting) {
              entry.target.classList.add('is-revealed');
              obs.unobserve(entry.target);
            }
          }
        },
        { threshold: 0.12, rootMargin: '0px 0px -30px 0px' }
      );
      revealEls.forEach((el) => {
        el.classList.add('reveal-pending');
        observer.observe(el);
      });
    }
  }

  // ---------------------------------------------------------
  // 6. Native <dialog> Modals (#order-modal & #cart-modal)
  // ---------------------------------------------------------
  const orderModal = document.getElementById('order-modal');
  const cartModal = document.getElementById('cart-modal');

  function setupDialogFallbacks(dialog) {
    if (!dialog) return;
    dialog.addEventListener('close', () => {
      dialog.returnValue = '';
    });

    if (!('closedBy' in HTMLDialogElement.prototype)) {
      const outside = (e, d) => {
        const r = d.getBoundingClientRect();
        return e.clientX < r.left || e.clientX > r.right || e.clientY < r.top || e.clientY > r.bottom;
      };
      let pressedOutside = false;
      dialog.addEventListener('pointerdown', (e) => {
        pressedOutside = outside(e, dialog);
      });
      dialog.addEventListener('pointerup', (e) => {
        if (pressedOutside && outside(e, dialog)) {
          dialog.close('dismiss');
        }
      });
    }
  }

  setupDialogFallbacks(orderModal);
  setupDialogFallbacks(cartModal);

  if (!('commandForElement' in HTMLButtonElement.prototype)) {
    document.querySelectorAll('[command="show-modal"]').forEach((btn) => {
      const targetId = btn.getAttribute('commandfor');
      const targetDialog = targetId ? document.getElementById(targetId) : null;
      if (targetDialog) {
        btn.addEventListener('click', () => {
          if (!targetDialog.open) targetDialog.showModal();
        });
      }
    });
    document.querySelectorAll('[command="close"]').forEach((btn) => {
      const targetId = btn.getAttribute('commandfor');
      const targetDialog = targetId ? document.getElementById(targetId) : null;
      if (targetDialog) {
        btn.addEventListener('click', () => targetDialog.close('cancel'));
      }
    });
  }

  document.querySelectorAll('[data-open-order]').forEach((btn) => {
    if (!btn.hasAttribute('command')) {
      btn.addEventListener('click', () => {
        if (orderModal && !orderModal.open) orderModal.showModal();
      });
    }
  });

  document.querySelectorAll('[data-open-cart]').forEach((btn) => {
    btn.addEventListener('click', () => {
      resetCartModalViews();
      if (cartModal && !cartModal.open) cartModal.showModal();
    });
  });

  // ---------------------------------------------------------
  // 7. E-Commerce Shopping Cart State & Checkout Engine
  // ---------------------------------------------------------
  const CART_STORAGE_KEY = 'servipharm_cart_v1';
  let cartState = [];
  let appliedCoupon = 'SERVI10';
  let selectedDeliverySpeed = 'standard';

  try {
    const raw = localStorage.getItem(CART_STORAGE_KEY);
    if (raw) {
      const parsed = JSON.parse(raw);
      if (Array.isArray(parsed)) {
        cartState = parsed.filter((x) => x && catalogMap.has(x.id) && x.qty > 0);
      }
    }
  } catch (e) {}

  function saveCart() {
    try {
      localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cartState));
    } catch (e) {}
  }

  function computeCartPricing() {
    let totalCount = 0;
    let subtotal = 0;
    let mrpTotal = 0;

    cartState.forEach((entry) => {
      const p = catalogMap.get(entry.id);
      if (!p) return;
      totalCount += entry.qty;
      subtotal += Number(p.price) * entry.qty;
      mrpTotal += Number(p.mrp) * entry.qty;
    });

    let couponDiscount = 0;
    if (appliedCoupon === 'ORTHO15' && subtotal >= 1500) {
      couponDiscount = Math.round(subtotal * 0.15);
    } else if (['SERVI10', 'AMBARNATH', 'WELCOME10', 'ORTHO15'].includes(appliedCoupon)) {
      couponDiscount = Math.round(subtotal * 0.1);
    }

    const netItemsTotal = Math.max(0, subtotal - couponDiscount);
    let deliveryFee = 0;
    if (totalCount > 0) {
      if (selectedDeliverySpeed === 'express') {
        deliveryFee = appliedCoupon === 'AMBARNATH' ? 0 : 79;
      } else {
        deliveryFee = netItemsTotal >= 499 ? 0 : 49;
      }
    }

    const grandTotal = netItemsTotal + deliveryFee;
    const mrpDiscount = mrpTotal - subtotal;
    const totalSavings = mrpDiscount + couponDiscount;

    return {
      totalCount,
      subtotal,
      mrpTotal,
      couponDiscount,
      netItemsTotal,
      deliveryFee,
      grandTotal,
      mrpDiscount,
      totalSavings,
    };
  }

  function addToCart(productId, qtyToAdd = 1, openCartImmediately = false) {
    const product = catalogMap.get(productId);
    if (!product) return;

    const cleanQty = Math.max(1, Math.min(20, Number(qtyToAdd) || 1));
    const existing = cartState.find((item) => item.id === productId);
    if (existing) {
      existing.qty = Math.min(30, existing.qty + cleanQty);
    } else {
      cartState.push({ id: productId, qty: cleanQty });
    }

    saveCart();
    renderCartUI();
    showToast(`Added ${cleanQty} × ${product.name} to your cart`);

    if (openCartImmediately && cartModal) {
      resetCartModalViews();
      if (!cartModal.open) cartModal.showModal();
    }
  }

  function updateItemQty(productId, nextQty) {
    const idx = cartState.findIndex((i) => i.id === productId);
    if (idx === -1) return;
    if (nextQty <= 0) {
      cartState.splice(idx, 1);
    } else {
      cartState[idx].qty = Math.min(30, nextQty);
    }
    saveCart();
    renderCartUI();
  }

  function clearCart() {
    cartState = [];
    saveCart();
    renderCartUI();
  }

  function resetCartModalViews() {
    const confirmedView = document.getElementById('order-confirmed-view');
    if (confirmedView) confirmedView.hidden = true;
    renderCartUI();
  }

  function buildCartLineItemsInto(containerEl) {
    if (!containerEl) return;
    containerEl.innerHTML = '';

    cartState.forEach((entry) => {
      const p = catalogMap.get(entry.id);
      if (!p) return;

      const row = document.createElement('div');
      row.className = 'cart-line-item';
      row.innerHTML = `
        <img src="${p.image}" alt="${p.name}" class="cart-line-thumb">
        <div>
          <h4 class="cart-line-title">${p.name}</h4>
          <div class="cart-line-meta">${p.category} • ${p.pack_size} • SKU: ${p.sku}</div>
          <div class="cart-line-price">${formatINR(p.price)} × ${entry.qty} = ${formatINR(p.price * entry.qty)} <span style="color:var(--text-muted);font-weight:400;font-size:0.75rem;"><del>${formatINR(p.mrp * entry.qty)}</del></span></div>
        </div>
        <div class="cart-line-controls">
          <div class="qty-stepper">
            <button type="button" class="qty-btn" data-cart-dec="${p.id}" aria-label="Decrease">−</button>
            <input type="number" class="qty-input" value="${entry.qty}" readonly>
            <button type="button" class="qty-btn" data-cart-inc="${p.id}" aria-label="Increase">+</button>
          </div>
          <button type="button" class="cart-remove-btn" data-cart-remove="${p.id}">
            <i class="fa-regular fa-trash-can" aria-hidden="true"></i> Remove
          </button>
        </div>
      `;
      containerEl.appendChild(row);
    });

    containerEl.querySelectorAll('[data-cart-dec]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-cart-dec');
        const item = cartState.find((i) => i.id === id);
        if (item) updateItemQty(id, item.qty - 1);
      });
    });

    containerEl.querySelectorAll('[data-cart-inc]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-cart-inc');
        const item = cartState.find((i) => i.id === id);
        if (item) updateItemQty(id, item.qty + 1);
      });
    });

    containerEl.querySelectorAll('[data-cart-remove]').forEach((btn) => {
      btn.addEventListener('click', () => {
        const id = btn.getAttribute('data-cart-remove');
        updateItemQty(id, 0);
      });
    });
  }

  function renderCartUI() {
    const pricing = computeCartPricing();
    const {
      totalCount,
      mrpTotal,
      couponDiscount,
      netItemsTotal,
      deliveryFee,
      grandTotal,
      mrpDiscount,
      totalSavings,
    } = pricing;

    // Header & Floating Bar Badges
    const countBadge = document.getElementById('cart-count-badge');
    const headerTotal = document.getElementById('cart-header-total');
    const floatingBar = document.getElementById('floating-cart-bar');
    const floatingCount = document.getElementById('floating-cart-count');
    const floatingTotal = document.getElementById('floating-cart-total');
    const floatingSavings = document.getElementById('floating-cart-savings');

    if (countBadge) countBadge.textContent = String(totalCount);
    if (headerTotal) headerTotal.textContent = formatINR(netItemsTotal);

    const onDedicatedCheckoutPage = Boolean(document.getElementById('page-checkout-workspace'));
    if (floatingBar) {
      floatingBar.hidden = totalCount === 0 || onDedicatedCheckoutPage;
      if (floatingCount) floatingCount.textContent = `${totalCount} ${totalCount === 1 ? 'Item' : 'Items'}`;
      if (floatingTotal) floatingTotal.textContent = formatINR(grandTotal);
      if (floatingSavings) {
        floatingSavings.textContent = ` • Saving ${formatINR(totalSavings)} on this order`;
      }
    }

    // Free Delivery Progress Helper
    function updateFreeDeliveryBar(msgId, fillId) {
      const msgEl = document.getElementById(msgId);
      const fillEl = document.getElementById(fillId);
      if (!msgEl || !fillEl) return;
      if (netItemsTotal >= 499) {
        msgEl.innerHTML = '<strong>Unlocked!</strong> You qualify for FREE Express Delivery in Ambarnath & Thane (421503).';
        fillEl.style.width = '100%';
      } else {
        const remaining = 499 - netItemsTotal;
        const pct = Math.min(100, Math.round((netItemsTotal / 499) * 100));
        msgEl.textContent = `Add ${formatINR(remaining)} more for FREE Express Local Delivery!`;
        fillEl.style.width = `${pct}%`;
      }
    }

    updateFreeDeliveryBar('free-delivery-msg', 'free-delivery-fill');
    updateFreeDeliveryBar('page-free-delivery-msg', 'page-free-delivery-fill');

    // 1. Update Cart Modal
    const emptyState = document.getElementById('cart-empty-state');
    const activeLayout = document.getElementById('cart-active-layout');
    const confirmedView = document.getElementById('order-confirmed-view');
    const isShowingConfirmed = confirmedView && !confirmedView.hidden;

    if (emptyState && activeLayout && !isShowingConfirmed) {
      emptyState.hidden = totalCount > 0;
      activeLayout.hidden = totalCount === 0;
    }

    const itemsHeading = document.getElementById('cart-items-heading');
    if (itemsHeading) itemsHeading.textContent = `Cart Items (${totalCount})`;
    buildCartLineItemsInto(document.getElementById('cart-items-list'));

    const setSummaryFields = (prefix) => {
      const elMrp = document.getElementById(`${prefix}summary-mrp`);
      const elDisc = document.getElementById(`${prefix}summary-discount`);
      const elCouponRow = document.getElementById(`${prefix}summary-coupon-row`);
      const elCouponCode = document.getElementById(`${prefix}summary-coupon-code`);
      const elCouponVal = document.getElementById(`${prefix}summary-coupon-val`);
      const elDelivery = document.getElementById(`${prefix}summary-delivery`);
      const elGrand = document.getElementById(`${prefix}summary-grand-total`);

      if (elMrp) elMrp.textContent = formatINR(mrpTotal);
      if (elDisc) elDisc.textContent = `-${formatINR(mrpDiscount)}`;
      if (elCouponRow) {
        elCouponRow.hidden = couponDiscount <= 0;
        if (elCouponCode) elCouponCode.textContent = appliedCoupon;
        if (elCouponVal) elCouponVal.textContent = `-${formatINR(couponDiscount)}`;
      }
      if (elDelivery) elDelivery.textContent = deliveryFee === 0 ? 'FREE' : formatINR(deliveryFee);
      if (elGrand) elGrand.textContent = formatINR(grandTotal);
    };

    setSummaryFields('');
    setSummaryFields('page-');

    const elPlaceBtnText = document.getElementById('place-order-btn-text');
    if (elPlaceBtnText) elPlaceBtnText.textContent = `Place Order • ${formatINR(grandTotal)}`;

    const elPagePlaceText = document.getElementById('page-place-order-text');
    if (elPagePlaceText) {
      elPagePlaceText.textContent = `Confirm Order & Generate Invoice • ${formatINR(grandTotal)}`;
    }

    const elPageSavings = document.getElementById('page-total-savings');
    if (elPageSavings) elPageSavings.textContent = formatINR(totalSavings);

    // 2. Update Dedicated Checkout Page (?page=checkout)
    const pageEmpty = document.getElementById('page-cart-empty');
    const pageWorkspace = document.getElementById('page-checkout-workspace');
    const pageHeading = document.getElementById('page-cart-heading');

    if (pageEmpty && pageWorkspace) {
      pageEmpty.hidden = totalCount > 0;
      pageWorkspace.hidden = totalCount === 0;
    }
    if (pageHeading) {
      pageHeading.textContent = `Items in Your Healthcare Cart (${totalCount})`;
    }
    buildCartLineItemsInto(document.getElementById('page-cart-items-list'));
  }

  // Product Card Quantity Steppers, Add to Cart & Buy Now Buttons
  document.querySelectorAll('.ecom-product-card').forEach((card) => {
    const decBtn = card.querySelector('[data-qty-dec]');
    const incBtn = card.querySelector('[data-qty-inc]');
    const qtyInput = card.querySelector('.qty-input');

    if (decBtn && qtyInput) {
      decBtn.addEventListener('click', () => {
        qtyInput.value = String(Math.max(1, (Number(qtyInput.value) || 1) - 1));
      });
    }
    if (incBtn && qtyInput) {
      incBtn.addEventListener('click', () => {
        qtyInput.value = String(Math.min(20, (Number(qtyInput.value) || 1) + 1));
      });
    }
  });

  document.querySelectorAll('[data-add-cart]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const pid = btn.getAttribute('data-add-cart') || '';
      const parentCard = btn.closest('.ecom-product-card');
      const qtyInput = parentCard ? parentCard.querySelector('.qty-input') : null;
      const qty = qtyInput ? Number(qtyInput.value) || 1 : Number(btn.getAttribute('data-qty') || 1);
      addToCart(pid, qty, false);
    });
  });

  document.querySelectorAll('[data-buy-now]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const pid = btn.getAttribute('data-buy-now') || '';
      const parentCard = btn.closest('.ecom-product-card');
      const qtyInput = parentCard ? parentCard.querySelector('.qty-input') : null;
      const qty = qtyInput ? Number(qtyInput.value) || 1 : 1;
      addToCart(pid, qty, true);
    });
  });

  // Wishlist Heart Toggle
  document.querySelectorAll('[data-wishlist]').forEach((btn) => {
    btn.addEventListener('click', () => {
      const isSaved = btn.classList.toggle('is-saved');
      const icon = btn.querySelector('i');
      if (icon) {
        icon.className = isSaved ? 'fa-solid fa-heart' : 'fa-regular fa-heart';
      }
      showToast(isSaved ? 'Saved to your healthcare wishlist' : 'Removed from wishlist');
    });
  });

  // Clear Cart Buttons (Modal & Page)
  ['clear-cart-btn', 'page-clear-cart-btn'].forEach((id) => {
    const btn = document.getElementById(id);
    if (btn) btn.addEventListener('click', clearCart);
  });

  // Coupon Application (Modal & Page & Clickable Promo Chips)
  function applyCouponCode(rawCode) {
    const code = (rawCode || '').trim().toUpperCase();
    const modalInput = document.getElementById('cart-coupon-input');
    const pageInput = document.getElementById('page-coupon-input');
    const modalMsg = document.getElementById('coupon-status-msg');
    const pageMsg = document.getElementById('page-coupon-status');

    if (modalInput) modalInput.value = code;
    if (pageInput) pageInput.value = code;

    document.querySelectorAll('[data-apply-coupon]').forEach((chip) => {
      chip.classList.toggle('is-active', chip.getAttribute('data-apply-coupon') === code);
    });

    if (['SERVI10', 'AMBARNATH', 'WELCOME10', 'ORTHO15'].includes(code)) {
      appliedCoupon = code;
      const desc = code === 'ORTHO15' ? '15% OFF on ₹1,500+ (10% under ₹1,500)' : '10% discount';
      const html = `<span style="color: var(--emerald); font-weight: 700;">Coupon ${code} active (${desc})!</span>`;
      if (modalMsg) modalMsg.innerHTML = html;
      if (pageMsg) pageMsg.innerHTML = html;
      showToast(`Coupon ${code} applied!`);
    } else {
      appliedCoupon = '';
      const errHtml = `<span style="color: #ef4444;">Invalid code. Try SERVI10, AMBARNATH, or ORTHO15.</span>`;
      if (modalMsg) modalMsg.innerHTML = errHtml;
      if (pageMsg) pageMsg.innerHTML = errHtml;
    }
    renderCartUI();
  }

  const applyCouponBtn = document.getElementById('apply-coupon-btn');
  const couponInput = document.getElementById('cart-coupon-input');
  if (applyCouponBtn && couponInput) {
    applyCouponBtn.addEventListener('click', () => applyCouponCode(couponInput.value));
  }

  const pageApplyCouponBtn = document.getElementById('page-apply-coupon-btn');
  const pageCouponInput = document.getElementById('page-coupon-input');
  if (pageApplyCouponBtn && pageCouponInput) {
    pageApplyCouponBtn.addEventListener('click', () => applyCouponCode(pageCouponInput.value));
  }

  document.querySelectorAll('[data-apply-coupon]').forEach((chip) => {
    chip.addEventListener('click', () => {
      applyCouponCode(chip.getAttribute('data-apply-coupon') || 'SERVI10');
    });
  });

  // Delivery Speed & Radio Card Selection on Dedicated Checkout Page
  document.querySelectorAll('input[name="delivery_speed"]').forEach((radio) => {
    radio.addEventListener('change', () => {
      selectedDeliverySpeed = radio.value;
      document.querySelectorAll('input[name="delivery_speed"]').forEach((r) => {
        const card = r.closest('.radio-card');
        if (card) card.classList.toggle('is-selected', r.checked);
      });
      renderCartUI();
    });
  });

  document.querySelectorAll('input[name="payment_method"]').forEach((radio) => {
    radio.addEventListener('change', () => {
      document.querySelectorAll('input[name="payment_method"]').forEach((r) => {
        const card = r.closest('.radio-card');
        if (card) card.classList.toggle('is-selected', r.checked);
      });
    });
  });

  // PIN Code Serviceability Checker on Dedicated Checkout Page
  const pincodeInput = document.getElementById('pchk-pincode');
  const pincodeBadge = document.getElementById('pincode-status-badge');
  if (pincodeInput && pincodeBadge) {
    pincodeInput.addEventListener('input', () => {
      const val = pincodeInput.value.trim();
      if (val === '421503' || val.startsWith('421')) {
        pincodeBadge.innerHTML = '<i class="fa-solid fa-circle-check" aria-hidden="true"></i> Ambarnath / Thane Express Zone';
        pincodeBadge.style.color = 'var(--emerald)';
      } else if (/^4\d{5}$/.test(val)) {
        pincodeBadge.innerHTML = '<i class="fa-solid fa-truck" aria-hidden="true"></i> Maharashtra Standard Delivery';
        pincodeBadge.style.color = 'var(--brand-primary)';
      } else if (val.length === 6) {
        pincodeBadge.innerHTML = '<i class="fa-solid fa-box" aria-hidden="true"></i> Pan-India Courier Dispatch';
        pincodeBadge.style.color = 'var(--text-muted)';
      }
    });
  }

  // Unified Checkout Form Handler (Works for both Modal #checkout-form and Full-Page #page-checkout-form)
  function bindCheckoutSubmission(formId, submitBtnId, isFullPage) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      if (cartState.length === 0) return;

      const submitBtn = document.getElementById(submitBtnId);
      const originalHtml = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>Confirming Order...</span>';
      }

      const formData = new FormData(form);
      const payload = {
        customer_name: formData.get('customer_name'),
        customer_email: formData.get('customer_email'),
        customer_phone: formData.get('customer_phone'),
        pincode: formData.get('pincode'),
        city: formData.get('city') || 'Ambarnath, Thane',
        delivery_speed: formData.get('delivery_speed') || selectedDeliverySpeed,
        payment_method: formData.get('payment_method'),
        upi_id: formData.get('upi_id') || '',
        delivery_address: formData.get('delivery_address'),
        rx_note: formData.get('rx_note'),
        coupon_code: appliedCoupon,
        items: cartState,
      };

      try {
        const res = await fetch('?action=checkout', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload),
        });
        const data = await res.json();
        if (res.ok && data.success && data.order) {
          const order = data.order;
          clearCart();
          form.reset();
          showToast(data.message || 'Order confirmed!');

          if (isFullPage && data.redirect_url) {
            window.location.href = data.redirect_url;
            return;
          }

          // Show in-modal receipt + link to full printable invoice
          const activeLayout = document.getElementById('cart-active-layout');
          const emptyState = document.getElementById('cart-empty-state');
          const confirmedView = document.getElementById('order-confirmed-view');
          const confirmedNum = document.getElementById('confirmed-order-number');
          const confirmedCard = document.getElementById('confirmed-summary-card');
          const invoiceLink = document.getElementById('view-invoice-link');

          if (activeLayout) activeLayout.hidden = true;
          if (emptyState) emptyState.hidden = true;
          if (confirmedView) confirmedView.hidden = false;
          if (confirmedNum) confirmedNum.textContent = `Order #${order.order_number} Confirmed!`;
          if (invoiceLink && data.redirect_url) {
            invoiceLink.setAttribute('href', data.redirect_url);
          }
          if (confirmedCard) {
            const linesHtml = order.items
              .map((l) => `<div style="display:flex;justify-content:space-between;padding:4px 0;"><span>${l.qty} × ${l.name}</span><strong>${formatINR(l.line_total)}</strong></div>`)
              .join('');
            confirmedCard.innerHTML = `
              <div style="margin-bottom:8px;"><strong>Customer:</strong> ${order.customer.name} (${order.customer.phone})</div>
              <div style="margin-bottom:8px;"><strong>Delivery To:</strong> ${order.customer.address} - ${order.customer.pincode}</div>
              <div style="margin-bottom:8px;"><strong>Payment Mode:</strong> ${order.payment_method}</div>
              <hr style="border:0;border-top:1px solid var(--border-color);margin:8px 0;">
              ${linesHtml}
              <hr style="border:0;border-top:1px solid var(--border-color);margin:8px 0;">
              <div style="display:flex;justify-content:space-between;font-size:1rem;">
                <strong>Net Payable:</strong>
                <strong style="color:var(--brand-primary);">${formatINR(order.pricing.grand_total)}</strong>
              </div>
              <div style="color:var(--emerald);font-size:0.8rem;margin-top:4px;">Total Savings: ${formatINR(order.pricing.total_savings)}</div>
            `;
          }
        } else {
          showToast(data.message || 'Please check your checkout details.', 'error');
        }
      } catch (err) {
        showToast('Network error placing order. Please try again.', 'error');
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalHtml;
        }
      }
    });
  }

  bindCheckoutSubmission('checkout-form', 'place-order-btn', false);
  bindCheckoutSubmission('page-checkout-form', 'page-place-order-btn', true);

  const continueShoppingBtn = document.getElementById('continue-shopping-btn');
  if (continueShoppingBtn && cartModal) {
    continueShoppingBtn.addEventListener('click', () => {
      resetCartModalViews();
      cartModal.close('done');
    });
  }

  // Initialize Cart UI on Load
  renderCartUI();

  // ---------------------------------------------------------
  // 8. Catalog Live Filtering, Sorting & Header Search Sync
  // ---------------------------------------------------------
  const filterButtons = document.querySelectorAll('.filter-pill');
  const searchInput = document.getElementById('catalog-search');
  const headerSearchInput = document.getElementById('header-search-input');
  const mobileSearchInput = document.getElementById('mobile-search-input');
  const sortSelect = document.getElementById('catalog-sort');
  const catalogGrid = document.getElementById('catalog-grid');
  const catalogCards = Array.from(document.querySelectorAll('.ecom-product-card'));
  const emptyState = document.getElementById('catalog-empty');
  const activeInitPill = document.querySelector('.filter-pill.is-active');
  let activeCategory = activeInitPill ? (activeInitPill.getAttribute('data-filter') || 'all') : 'all';

  function applyCatalogFilterAndSort() {
    if (catalogCards.length === 0) return;
    const query = (searchInput ? searchInput.value : '').trim().toLowerCase();
    const sortBy = sortSelect ? sortSelect.value : 'featured';
    let visibleCount = 0;

    const sorted = [...catalogCards].sort((a, b) => {
      const priceA = Number(a.getAttribute('data-price') || 0);
      const priceB = Number(b.getAttribute('data-price') || 0);
      const discA = Number(a.getAttribute('data-discount') || 0);
      const discB = Number(b.getAttribute('data-discount') || 0);
      const rateA = Number(a.getAttribute('data-rating') || 0);
      const rateB = Number(b.getAttribute('data-rating') || 0);
      const idxA = Number(a.getAttribute('data-index') || 0);
      const idxB = Number(b.getAttribute('data-index') || 0);

      if (sortBy === 'price-asc') return priceA - priceB;
      if (sortBy === 'price-desc') return priceB - priceA;
      if (sortBy === 'discount-desc') return discB - discA;
      if (sortBy === 'rating-desc') return rateB - rateA;
      return idxA - idxB;
    });

    if (catalogGrid) {
      sorted.forEach((card) => catalogGrid.appendChild(card));
    }

    sorted.forEach((card) => {
      const cardCat = card.getAttribute('data-category') || '';
      const haystack = card.getAttribute('data-search') || '';
      const catMatch = activeCategory === 'all' || cardCat === activeCategory;
      const queryMatch = query === '' || haystack.includes(query);
      const show = catMatch && queryMatch;
      card.hidden = !show;
      if (show) visibleCount++;
    });

    if (emptyState) {
      emptyState.hidden = visibleCount > 0;
    }
  }

  filterButtons.forEach((btn) => {
    btn.addEventListener('click', () => {
      activeCategory = btn.getAttribute('data-filter') || 'all';
      filterButtons.forEach((b) => {
        const isCurrent = b === btn;
        b.classList.toggle('is-active', isCurrent);
        b.setAttribute('aria-selected', String(isCurrent));
      });
      applyCatalogFilterAndSort();
    });
  });

  if (searchInput) {
    searchInput.addEventListener('input', () => {
      if (headerSearchInput && headerSearchInput.value !== searchInput.value) {
        headerSearchInput.value = searchInput.value;
      }
      applyCatalogFilterAndSort();
    });
  }

  if (headerSearchInput) {
    headerSearchInput.addEventListener('input', () => {
      if (searchInput) {
        searchInput.value = headerSearchInput.value;
        applyCatalogFilterAndSort();
      }
    });
    headerSearchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        const catalogSec = document.getElementById('catalog');
        if (catalogSec) {
          catalogSec.scrollIntoView({ behavior: 'smooth' });
        } else {
          window.location.href = `./#catalog`;
        }
      }
    });
  }

  if (mobileSearchInput) {
    mobileSearchInput.addEventListener('input', () => {
      if (searchInput) {
        searchInput.value = mobileSearchInput.value;
        applyCatalogFilterAndSort();
      }
    });
    mobileSearchInput.addEventListener('keydown', (e) => {
      if (e.key === 'Enter') {
        if (mobileDrawer) mobileDrawer.hidden = true;
        if (mobileBtn) mobileBtn.setAttribute('aria-expanded', 'false');
        const catalogSec = document.getElementById('catalog');
        if (catalogSec) {
          catalogSec.scrollIntoView({ behavior: 'smooth' });
        } else {
          window.location.href = `./#catalog`;
        }
      }
    });
  }

  if (sortSelect) {
    sortSelect.addEventListener('change', applyCatalogFilterAndSort);
  }

  document.querySelectorAll('[data-filter-jump]').forEach((link) => {
    link.addEventListener('click', () => {
      const targetFilter = link.getAttribute('data-filter-jump') || 'all';
      const targetBtn = document.querySelector(`.filter-pill[data-filter="${targetFilter}"]`);
      if (targetBtn) targetBtn.click();
    });
  });

  if (activeCategory !== 'all') {
    applyCatalogFilterAndSort();
  }

  // ---------------------------------------------------------
  // 9. Copy CIN / Coupon / Address to Clipboard
  // ---------------------------------------------------------
  document.querySelectorAll('[data-copy]').forEach((btn) => {
    btn.addEventListener('click', async () => {
      const value = btn.getAttribute('data-copy') || '';
      try {
        await navigator.clipboard.writeText(value);
        showToast(`Copied to clipboard: ${value}`);
      } catch (e) {
        showToast('Unable to copy automatically — please select and copy.', 'error');
      }
    });
  });

  // ---------------------------------------------------------
  // 10. Spotlight & 3D Tilt Effects
  // ---------------------------------------------------------
  if (!prefersReducedMotion.matches) {
    document.querySelectorAll('.spotlight-card').forEach((card) => {
      card.addEventListener('pointermove', (e) => {
        const rect = card.getBoundingClientRect();
        card.style.setProperty('--mx', `${e.clientX - rect.left}px`);
        card.style.setProperty('--my', `${e.clientY - rect.top}px`);
      });
    });

    document.querySelectorAll('[data-tilt]').forEach((card) => {
      card.addEventListener('pointermove', (e) => {
        const rect = card.getBoundingClientRect();
        const x = (e.clientX - rect.left) / rect.width - 0.5;
        const y = (e.clientY - rect.top) / rect.height - 0.5;
        card.style.transform = `perspective(1000px) rotateX(${(-y * 5).toFixed(2)}deg) rotateY(${(x * 6).toFixed(2)}deg)`;
      });
      card.addEventListener('pointerleave', () => {
        card.style.transform = '';
      });
    });
  }

  // ---------------------------------------------------------
  // 11. Animated Corporate Capital & Metric Counters
  // ---------------------------------------------------------
  const counters = document.querySelectorAll('[data-counter]');
  if (counters.length > 0 && !prefersReducedMotion.matches && 'IntersectionObserver' in window) {
    const formatIndian = (num) => new Intl.NumberFormat('en-IN').format(Math.round(num));
    const counterObserver = new IntersectionObserver(
      (entries, obs) => {
        for (const entry of entries) {
          if (!entry.isIntersecting) continue;
          const el = entry.target;
          obs.unobserve(el);

          const target = Number(el.getAttribute('data-counter') || '0');
          const prefix = el.getAttribute('data-prefix') || '';
          const suffix = el.getAttribute('data-suffix') || '';
          const fmt = el.getAttribute('data-format') || 'plain';
          const duration = 900;
          const start = performance.now();

          const tick = (now) => {
            const p = Math.min(1, (now - start) / duration);
            const eased = 1 - Math.pow(1 - p, 3);
            const val = target * eased;
            const formatted = fmt === 'indian' ? formatIndian(val) : String(Math.round(val));
            el.textContent = `${prefix}${formatted}${suffix}`;
            if (p < 1) requestAnimationFrame(tick);
          };
          requestAnimationFrame(tick);
        }
      },
      { threshold: 0.35 }
    );
    counters.forEach((c) => counterObserver.observe(c));
  }

  // ---------------------------------------------------------
  // 12. Live Interactive Bio-Molecular, Medical Cross & ECG Canvas
  // ---------------------------------------------------------
  const canvas = document.getElementById('bio-canvas');
  if (canvas && !prefersReducedMotion.matches) {
    const ctx = canvas.getContext('2d');
    if (ctx) {
      let width = 0;
      let height = 0;
      let pointerX = -9999;
      let pointerY = -9999;
      let phase = 0;

      window.addEventListener(
        'pointermove',
        (e) => {
          pointerX = e.clientX;
          pointerY = e.clientY;
        },
        { passive: true }
      );
      window.addEventListener(
        'pointerleave',
        () => {
          pointerX = -9999;
          pointerY = -9999;
        },
        { passive: true }
      );

      // 52 living particles: molecular nodes, medical crosses (+), and hexagonal pharma rings
      const shapes = ['node', 'node', 'node', 'cross', 'hex'];
      const nodes = Array.from({ length: 52 }, (_, idx) => ({
        x: Math.random(),
        y: Math.random(),
        vx: (Math.random() - 0.5) * 0.00058,
        vy: (Math.random() - 0.5) * 0.00058,
        r: Math.random() * 2.6 + 1.8,
        shape: shapes[idx % shapes.length],
        angle: Math.random() * Math.PI * 2,
        spin: (Math.random() - 0.5) * 0.012,
        pulseOffset: Math.random() * Math.PI * 2,
      }));

      const resize = () => {
        width = canvas.width = window.innerWidth;
        height = canvas.height = window.innerHeight;
      };
      resize();
      window.addEventListener('resize', resize, { passive: true });

      const drawCross = (x, y, size, angle) => {
        ctx.save();
        ctx.translate(x, y);
        ctx.rotate(angle);
        const arm = size * 2.4;
        const thick = size * 0.78;
        ctx.fillRect(-arm / 2, -thick / 2, arm, thick);
        ctx.fillRect(-thick / 2, -arm / 2, thick, arm);
        ctx.restore();
      };

      const drawHex = (x, y, radius, angle) => {
        ctx.save();
        ctx.translate(x, y);
        ctx.rotate(angle);
        ctx.beginPath();
        for (let k = 0; k < 6; k++) {
          const theta = (Math.PI / 3) * k;
          const hx = Math.cos(theta) * radius;
          const hy = Math.sin(theta) * radius;
          if (k === 0) ctx.moveTo(hx, hy);
          else ctx.lineTo(hx, hy);
        }
        ctx.closePath();
        ctx.stroke();
        ctx.restore();
      };

      const draw = () => {
        ctx.clearRect(0, 0, width, height);
        phase += 0.022;

        const styles = getComputedStyle(root);
        const primary = styles.getPropertyValue('--brand-primary').trim() || '#14b8a6';
        const secondary = styles.getPropertyValue('--brand-secondary').trim() || '#38bdf8';

        // 1. Subtle interactive cursor bio-glow
        if (pointerX > 0 && pointerY > 0) {
          const grad = ctx.createRadialGradient(pointerX, pointerY, 8, pointerX, pointerY, 220);
          grad.addColorStop(0, 'rgba(45, 212, 191, 0.14)');
          grad.addColorStop(1, 'rgba(45, 212, 191, 0)');
          ctx.fillStyle = grad;
          ctx.beginPath();
          ctx.arc(pointerX, pointerY, 220, 0, Math.PI * 2);
          ctx.fill();
        }

        // 2. Sweeping translucent ECG heartbeat pulse line across mid-screen
        ctx.save();
        ctx.strokeStyle = primary;
        ctx.globalAlpha = 0.16;
        ctx.lineWidth = 1.4;
        ctx.beginPath();
        const baseY = height * 0.46;
        const beatX = ((phase * 140) % (width + 300)) - 150;
        for (let x = 0; x <= width; x += 6) {
          const d = x - beatX;
          let yOffset = Math.sin(x * 0.012 + phase) * 6;
          if (Math.abs(d) < 55) {
            if (d > -25 && d < -10) yOffset -= 14;
            else if (d >= -10 && d < 4) yOffset -= 44 * Math.cos((d + 3) * 0.22);
            else if (d >= 4 && d < 18) yOffset += 22;
            else if (d >= 24 && d < 42) yOffset -= 10;
          }
          if (x === 0) ctx.moveTo(x, baseY + yOffset);
          else ctx.lineTo(x, baseY + yOffset);
        }
        ctx.stroke();
        ctx.restore();

        // 3. Molecular nodes, floating medical crosses, hex rings & synaptic connections
        for (let i = 0; i < nodes.length; i++) {
          const a = nodes[i];
          a.x = (a.x + a.vx + 1) % 1;
          a.y = (a.y + a.vy + 1) % 1;
          a.angle += a.spin;

          let ax = a.x * width;
          let ay = a.y * height;

          // Gentle attraction/interaction with pointer
          const pdx = pointerX - ax;
          const pdy = pointerY - ay;
          const pDist = Math.hypot(pdx, pdy);
          if (pDist < 210 && pDist > 1) {
            ax += (pdx / pDist) * 8;
            ay += (pdy / pDist) * 8;

            ctx.strokeStyle = secondary;
            ctx.globalAlpha = (1 - pDist / 210) * 0.32;
            ctx.lineWidth = 1;
            ctx.beginPath();
            ctx.moveTo(ax, ay);
            ctx.lineTo(pointerX, pointerY);
            ctx.stroke();
          }

          const pulse = 0.75 + 0.25 * Math.sin(phase * 1.6 + a.pulseOffset);

          if (a.shape === 'cross') {
            ctx.fillStyle = primary;
            ctx.globalAlpha = 0.34 * pulse;
            drawCross(ax, ay, a.r * 1.45, a.angle);
          } else if (a.shape === 'hex') {
            ctx.strokeStyle = secondary;
            ctx.lineWidth = 1.25;
            ctx.globalAlpha = 0.32 * pulse;
            drawHex(ax, ay, a.r * 3.1, a.angle);
          } else {
            ctx.fillStyle = primary;
            ctx.globalAlpha = 0.45 * pulse;
            ctx.beginPath();
            ctx.arc(ax, ay, a.r, 0, Math.PI * 2);
            ctx.fill();
          }

          for (let j = i + 1; j < nodes.length; j++) {
            const b = nodes[j];
            const bx = b.x * width;
            const by = b.y * height;
            const dist = Math.hypot(ax - bx, ay - by);
            if (dist < 175) {
              ctx.strokeStyle = primary;
              ctx.globalAlpha = (1 - dist / 175) * 0.22;
              ctx.lineWidth = 1;
              ctx.beginPath();
              ctx.moveTo(ax, ay);
              ctx.lineTo(bx, by);
              ctx.stroke();
            }
          }
        }
        requestAnimationFrame(draw);
      };
      requestAnimationFrame(draw);
    }
  }

  // ---------------------------------------------------------
  // 13. AJAX Inquiry Form Submission (Contact Form & Rx Modal)
  // ---------------------------------------------------------
  function bindAjaxForm(formId, onSuccess) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', async (e) => {
      e.preventDefault();
      const submitBtn = form.querySelector('button[type="submit"]');
      const originalHtml = submitBtn ? submitBtn.innerHTML : '';
      if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span>Submitting...</span>';
      }

      try {
        const res = await fetch(form.getAttribute('action') || '?action=inquiry', {
          method: 'POST',
          body: new FormData(form),
        });
        const data = await res.json();
        if (res.ok && data.success) {
          showToast(data.message || 'Inquiry submitted successfully!', 'success');
          form.reset();
          if (typeof onSuccess === 'function') onSuccess(data);
        } else {
          showToast(data.message || 'Please check your inquiry fields.', 'error');
        }
      } catch (err) {
        showToast('Network error submitting inquiry. Please email servipharmhealthcare@gmail.com.', 'error');
      } finally {
        if (submitBtn) {
          submitBtn.disabled = false;
          submitBtn.innerHTML = originalHtml;
        }
      }
    });
  }

  bindAjaxForm('contact-inquiry-form');
  bindAjaxForm('modal-inquiry-form', () => {
    if (orderModal && orderModal.open) {
      orderModal.close('submitted');
    }
  });
})();
