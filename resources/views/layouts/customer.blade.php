<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Exclusive Jewellery Catalogue') | B V JEWELLERS</title>
    <meta name="description" content="@yield('meta_description', 'Discover handcrafted 22K/24K BIS Hallmarked gold, uncut Polki, certified solitaire diamonds, and antique temple jewellery at B V JEWELLERS since 1984.')">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body>

    @php
        $rates = \App\Models\MetalRate::all();
    @endphp

    <!-- Top Bullion Ticker Bar -->
    <div class="top-ticker-bar">
        <div class="container-luxury top-ticker-inner">
            <div class="ticker-rates-wrap">
                <span style="color: var(--gold-bright); font-weight: 700; display: inline-flex; align-items: center; gap: 0.35rem;">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="10"/></svg>
                    TODAY'S RATES:
                </span>
                @foreach($rates as $rate)
                    <div class="ticker-rate-badge">
                        <span class="metal">{{ $rate->metal_name }}:</span>
                        <span class="price">₹{{ number_format($rate->rate_per_gram, 2) }}/{{ $rate->unit }}</span>
                        @if($rate->trend === 'up')
                            <span class="trend-up">&#9650;</span>
                        @elseif($rate->trend === 'down')
                            <span class="trend-down">&#9660;</span>
                        @endif
                    </div>
                @endforeach
            </div>

            <div class="top-contact-links">
                <a href="tel:+919876543210" title="Call Showroom">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    +91 98765 43210
                </a>
                <span>&bull;</span>
                <span style="color: #a8a29e;">Showroom: 10:30 AM - 8:30 PM</span>
            </div>
        </div>
    </div>

    <!-- Luxury Navigation Header -->
    <header class="luxury-header">
        <div class="container-luxury navbar-inner">
            <!-- Brand Link -->
            <a href="{{ route('home') }}" class="brand-link">
                <div class="brand-crest-box">BV</div>
                <div class="brand-name-group">
                    <span class="brand-title">B V JEWELLERS</span>
                    <span class="brand-subtitle">Heritage & Bullion Since 1984</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <nav>
                <ul class="nav-menu-desktop">
                    <li><a href="{{ route('home') }}" class="nav-menu-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
                    <li><a href="{{ route('catalogue.index') }}" class="nav-menu-link {{ request()->routeIs('catalogue.*') ? 'active' : '' }}">Catalogue</a></li>
                    <li><a href="{{ route('collections') }}" class="nav-menu-link {{ request()->routeIs('collections') ? 'active' : '' }}">Collections</a></li>
                    <li><a href="{{ route('about') }}" class="nav-menu-link {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a></li>
                    <li><a href="{{ route('contact') }}" class="nav-menu-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
                </ul>
            </nav>

            <!-- Nav Actions -->
            <div class="nav-actions-wrap">
                <a href="https://wa.me/919876543210?text={{ urlencode('Hello B V Jewellers, I would like to enquire about your jewellery catalogue.') }}" target="_blank" rel="noopener noreferrer" class="btn-nav-whatsapp">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    WhatsApp
                </a>
                <a href="{{ route('contact') }}" class="btn-nav-outline">
                    Showroom
                </a>
                <button type="button" class="mobile-menu-btn" onclick="toggleMobileDrawer()" aria-label="Toggle navigation">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
            </div>
        </div>
    </header>

    <!-- Mobile Drawer -->
    <div class="mobile-drawer-overlay" id="mobileDrawerOverlay" onclick="toggleMobileDrawer()"></div>
    <aside class="mobile-nav-drawer" id="mobileDrawer">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div class="brand-link">
                <div class="brand-crest-box" style="width: 36px; height: 36px; font-size: 1rem;">BV</div>
                <span class="brand-title" style="font-size: 1.05rem;">B V JEWELLERS</span>
            </div>
            <button type="button" class="modal-lux-close" onclick="toggleMobileDrawer()">&times;</button>
        </div>

        <nav style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('home') }}" class="nav-menu-link {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
            <a href="{{ route('catalogue.index') }}" class="nav-menu-link {{ request()->routeIs('catalogue.*') ? 'active' : '' }}">Catalogue</a>
            <a href="{{ route('collections') }}" class="nav-menu-link {{ request()->routeIs('collections') ? 'active' : '' }}">Collections</a>
            <a href="{{ route('about') }}" class="nav-menu-link {{ request()->routeIs('about') ? 'active' : '' }}">About Us</a>
            <a href="{{ route('contact') }}" class="nav-menu-link {{ request()->routeIs('contact') ? 'active' : '' }}">Contact & Showroom</a>
        </nav>

        <div style="margin-top: auto; display: flex; flex-direction: column; gap: 0.75rem;">
            <a href="https://wa.me/919876543210" target="_blank" class="btn-whatsapp-large" style="padding: 0.75rem; font-size: 0.85rem;">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                WhatsApp Enquiry
            </a>
            <a href="tel:+919876543210" class="btn-gold-outline" style="padding: 0.75rem; font-size: 0.85rem;">
                Call Showroom
            </a>
        </div>
    </aside>

    <!-- Success Flash Banner -->
    @if(session('success'))
        <div class="container-luxury" style="margin-top: 1.5rem;">
            <div class="alert-lux-success">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <div>{{ session('success') }}</div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- VIP Consultation Modal -->
    <div class="modal-lux-overlay" id="consultationModal">
        <div class="modal-lux-card">
            <div class="modal-lux-header">
                <h3 id="modalConsultationTitle">Book Showroom Consultation</h3>
                <button type="button" class="modal-lux-close" onclick="closeConsultationModal()">&times;</button>
            </div>
            <div class="modal-lux-body">
                <p style="font-size: 0.84rem; color: var(--text-secondary); margin-bottom: 1.25rem;">
                    Leave your contact details and our diamond & bullion specialist will prepare the selected pieces for your private viewing.
                </p>

                <form method="POST" action="{{ route('catalogue.inquiry') }}">
                    @csrf
                    <input type="hidden" name="interested_category" id="inquiryCategory" value="General Showroom Enquiry">
                    
                    <div class="form-group-lux">
                        <label class="form-label-lux">Full Name</label>
                        <input type="text" name="customer_name" class="luxury-input" placeholder="Your name" required>
                    </div>

                    <div class="form-row-2">
                        <div class="form-group-lux">
                            <label class="form-label-lux">Phone Number</label>
                            <input type="tel" name="customer_phone" class="luxury-input" placeholder="+91 ..." required>
                        </div>
                        <div class="form-group-lux">
                            <label class="form-label-lux">Email Address</label>
                            <input type="email" name="customer_email" class="luxury-input" placeholder="name@example.com">
                        </div>
                    </div>

                    <div class="form-group-lux">
                        <label class="form-label-lux">Budget Preference (Optional)</label>
                        <select name="budget_range" class="luxury-input">
                            <option value="Under ₹1,00,000">Under ₹1,00,000</option>
                            <option value="₹1,00,000 - ₹3,00,000" selected>₹1,00,000 - ₹3,00,000</option>
                            <option value="₹3,00,000 - ₹6,00,000">₹3,00,000 - ₹6,00,000</option>
                            <option value="₹6,00,000 - ₹15,00,000">₹6,00,000 - ₹15,00,000</option>
                            <option value="₹15,00,000+ (High Bridal)">₹15,00,000+ (High Bridal)</option>
                        </select>
                    </div>

                    <div class="form-group-lux">
                        <label class="form-label-lux">Specific Jewellery Piece / Requirement</label>
                        <textarea name="message" id="inquiryMessage" rows="2" class="luxury-input" placeholder="Mention item name or custom design requirement..."></textarea>
                    </div>

                    <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
                        <button type="button" class="btn-gold-outline" style="padding: 0.65rem 1.25rem;" onclick="closeConsultationModal()">Cancel</button>
                        <button type="submit" class="btn-gold-primary" style="padding: 0.65rem 1.5rem;">Submit Enquiry</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Luxury Footer -->
    <footer class="luxury-footer">
        <div class="container-luxury">
            <div class="footer-top-grid">
                <!-- Brand Info -->
                <div>
                    <div class="footer-brand-title">B V JEWELLERS</div>
                    <div class="footer-brand-tag">Fine Heritage Jewellery & Bullion</div>
                    <p class="footer-brand-bio">
                        Crafting timeless bridal jewellery, certified natural diamonds, uncut Polki necklaces, and 999 investment bullion since 1984. Every piece is hallmarked with 100% laser HUID authenticity.
                    </p>
                    <div style="display: flex; gap: 0.75rem;">
                        <a href="https://wa.me/919876543210" target="_blank" class="btn-nav-whatsapp" style="padding: 0.45rem 0.9rem;">
                            Direct WhatsApp
                        </a>
                        <a href="tel:+919876543210" class="btn-nav-outline" style="padding: 0.45rem 0.9rem; color: #ffffff; border-color: rgba(255,255,255,0.3);">
                            +91 98765 43210
                        </a>
                    </div>
                </div>

                <!-- Quick Navigation -->
                <div>
                    <div class="footer-col-title">Catalogue</div>
                    <ul class="footer-links-list">
                        <li><a href="{{ route('catalogue.index') }}">All Vault Items</a></li>
                        <li><a href="{{ route('catalogue.index', ['category' => 'Necklaces']) }}">Bridal Necklaces</a></li>
                        <li><a href="{{ route('catalogue.index', ['category' => 'Rings']) }}">Solitaire Rings</a></li>
                        <li><a href="{{ route('catalogue.index', ['category' => 'Bangles & Bracelets']) }}">Heritage Bangles</a></li>
                        <li><a href="{{ route('catalogue.index', ['category' => 'Coins & Bars']) }}">24K Minted Bullion</a></li>
                    </ul>
                </div>

                <!-- Showroom & Hours -->
                <div>
                    <div class="footer-col-title">Showroom</div>
                    <ul class="footer-links-list">
                        <li style="color: #a8a29e;">
                            <strong>Flagship Showroom:</strong><br>
                            Gold Souk, Zaveri Bazaar,<br>
                            Diamond District, Mumbai 400002
                        </li>
                        <li style="color: #a8a29e; margin-top: 0.5rem;">
                            <strong>Showroom Hours:</strong><br>
                            Mon - Sat: 10:30 AM - 8:30 PM<br>
                            Sunday: 11:00 AM - 7:00 PM
                        </li>
                    </ul>
                </div>

                <!-- BIS Hallmark & Statutory Compliance -->
                <div>
                    <div class="footer-col-title">Certification & Trust</div>
                    <div class="footer-compliance-box">
                        <div class="footer-compliance-item">
                            <div class="compliance-label">BIS Hallmark Certificate</div>
                            <div class="compliance-val">HM-IND-2026-928810-BVJ</div>
                        </div>
                        <div class="footer-compliance-item">
                            <div class="compliance-label">GSTIN Tax Registration</div>
                            <div class="compliance-val">27AAAAA0000A1Z5</div>
                        </div>
                        <div class="footer-compliance-item">
                            <div class="compliance-label">Assay & Purity Guarantee</div>
                            <div class="compliance-val" style="color: #34d399; font-size: 0.75rem;">100% HUID Laser Verified</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Bottom -->
            <div class="footer-bottom-bar">
                <div>
                    &copy; {{ date('Y') }} B V JEWELLERS. All Rights Reserved. Master of Fine Handcrafted Jewellery.
                </div>
                <div style="display: flex; gap: 1.5rem; align-items: center;">
                    <a href="{{ route('about') }}" style="color: #a8a29e;">About Us</a>
                    <a href="{{ route('contact') }}" style="color: #a8a29e;">Contact</a>
                    <a href="{{ route('login') }}" style="color: rgba(212, 175, 55, 0.6); font-size: 0.72rem;">Admin Vault</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/919876543210?text={{ urlencode('Hello B V Jewellers, I would like to enquire about your jewellery.') }}" target="_blank" rel="noopener noreferrer" class="whatsapp-float-btn" title="WhatsApp Enquiry" aria-label="WhatsApp">
        <svg width="26" height="26" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
    </a>

    <!-- Script for mobile toggle and consultation modal -->
    <script>
        function toggleMobileDrawer() {
            const drawer = document.getElementById('mobileDrawer');
            const overlay = document.getElementById('mobileDrawerOverlay');
            if (drawer && overlay) {
                drawer.classList.toggle('open');
                overlay.classList.toggle('open');
            }
        }

        function openConsultationModal(category = 'General Showroom Enquiry', message = '') {
            const modal = document.getElementById('consultationModal');
            const catInput = document.getElementById('inquiryCategory');
            const msgInput = document.getElementById('inquiryMessage');
            if (modal) {
                if (catInput) catInput.value = category;
                if (msgInput && message) msgInput.value = message;
                modal.classList.add('open');
            }
        }

        function closeConsultationModal() {
            const modal = document.getElementById('consultationModal');
            if (modal) {
                modal.classList.remove('open');
            }
        }

        window.addEventListener('click', function(e) {
            const modal = document.getElementById('consultationModal');
            if (e.target === modal) {
                closeConsultationModal();
            }
        });

        /* ── Header scroll shadow ── */
        (function () {
            const header = document.querySelector('.luxury-header');
            if (!header) return;
            const onScroll = () => {
                header.classList.toggle('scrolled', window.scrollY > 20);
            };
            window.addEventListener('scroll', onScroll, { passive: true });
        })();

        /* ── Scroll reveal (IntersectionObserver) ── */
        (function () {
            const items = document.querySelectorAll('.reveal');
            if (!items.length) return;
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });
            items.forEach(el => io.observe(el));
        })();

        /* ── Mobile filter sidebar toggle ── */
        function toggleFilterSidebar() {
            const btn = document.getElementById('filterToggleBtn');
            const body = document.getElementById('filterSidebarBody');
            if (!btn || !body) return;
            const isOpen = btn.classList.toggle('open');
            if (isOpen) {
                body.style.maxHeight = body.scrollHeight + 'px';
                body.style.opacity = '1';
            } else {
                body.style.maxHeight = '0';
                body.style.opacity = '0';
            }
        }

        /* Initialise filter sidebar open on desktop */
        (function () {
            const body = document.getElementById('filterSidebarBody');
            if (!body) return;
            if (window.innerWidth > 768) {
                body.style.maxHeight = 'none';
                body.style.opacity = '1';
            } else {
                body.style.maxHeight = '0';
                body.style.opacity = '0';
            }
        })();
    </script>

    @stack('scripts')
</body>
</html>
