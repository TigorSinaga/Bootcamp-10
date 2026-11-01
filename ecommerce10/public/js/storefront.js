/**
 * RuangBelanja — Modern Dynamic Storefront & UI Interactions
 */
(function () {
    'use strict';

    // ── Number & Currency Formatter ──
    const formatPrice = (amount) => new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(amount);

    // ── Toast Notifications ──
    const getToast = () => document.querySelector('#toast-message');
    let toastTimer;

    function showMessage(message, type = 'info') {
        const toast = getToast();
        if (!toast || !message) return;
        toast.textContent = message;
        toast.classList.add('show');
        if (type === 'success') {
            toast.style.borderLeftColor = '#10B981';
        } else if (type === 'error') {
            toast.style.borderLeftColor = '#C95656';
        } else {
            toast.style.borderLeftColor = 'var(--rb-accent)';
        }
        window.clearTimeout(toastTimer);
        toastTimer = window.setTimeout(() => toast.classList.remove('show'), 2800);
    }

    // ── Reusable Async Form Submitter ──
    async function submitJson(form, button, busyText) {
        const original = button.textContent;
        button.disabled = true;
        if (busyText) button.textContent = busyText;
        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    Accept: 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
            const result = await response.json();
            if (!response.ok) throw new Error(result.message || 'Terjadi kesalahan.');
            return result;
        } finally {
            button.disabled = false;
            button.textContent = original;
        }
    }

    // ── Add to Cart (Home & Product Lists) ──
    document.querySelectorAll('.add-to-cart-form').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            const originalText = button.textContent;
            try {
                button.disabled = true;
                button.textContent = 'Menambahkan...';
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        Accept: 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });
                const result = await response.json();
                if (!response.ok) throw new Error(result.message || 'Stok tidak mencukupi.');

                // Update cart count with animation
                const cartCount = document.querySelector('#cart-count');
                if (cartCount) {
                    cartCount.textContent = result.cartCount;
                    cartCount.classList.remove('cart-bump');
                    void cartCount.offsetWidth; // trigger reflow
                    cartCount.classList.add('cart-bump');
                }

                button.classList.add('is-added');
                button.textContent = '✓ Ditambahkan';
                showMessage(result.message || 'Produk berhasil ditambahkan!', 'success');

                setTimeout(() => {
                    button.classList.remove('is-added');
                    button.textContent = originalText;
                    button.disabled = false;
                }, 1400);
            } catch (error) {
                button.disabled = false;
                button.textContent = originalText;
                showMessage(error.message, 'error');
            }
        });
    });

    // ── Cart Quantity Steppers (AJAX Update) ──
    document.querySelectorAll('.cart-quantity-form').forEach((form) => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const button = form.querySelector('button[type="submit"]');
            const item = form.closest('.cart-item');
            try {
                const result = await submitJson(form, button);
                const quantity = Number(result.quantity);

                // Update row quantity and controls
                item.querySelector('[data-quantity-value]').textContent = quantity;
                item.querySelectorAll('.cart-quantity-form').forEach((quantityForm) => {
                    const quantityButton = quantityForm.querySelector('button');
                    const step = Number(quantityButton.dataset.quantityStep);
                    quantityForm.querySelector('[name="quantity"]').value = Math.max(1, quantity + step);
                    quantityButton.disabled = step < 0 ? quantity <= 1 : quantity >= Number(item.dataset.stock);
                });

                // Update summary item line
                const summary = document.querySelector(`[data-summary-product-id="${result.productId}"]`);
                if (summary) {
                    summary.querySelector('[data-summary-quantity]').textContent = quantity;
                    summary.querySelector('[data-summary-total]').textContent = formatPrice(result.itemTotal);
                }

                // Update totals and headers
                const countBadge = document.querySelector('#cart-item-count');
                if (countBadge) countBadge.textContent = `${result.cartCount} item`;
                const summaryQty = document.querySelector('#summary-quantity');
                if (summaryQty) summaryQty.textContent = `(${result.cartCount} item)`;

                const subtotalEl = document.querySelector('#cart-subtotal');
                if (subtotalEl) subtotalEl.textContent = formatPrice(result.subtotal);
                const totalEl = document.querySelector('#cart-total');
                if (totalEl) totalEl.textContent = formatPrice(result.total);

                // Shipping fee & message
                const shipping = document.querySelector('#shipping-fee');
                if (shipping) {
                    shipping.textContent = result.shippingFee === 0 ? 'Gratis' : formatPrice(result.shippingFee);
                    shipping.classList.toggle('free-shipping', result.shippingFee === 0);
                }
                const shippingMsg = document.querySelector('[data-shipping-message]');
                if (shippingMsg) shippingMsg.textContent = result.shippingMessage;

                // Dynamic Free Shipping Progress Bar
                const progressBar = document.querySelector('#shipping-bar-fill');
                if (progressBar) {
                    const pct = Math.min(100, Math.max(5, Math.round((result.subtotal / 100000) * 100)));
                    progressBar.style.width = `${pct}%`;
                    progressBar.classList.toggle('is-free', result.subtotal >= 100000);
                }

                // Navbar badge bump
                const cartCount = document.querySelector('#cart-count');
                if (cartCount) {
                    cartCount.textContent = result.cartCount;
                    cartCount.classList.remove('cart-bump');
                    void cartCount.offsetWidth;
                    cartCount.classList.add('cart-bump');
                }

                showMessage(result.message || 'Keranjang diperbarui.');
            } catch (error) {
                showMessage(error.message, 'error');
            }
        });
    });

    // ── Cart Item Removal Animation ──
    document.querySelectorAll('.cart-item form[action*="cart/items"]').forEach((form) => {
        // Find forms that are for deletion
        if (form.querySelector('input[name="_method"][value="DELETE"]')) {
            form.addEventListener('submit', (event) => {
                const item = form.closest('.cart-item');
                const productName = item?.querySelector('.cart-item-name')?.textContent?.trim() || 'produk';
                if (!confirm(`Hapus "${productName}" dari keranjang?`)) {
                    event.preventDefault();
                    return;
                }
                if (item) {
                    item.classList.add('is-removing');
                }
            });
        }
    });

    // ── Flash Sale Dynamic Countdown Timer ──
    function initFlashSaleCountdown() {
        const countdownEl = document.querySelector('.countdown');
        if (!countdownEl) return;

        const hoursEl = countdownEl.querySelector('.time-box-hours') || countdownEl.children[0];
        const minutesEl = countdownEl.querySelector('.time-box-minutes') || countdownEl.children[2];
        const secondsEl = countdownEl.querySelector('.time-box-seconds') || countdownEl.children[4];

        if (!hoursEl || !minutesEl || !secondsEl) return;

        function updateCountdown() {
            const now = new Date();
            // Count down towards end of the current day
            const endOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
            const diffSeconds = Math.max(0, Math.floor((endOfDay.getTime() - now.getTime()) / 1000));

            const hours = String(Math.floor(diffSeconds / 3600)).padStart(2, '0');
            const minutes = String(Math.floor((diffSeconds % 3600) / 60)).padStart(2, '0');
            const seconds = String(diffSeconds % 60).padStart(2, '0');

            hoursEl.textContent = hours;
            minutesEl.textContent = minutes;
            secondsEl.textContent = seconds;
        }

        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
    initFlashSaleCountdown();

    // ── Copy Promo Code (`HALO15`) ──
    const promoBox = document.querySelector('#copy-promo') || document.querySelector('.promo-code');
    if (promoBox) {
        promoBox.addEventListener('click', async () => {
            const code = promoBox.dataset.code || 'HALO15';
            try {
                await navigator.clipboard.writeText(code);
                showMessage(`Kode promo "${code}" berhasil disalin! 📋`, 'success');
                const strong = promoBox.querySelector('strong');
                if (strong) {
                    const original = strong.textContent;
                    strong.textContent = 'TERSALIN! ✓';
                    setTimeout(() => { strong.textContent = original; }, 1600);
                }
            } catch {
                showMessage(`Gunakan kode promo: ${code}`);
            }
        });
    }

    // ── Category Pills Filter on Storefront ──
    const cards = [...document.querySelectorAll('.product-card')];
    const filters = [...document.querySelectorAll('.filter-btn')];
    const searchInput = document.querySelector('#product-search');
    let activeCategory = 'semua';

    function filterProducts() {
        const query = searchInput?.value.trim().toLocaleLowerCase('id') || '';
        let visible = 0;

        cards.forEach((card) => {
            const categoryMatch = activeCategory === 'semua' || card.dataset.category === activeCategory;
            const text = `${card.dataset.name} ${card.dataset.category} ${card.dataset.categoryName}`.toLocaleLowerCase('id');
            const visibleCard = categoryMatch && text.includes(query);
            card.hidden = !visibleCard;
            if (visibleCard) visible += 1;
        });

        let empty = document.querySelector('.empty-state');
        if (!visible && !empty) {
            empty = document.createElement('p');
            empty.className = 'empty-state';
            empty.textContent = 'Produk tidak ditemukan. Coba kata kunci atau kategori lain.';
            document.querySelector('#product-grid')?.append(empty);
        } else if (visible && empty) {
            empty.remove();
        }
    }

    filters.forEach((button) => {
        button.addEventListener('click', () => {
            activeCategory = button.dataset.filter;
            filters.forEach((item) => item.classList.toggle('active', item === button));
            filterProducts();
        });
    });

    if (searchInput) {
        searchInput.addEventListener('input', filterProducts);
    }

    // ── Wishlist Hearts with LocalStorage ──
    const WISHLIST_KEY = 'rb_wishlist_items';
    let savedWishlist = [];
    try {
        savedWishlist = JSON.parse(localStorage.getItem(WISHLIST_KEY) || '[]');
    } catch {
        savedWishlist = [];
    }

    document.querySelectorAll('.favorite-btn').forEach((button) => {
        const card = button.closest('.product-card');
        const productName = card?.dataset.name || 'Produk';

        // Restore initial state from localStorage
        if (savedWishlist.includes(productName)) {
            button.classList.add('active');
            button.textContent = '♥';
            button.setAttribute('aria-pressed', 'true');
        }

        button.addEventListener('click', () => {
            const isActive = button.classList.toggle('active');
            button.textContent = isActive ? '♥' : '♡';
            button.setAttribute('aria-pressed', String(isActive));

            if (isActive) {
                if (!savedWishlist.includes(productName)) savedWishlist.push(productName);
                showMessage(`"${productName}" disimpan ke wishlist ♡`, 'success');
            } else {
                savedWishlist = savedWishlist.filter((name) => name !== productName);
                showMessage(`"${productName}" dihapus dari wishlist.`);
            }

            try {
                localStorage.setItem(WISHLIST_KEY, JSON.stringify(savedWishlist));
            } catch {
                // Ignore storage limits
            }
        });
    });

    // ── Newsletter Form Interaction ──
    document.querySelector('#newsletter-form')?.addEventListener('submit', (event) => {
        event.preventDefault();
        showMessage('Terima kasih sudah berlangganan update promo kami! ✨', 'success');
        event.currentTarget.reset();
    });

    // ── Payment Option Radio Selection (Checkout) ──
    document.querySelectorAll('.payment-option input').forEach((input) => {
        input.addEventListener('change', () => {
            document.querySelectorAll('.payment-option').forEach((option) =>
                option.classList.toggle('selected', option.contains(input))
            );
        });
    });

    // ── Admin Products Instant Client-Side Filter ──
    const adminProductSearch = document.querySelector('#product-search-filter');
    const adminProductTable = document.querySelector('.product-admin-table');
    if (adminProductSearch && adminProductTable) {
        const rows = [...adminProductTable.querySelectorAll('tbody tr')];
        adminProductSearch.addEventListener('input', () => {
            const term = adminProductSearch.value.trim().toLowerCase();
            let matches = 0;
            rows.forEach((row) => {
                if (row.classList.contains('product-admin-empty')) return;
                const text = row.textContent.toLowerCase();
                const matched = text.includes(term);
                row.hidden = !matched;
                if (matched) matches += 1;
            });
        });
    }

    // ── Smooth Scroll Reveal Animation ──
    const observer = 'IntersectionObserver' in window ? new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.08 }) : null;

    document.querySelectorAll('.product-card, .category-card, .benefit').forEach((element) => {
        if (observer) observer.observe(element);
        else element.classList.add('is-visible');
    });

    // ── Initial Toast from Server Session ──
    const initialMessage = getToast()?.dataset.message;
    if (initialMessage && initialMessage.trim()) {
        showMessage(initialMessage.trim());
    }
})();
