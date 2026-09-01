@extends('layouts.customer')

@section('title', 'About Us & Brand Heritage Since 1984')
@section('meta_description', 'Discover the four-decade legacy of B V JEWELLERS: Master craftsmen of 100% BIS hallmarked gold, certified diamonds, and investment bullion.')

@section('content')

<!-- Header Banner -->
<div class="about-hero">
    <div class="container-luxury" style="max-width: 800px;">
        <span class="section-subtitle" style="color: var(--gold-bright);">Our Heritage & Legacy</span>
        <h1 class="section-title" style="color: #ffffff; font-size: 3rem; margin-bottom: 1rem;">
            Four Decades of Purity & Timeless Craftsmanship
        </h1>
        <p class="section-desc" style="color: #d1cbc1; font-size: 1.05rem;">
            Founded in 1984, B V JEWELLERS has stood as an enduring symbol of authentic gold purity, artisan karigar mastery, and bullion integrity.
        </p>
    </div>
</div>

<div class="container-luxury" style="padding-top: 4.5rem; padding-bottom: 6rem;">
    <!-- Story Grid -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 3.5rem; align-items: center; margin-bottom: 5.5rem;">
        <div>
            <span class="section-subtitle">Our Journey</span>
            <h2 class="section-title" style="font-size: 2.25rem; margin-bottom: 1.25rem;">
                Crafting Legacies, Preserving Heirloom Artistry
            </h2>
            <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.7; margin-bottom: 1.25rem;">
                From our foundational showroom in the heart of Mumbai's historic jewellery district, B V JEWELLERS has grown into one of the most trusted names in hallmarked gold and certified diamonds.
            </p>
            <p style="font-size: 0.95rem; color: var(--text-secondary); line-height: 1.7; margin-bottom: 1.5rem;">
                Every creation that enters our vault is subjected to rigorous XRF spectrometer assaying, laser-engraved with BIS Hallmark Unique Identification (HUID), and crafted by third-generation master karigars specializing in Jadau, Polki, and Nakshi antique temple jewellery.
            </p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; border-top: 1px solid var(--gold-border-light); padding-top: 1.5rem;">
                <div>
                    <div style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 800; color: var(--gold-primary);">40+ Years</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Of Continuous Bullion Trust</div>
                </div>
                <div>
                    <div style="font-family: var(--font-heading); font-size: 1.6rem; font-weight: 800; color: var(--gold-primary);">100% HUID</div>
                    <div style="font-size: 0.78rem; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Laser Verified Purity</div>
                </div>
            </div>
        </div>

        <div style="position: relative;">
            <div style="background: var(--onyx-card); border-radius: var(--radius-lg); border: 1px solid var(--gold-border); padding: 1.5rem; box-shadow: 0 15px 35px rgba(0,0,0,0.1);">
                <img src="https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=800&auto=format&fit=crop&q=80" alt="BVJ Heritage Jewellery" style="width: 100%; height: 420px; object-fit: cover; border-radius: var(--radius-md);" loading="lazy">
            </div>
        </div>
    </div>

    <!-- The 4 Pillars Section -->
    <div class="section-header">
        <span class="section-subtitle">Core Pillars</span>
        <h2 class="section-title">The BVJ Standard of Excellence</h2>
        <p class="section-desc">
            Why discerning families and connoisseurs choose B V JEWELLERS for their most cherished milestones.
        </p>
        <div class="gold-divider-center"></div>
    </div>

    <div class="about-feature-grid" style="margin-bottom: 5.5rem;">
        <div class="about-feature-card">
            <div class="about-icon-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path></svg>
            </div>
            <h3 class="about-feature-title">100% BIS Hallmarking</h3>
            <p class="about-feature-desc">
                Every gold ornament bears the BIS logo, 6-digit HUID code, and fineness stamp (22K916 / 18K750) ensuring complete consumer protection and buyback assurance.
            </p>
        </div>

        <div class="about-feature-card">
            <div class="about-icon-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="6 3 18 3 22 9 12 22 2 9 6 3"></polygon></svg>
            </div>
            <h3 class="about-feature-title">Ethical Diamond Grading</h3>
            <p class="about-feature-desc">
                Our diamonds undergo rigorous 4C evaluation (Cut, Clarity, Color, Carat) with VVS1-EF clarity standards and conflict-free natural provenance.
            </p>
        </div>

        <div class="about-feature-card">
            <div class="about-icon-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
            </div>
            <h3 class="about-feature-title">Transparent Bullion Rates</h3>
            <p class="about-feature-desc">
                Our daily metal board reflects real-time bullion market rates with complete transparency in net metal weights and karigar making charges.
            </p>
        </div>

        <div class="about-feature-card">
            <div class="about-icon-wrap">
                <svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line><polyline points="10 9 9 9 8 9"></polyline></svg>
            </div>
            <h3 class="about-feature-title">Lifetime Karigar Care</h3>
            <p class="about-feature-desc">
                We provide complimentary ultrasonic cleaning, rhodium refinishing, prong tightening, and lifetime valuation certificates for all BVJ creations.
            </p>
        </div>
    </div>

    <!-- Statutory Credentials -->
    <div style="background: var(--onyx-deep); color: #ffffff; border-radius: var(--radius-lg); border: 1px solid var(--onyx-border); padding: 3rem; text-align: center;">
        <span class="section-subtitle" style="color: var(--gold-bright);">Statutory Trust</span>
        <h2 class="section-title" style="color: #ffffff; font-size: 2rem; margin-bottom: 0.75rem;">
            Official Jeweller Registration & BIS Certification
        </h2>
        <p class="section-desc" style="color: #b5afa4; max-width: 600px; margin: 0 auto 2rem auto;">
            Registered under the Bureau of Indian Standards (BIS) Hallmarking Scheme & Central Board of Indirect Taxes and Customs.
        </p>

        <div style="display: flex; justify-content: center; gap: 2rem; flex-wrap: wrap;">
            <div style="background: var(--onyx-card); border: 1px solid var(--onyx-border); border-radius: var(--radius-md); padding: 1.25rem 2rem; text-align: left;">
                <div style="font-size: 0.7rem; color: var(--gold-bright); text-transform: uppercase; font-weight: 700;">BIS Hallmark Certificate</div>
                <div style="font-family: monospace; font-size: 1rem; color: #ffffff; font-weight: 700; margin-top: 0.2rem;">HM-IND-2026-928810-BVJ</div>
            </div>

            <div style="background: var(--onyx-card); border: 1px solid var(--onyx-border); border-radius: var(--radius-md); padding: 1.25rem 2rem; text-align: left;">
                <div style="font-size: 0.7rem; color: var(--gold-bright); text-transform: uppercase; font-weight: 700;">GSTIN & Tax Registration</div>
                <div style="font-family: monospace; font-size: 1rem; color: #ffffff; font-weight: 700; margin-top: 0.2rem;">27AAAAA0000A1Z5</div>
            </div>
        </div>
    </div>
</div>

@endsection
