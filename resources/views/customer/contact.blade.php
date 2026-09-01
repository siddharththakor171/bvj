@extends('layouts.customer')

@section('title', 'Contact Us & Flagship Showroom Visit')
@section('meta_description', 'Visit the B V JEWELLERS flagship showroom in Zaveri Bazaar, Mumbai. Book a private consultation or enquire via WhatsApp (+91 98765 43210).')

@section('content')

<!-- Header Banner -->
<div style="background: radial-gradient(circle at 50% 50%, rgba(212, 175, 55, 0.08) 0%, rgba(18, 17, 14, 0.98) 100%), var(--onyx-deep); color: #ffffff; padding: 4rem 0; border-bottom: 1px solid var(--onyx-border);">
    <div class="container-luxury" style="text-align: center;">
        <span class="section-subtitle" style="color: var(--gold-bright);">Showroom & Private Consultations</span>
        <h1 class="section-title" style="color: #ffffff; font-size: 2.85rem; margin-bottom: 0.75rem;">
            Visit B V JEWELLERS Showroom
        </h1>
        <p class="section-desc" style="max-width: 600px; margin: 0 auto; color: #c4bfa5;">
            Experience our master jewellery vault in person. Our diamond specialists and karigars are ready to assist you.
        </p>
    </div>
</div>

<div class="container-luxury" style="padding-top: 4rem; padding-bottom: 6rem;">
    <div class="contact-grid">
        <!-- Left: Showroom Info Card -->
        <div class="contact-info-card">
            <h2 class="info-title">Flagship Showroom</h2>
            <p class="info-sub">
                Located in Mumbai's historic Gold Souk, offering private viewing suites for bridal trousseau selection and bullion investment.
            </p>

            <div class="contact-detail-item">
                <div class="contact-icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                </div>
                <div>
                    <div class="item-title">Showroom Address</div>
                    <div class="item-text">
                        B V JEWELLERS Heritage House,<br>
                        Gold Souk Arcade, Zaveri Bazaar,<br>
                        Diamond District, Mumbai - 400002, Maharashtra, India
                    </div>
                </div>
            </div>

            <div class="contact-detail-item">
                <div class="contact-icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                </div>
                <div>
                    <div class="item-title">Showroom Timings</div>
                    <div class="item-text">
                        <strong>Monday – Saturday:</strong> 10:30 AM – 8:30 PM<br>
                        <strong>Sunday:</strong> 11:00 AM – 7:00 PM<br>
                        <span style="color: var(--gold-primary); font-weight: 600;">(Private Bridal Suites by Appointment)</span>
                    </div>
                </div>
            </div>

            <div class="contact-detail-item">
                <div class="contact-icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                </div>
                <div>
                    <div class="item-title">Direct Showroom Lines</div>
                    <div class="item-text">
                        Phone: <a href="tel:+919876543210" style="color: var(--text-primary); font-weight: 700;">+91 98765 43210</a><br>
                        Email: <a href="mailto:admin@bvjewellers.com" style="color: var(--gold-primary); font-weight: 600;">admin@bvjewellers.com</a>
                    </div>
                </div>
            </div>

            <div style="margin-top: 2rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <a href="https://wa.me/919876543210?text={{ urlencode('Hello B V Jewellers, I would like to enquire about visiting your showroom.') }}" target="_blank" rel="noopener noreferrer" class="btn-whatsapp-large" style="width: 100%;">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
                    Direct WhatsApp Message
                </a>
                <a href="tel:+919876543210" class="btn-gold-outline" style="width: 100%;">
                    Call Showroom Desk
                </a>
            </div>
        </div>

        <!-- Right: VIP Consultation Booking Form -->
        <div class="contact-form-card">
            <h2 class="contact-form-title">Schedule a Private Viewing</h2>
            <p class="contact-form-sub">
                Our jewellery curator will prepare your selected pieces and reserve a private consultation suite.
            </p>

            <form method="POST" action="{{ route('catalogue.inquiry') }}">
                @csrf
                <div class="form-group-lux">
                    <label class="form-label-lux">Full Name</label>
                    <input type="text" name="customer_name" class="luxury-input" placeholder="Your full name" required>
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

                <div class="form-row-2">
                    <div class="form-group-lux">
                        <label class="form-label-lux">Interested Category</label>
                        <select name="interested_category" class="luxury-input" required>
                            <option value="Bridal Jewellery Set">Bridal Jewellery Set</option>
                            <option value="Solitaire Diamond Engagement Ring">Solitaire Diamond Engagement Ring</option>
                            <option value="Heritage Polki & Kundan Choker">Heritage Polki & Kundan Choker</option>
                            <option value="Temple Gold Bangles">Temple Gold Bangles</option>
                            <option value="24K Bullion Investment Coins & Bars">24K Bullion Investment Coins & Bars</option>
                            <option value="925 Pure Silverware">925 Pure Silverware</option>
                            <option value="Custom Heirloom Remodeling">Custom Heirloom Remodeling</option>
                        </select>
                    </div>

                    <div class="form-group-lux">
                        <label class="form-label-lux">Budget Preference</label>
                        <select name="budget_range" class="luxury-input">
                            <option value="Under ₹1,00,000">Under ₹1,00,000</option>
                            <option value="₹1,00,000 - ₹3,00,000" selected>₹1,00,000 - ₹3,00,000</option>
                            <option value="₹3,00,000 - ₹6,00,000">₹3,00,000 - ₹6,00,000</option>
                            <option value="₹6,00,000 - ₹15,00,000">₹6,00,000 - ₹15,00,000</option>
                            <option value="₹15,00,000+ (Bridal Trousseau)">₹15,00,000+ (Bridal Trousseau)</option>
                        </select>
                    </div>
                </div>

                <div class="form-group-lux">
                    <label class="form-label-lux">Preferred Date & Design Notes</label>
                    <textarea name="message" rows="3" class="luxury-input" placeholder="Mention your preferred viewing date, specific SKU from the catalogue, or custom design specifications..."></textarea>
                </div>

                <button type="submit" class="btn-gold-primary" style="width: 100%; padding: 0.9rem; font-size: 0.9rem; margin-top: 0.5rem;">
                    Submit Showroom Consultation Request
                </button>
            </form>
        </div>
    </div>

    <!-- Frequently Asked Questions -->
    <div style="margin-top: 6rem;">
        <div class="section-header">
            <span class="section-subtitle">Showroom FAQ</span>
            <h2 class="section-title">Common Inquiries</h2>
            <div class="gold-divider-center"></div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; max-width: 960px; margin: 0 auto;">
            <div style="background: var(--bg-surface); border: 1px solid var(--gold-border-light); border-radius: var(--radius-md); padding: 1.5rem;">
                <h3 style="font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                    How do I verify the BIS Hallmark HUID?
                </h3>
                <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.6;">
                    Every piece contains a 6-digit laser alphanumeric code. You can download the government "BIS Care App" and enter the HUID to verify jeweller details, assay centre, and gold purity.
                </p>
            </div>

            <div style="background: var(--bg-surface); border: 1px solid var(--gold-border-light); border-radius: var(--radius-md); padding: 1.5rem;">
                <h3 style="font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                    Can I purchase or place orders online?
                </h3>
                <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.6;">
                    BVJ operates as an exclusive physical showroom catalogue. To purchase, verify purity in person, or arrange insured courier pickup, simply connect with our team via Call or WhatsApp.
                </p>
            </div>

            <div style="background: var(--bg-surface); border: 1px solid var(--gold-border-light); border-radius: var(--radius-md); padding: 1.5rem;">
                <h3 style="font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                    Can I customize a design seen in the catalogue?
                </h3>
                <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.6;">
                    Yes! Our master karigar atelier can customize gross weight, metal purity (18K/22K), gemstone accents (Ruby/Emerald/Sapphire), or ring sizes to your exact specifications.
                </p>
            </div>

            <div style="background: var(--bg-surface); border: 1px solid var(--gold-border-light); border-radius: var(--radius-md); padding: 1.5rem;">
                <h3 style="font-family: var(--font-heading); font-size: 1rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.5rem;">
                    Do you accept old gold exchange?
                </h3>
                <p style="font-size: 0.86rem; color: var(--text-secondary); line-height: 1.6;">
                    We offer instant computerized XRF melting and assay evaluation with zero deduction on gold content against the prevailing live 24K bullion market rate.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection
