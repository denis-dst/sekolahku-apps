<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="SekolahKu-Apps — Platform SaaS manajemen sekolah all-in-one. E-Rapor, Presensi Digital, SPP QRIS, BendaharaKu LPJ BOSP, dan WhatsApp Otomatis. Oleh DnD Tech Solutions.">
    <meta name="keywords"
        content="sekolahku, aplikasi sekolah, e-rapor, presensi digital, spp online, bendaharaku, lpj bosp, saas sekolah, dnd tech solutions">
    <meta name="author" content="DnD Tech Solutions">
    <title>SekolahKu-Apps — Platform Cerdas Manajemen Sekolah | DnD Tech Solutions</title>
    
    <!-- Preconnect & DNS-Prefetch for External CDNs -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdn.jsdelivr.net" crossorigin>
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">

    <!-- Non-blocking Google Fonts with display=swap -->
    <link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap">
    </noscript>

    <!-- Non-blocking Bootstrap Icons with Preload -->
    <link rel="preload" as="style" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" media="print" onload="this.media='all'">
    <noscript>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    </noscript>

    <style>
        @font-face {
            font-family: 'bootstrap-icons';
            font-display: swap;
        }

        :root {
            /* Primary green palette */
            --green-50: #f0fdf4;
            --green-100: #dcfce7;
            --green-200: #bbf7d0;
            --green-300: #86efac;
            --green-400: #4ade80;
            --green-500: #22c55e;
            --green-600: #16a34a;
            --green-700: #15803d;
            --green-800: #166534;
            --green-900: #14532d;

            /* Neutral */
            --white: #ffffff;
            --gray-50: #f8fafc;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --gray-900: #0f172a;

            /* Accent */
            --emerald: #059669;
            --teal: #0d9488;

            /* Shadows */
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.06), 0 2px 4px -2px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.08), 0 4px 6px -4px rgba(0, 0, 0, 0.06);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
            --shadow-2xl: 0 25px 50px -12px rgba(0, 0, 0, 0.15);
            --shadow-green: 0 10px 30px -5px rgba(22, 163, 74, 0.25);
        }

        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            color: var(--gray-700);
            background: var(--white);
            line-height: 1.7;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Utilities */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 24px;
        }

        .section-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 16px;
            border-radius: 100px;
            font-size: 0.8rem;
            font-weight: 600;
            letter-spacing: 0.03em;
            text-transform: uppercase;
            background: var(--green-50);
            color: var(--green-700);
            border: 1px solid var(--green-200);
        }

        .section-title {
            font-size: clamp(1.8rem, 4vw, 2.6rem);
            font-weight: 800;
            color: var(--gray-900);
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .section-subtitle {
            font-size: 1.05rem;
            color: var(--gray-500);
            max-width: 600px;
            line-height: 1.7;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 32px;
            border-radius: 12px;
            font-size: 0.95rem;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            letter-spacing: 0.01em;
        }

        .btn-primary {
            background: var(--green-700);
            color: var(--white);
            box-shadow: 0 4px 12px rgba(21, 128, 61, 0.25);
        }

        .btn-primary:hover {
            background: var(--green-800);
            transform: translateY(-2px);
            box-shadow: 0 14px 35px -5px rgba(21, 128, 61, 0.35);
        }

        .btn-outline {
            background: var(--white);
            color: var(--green-700);
            border: 2px solid var(--green-200);
        }

        .btn-outline:hover {
            background: var(--green-50);
            border-color: var(--green-400);
            transform: translateY(-2px);
        }

        .btn-white {
            background: var(--white);
            color: var(--green-700);
            box-shadow: var(--shadow-lg);
        }

        .btn-white:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-xl);
        }

        .btn-sm {
            padding: 10px 22px;
            font-size: 0.85rem;
            border-radius: 10px;
        }

        /* ========== NAVBAR ========== */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            padding: 16px 0;
            transition: all 0.4s ease;
            background: transparent;
        }

        .navbar.scrolled {
            background: rgba(255, 255, 255, 0.92);
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06), 0 0 0 1px rgba(0, 0, 0, 0.03);
            padding: 10px 0;
        }

        .navbar .container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .navbar-logo {
            width: 40px;
            height: 40px;
            background: var(--green-600);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1.2rem;
            font-weight: 900;
            box-shadow: var(--shadow-md);
        }

        .navbar-brand-text {
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--gray-900);
            letter-spacing: -0.01em;
        }

        .navbar-brand-text span {
            color: var(--green-700);
        }

        .navbar-links {
            display: flex;
            align-items: center;
            gap: 8px;
            list-style: none;
        }

        .navbar-links a {
            text-decoration: none;
            color: var(--gray-600);
            font-size: 0.9rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .navbar-links a:hover {
            color: var(--green-700);
            background: var(--green-50);
        }

        .navbar-cta {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            background: none;
            border: none;
            padding: 8px;
        }

        .hamburger span {
            width: 24px;
            height: 2.5px;
            background: var(--gray-700);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* ========== HERO SECTION ========== */
        .hero {
            position: relative;
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 120px 0 80px;
            overflow: hidden;
            background: var(--green-50);
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -40%;
            right: -20%;
            width: 700px;
            height: 700px;
            background: rgba(34, 197, 94, 0.08);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -30%;
            left: -10%;
            width: 500px;
            height: 500px;
            background: rgba(5, 150, 105, 0.06);
            border-radius: 50%;
            pointer-events: none;
        }

        .hero .container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .hero-content {
            opacity: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 18px;
            background: var(--white);
            border: 1px solid var(--green-200);
            border-radius: 100px;
            font-size: 0.82rem;
            font-weight: 600;
            color: var(--green-700);
            margin-bottom: 24px;
            box-shadow: var(--shadow-sm);
        }

        .hero-badge-dot {
            width: 8px;
            height: 8px;
            background: var(--green-500);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        .hero h1 {
            font-size: clamp(2.2rem, 5vw, 3.4rem);
            font-weight: 900;
            line-height: 1.1;
            letter-spacing: -0.03em;
            color: var(--gray-900);
            margin-bottom: 20px;
        }

        .hero h1 .highlight {
            color: var(--green-700);
            background: none;
            -webkit-text-fill-color: var(--green-700);
        }

        .hero p {
            font-size: 1.1rem;
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 500px;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            flex-wrap: wrap;
            margin-bottom: 48px;
        }

        .hero-stats {
            display: flex;
            gap: 40px;
        }

        .hero-stat {
            text-align: left;
        }

        .hero-stat-number {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--green-700);
            letter-spacing: -0.02em;
        }

        .hero-stat-label {
            font-size: 0.82rem;
            color: var(--gray-400);
            font-weight: 500;
        }

        /* Hero Visual */
        .hero-visual {
            position: relative;
            opacity: 1;
        }

        .hero-mockup {
            position: relative;
            background: var(--white);
            border-radius: 20px;
            box-shadow: var(--shadow-2xl);
            overflow: hidden;
            border: 1px solid var(--green-100);
        }

        .mockup-toolbar {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 14px 20px;
            background: var(--gray-50);
            border-bottom: 1px solid var(--gray-100);
        }

        .mockup-dot {
            width: 11px;
            height: 11px;
            border-radius: 50%;
        }

        .mockup-dot:nth-child(1) {
            background: #ef4444;
        }

        .mockup-dot:nth-child(2) {
            background: #f59e0b;
        }

        .mockup-dot:nth-child(3) {
            background: #22c55e;
        }

        .mockup-url {
            flex: 1;
            margin-left: 12px;
            padding: 6px 14px;
            background: var(--white);
            border-radius: 8px;
            font-size: 0.75rem;
            color: var(--gray-400);
            border: 1px solid var(--gray-200);
        }

        .mockup-body {
            padding: 24px;
            background: var(--green-50);
            min-height: 320px;
        }

        .mockup-sidebar {
            display: flex;
            gap: 16px;
        }

        .mockup-nav {
            width: 180px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .mockup-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.78rem;
            font-weight: 500;
            color: var(--gray-500);
            background: transparent;
            transition: all 0.2s;
        }

        .mockup-nav-item.active {
            background: var(--green-600);
            color: var(--white);
            box-shadow: var(--shadow-green);
        }

        .mockup-nav-item i {
            font-size: 0.9rem;
        }

        .mockup-content {
            flex: 1;
        }

        .mockup-kpi-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-bottom: 14px;
        }

        .mockup-kpi {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 14px 16px;
        }

        .mockup-kpi-label {
            font-size: 0.65rem;
            color: var(--gray-400);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.05em;
        }

        .mockup-kpi-value {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--green-700);
            margin-top: 4px;
        }

        .mockup-chart {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 12px;
            padding: 16px;
            min-height: 100px;
        }

        .mockup-chart-title {
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--gray-500);
            margin-bottom: 12px;
        }

        .mockup-chart-bars {
            display: flex;
            align-items: flex-end;
            gap: 8px;
            height: 70px;
        }

        .mockup-bar {
            flex: 1;
            border-radius: 6px 6px 0 0;
            transition: all 0.3s ease;
        }

        /* Floating accent cards */
        .float-card {
            position: absolute;
            background: var(--white);
            border-radius: 14px;
            padding: 14px 18px;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--green-100);
            animation: float 6s ease-in-out infinite;
            z-index: 2;
        }

        .float-card-1 {
            top: -20px;
            right: -20px;
            animation-delay: 0s;
        }

        .float-card-2 {
            bottom: 40px;
            left: -30px;
            animation-delay: 2s;
        }

        .float-card-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            margin-bottom: 8px;
        }

        .float-card-title {
            font-size: 0.78rem;
            font-weight: 700;
            color: var(--gray-800);
        }

        .float-card-desc {
            font-size: 0.68rem;
            color: var(--gray-400);
        }

        /* ========== TRUSTED BY / LOGOS ========== */
        .trusted {
            padding: 60px 0;
            background: var(--white);
            border-top: 1px solid var(--gray-100);
            border-bottom: 1px solid var(--gray-100);
        }

        .trusted-label {
            text-align: center;
            font-size: 0.82rem;
            font-weight: 700;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 28px;
        }

        .trusted-logos {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 48px;
            flex-wrap: wrap;
        }

        .trusted-item {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--gray-600);
            font-weight: 700;
            font-size: 1rem;
            transition: color 0.3s;
        }

        .trusted-item i {
            color: var(--green-700);
            font-size: 1.6rem;
        }

        .trusted-item:hover {
            color: var(--green-800);
        }

        /* ========== FEATURES SECTION ========== */
        .features {
            padding: 100px 0;
            background: var(--white);
        }

        .features-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .features-header .section-subtitle {
            margin: 16px auto 0;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 36px 30px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--green-600);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .feature-card:hover {
            border-color: var(--green-200);
            box-shadow: var(--shadow-xl);
            transform: translateY(-6px);
        }

        .feature-card:hover::before {
            transform: scaleX(1);
        }

        .feature-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 20px;
            background: var(--green-50);
            color: var(--green-600);
            border: 1px solid var(--green-100);
            transition: all 0.3s ease;
        }

        .feature-card:hover .feature-icon {
            background: var(--green-600);
            color: var(--white);
            box-shadow: var(--shadow-green);
        }

        .feature-card h3 {
            font-size: 1.15rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 10px;
        }

        .feature-card p {
            font-size: 0.9rem;
            color: var(--gray-500);
            line-height: 1.6;
        }

        .feature-tag {
            display: inline-block;
            padding: 4px 10px;
            margin-top: 14px;
            border-radius: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            background: var(--green-50);
            color: var(--green-700);
            border: 1px solid var(--green-200);
        }

        /* ========== HOW IT WORKS ========== */
        .how-it-works {
            padding: 100px 0;
            background: var(--green-50);
        }

        .how-header {
            text-align: center;
            margin-bottom: 64px;
        }

        .how-header .section-subtitle {
            margin: 16px auto 0;
        }

        .how-steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 24px;
            position: relative;
        }

        .how-steps::before {
            content: '';
            position: absolute;
            top: 44px;
            left: 12%;
            right: 12%;
            height: 2px;
            background: var(--green-200);
            z-index: 0;
        }

        .how-step {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .how-step-number {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: var(--white);
            border: 3px solid var(--green-400);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--green-600);
            margin: 0 auto 20px;
            box-shadow: var(--shadow-md);
            transition: all 0.3s ease;
        }

        .how-step:hover .how-step-number {
            background: var(--green-600);
            color: var(--white);
            transform: scale(1.1);
            box-shadow: var(--shadow-green);
        }

        .how-step h4 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 8px;
        }

        .how-step p {
            font-size: 0.85rem;
            color: var(--gray-500);
            line-height: 1.5;
        }

        /* ========== STATS / SOCIAL PROOF ========== */
        .stats {
            padding: 80px 0;
            background: var(--green-700);
            position: relative;
            overflow: hidden;
        }

        .stats::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -20%;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .stats::after {
            content: '';
            position: absolute;
            bottom: -30%;
            right: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 32px;
            position: relative;
            z-index: 1;
        }

        .stat-item {
            text-align: center;
        }

        .stat-item-number {
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--white);
            letter-spacing: -0.02em;
            line-height: 1;
            margin-bottom: 8px;
        }

        .stat-item-label {
            font-size: 0.88rem;
            color: var(--green-200);
            font-weight: 500;
        }

        /* ========== TESTIMONIALS ========== */
        .testimonials {
            padding: 100px 0;
            background: var(--white);
        }

        .testimonials-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .testimonials-header .section-subtitle {
            margin: 16px auto 0;
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .testimonial-card {
            background: var(--gray-50);
            border: 1px solid var(--gray-200);
            border-radius: 20px;
            padding: 32px 28px;
            transition: all 0.3s ease;
            position: relative;
        }

        .testimonial-card:hover {
            box-shadow: var(--shadow-lg);
            border-color: var(--green-200);
            transform: translateY(-4px);
        }

        .testimonial-stars {
            display: flex;
            gap: 2px;
            margin-bottom: 16px;
        }

        .testimonial-stars i {
            color: #f59e0b;
            font-size: 0.9rem;
        }

        .testimonial-card blockquote {
            font-size: 0.92rem;
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 20px;
            font-style: italic;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .testimonial-avatar {
            width: 44px;
            height: 44px;
            border-radius: 50%;
            background: var(--green-600);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-weight: 700;
            font-size: 0.9rem;
        }

        .testimonial-name {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--gray-800);
        }

        .testimonial-role {
            font-size: 0.78rem;
            color: var(--gray-400);
        }

        /* ========== PRICING ========== */
        .pricing {
            padding: 100px 0;
            background: var(--green-50);
        }

        .pricing-header {
            text-align: center;
            margin-bottom: 60px;
        }

        .pricing-header .section-subtitle {
            margin: 16px auto 0;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 28px;
            align-items: stretch;
        }

        .pricing-card {
            background: var(--white);
            border: 1px solid var(--gray-200);
            border-radius: 24px;
            padding: 40px 32px;
            position: relative;
            transition: all 0.3s ease;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .pricing-card:hover {
            box-shadow: var(--shadow-xl);
            transform: translateY(-6px);
        }

        .pricing-card.popular {
            border-color: var(--green-400);
            box-shadow: var(--shadow-xl), 0 0 0 1px var(--green-300);
        }

        .pricing-popular-badge {
            position: absolute;
            top: -14px;
            left: 50%;
            transform: translateX(-50%);
            padding: 6px 20px;
            background: var(--green-600);
            color: var(--white);
            font-size: 0.75rem;
            font-weight: 700;
            border-radius: 100px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .pricing-plan-name {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--gray-800);
            margin-bottom: 8px;
        }

        .pricing-plan-desc {
            font-size: 0.82rem;
            color: var(--gray-400);
            margin-bottom: 20px;
            min-height: 38px;
        }

        .pricing-price {
            margin-bottom: 28px;
        }

        .pricing-currency {
            font-size: 1rem;
            font-weight: 600;
            color: var(--gray-500);
            vertical-align: super;
        }

        .pricing-amount {
            font-size: 2.8rem;
            font-weight: 900;
            color: var(--gray-900);
            letter-spacing: -0.03em;
        }

        .pricing-period {
            font-size: 0.85rem;
            color: var(--gray-400);
            font-weight: 500;
        }

        .pricing-features {
            list-style: none;
            margin-bottom: 32px;
            flex-grow: 1;
        }

        .pricing-card .btn {
            margin-top: auto;
            width: 100%;
            text-align: center;
        }

        .pricing-features li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            font-size: 0.88rem;
            color: var(--gray-600);
            border-bottom: 1px solid var(--gray-100);
        }

        .pricing-features li:last-child {
            border-bottom: none;
        }

        .pricing-features li i {
            color: var(--green-500);
            font-size: 1rem;
        }

        .pricing-card .btn {
            width: 100%;
        }

        /* ========== CTA SECTION ========== */
        .cta {
            padding: 100px 0;
            background: var(--white);
        }

        .cta-card {
            background: var(--green-700);
            border-radius: 28px;
            padding: 64px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -20%;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.04);
            border-radius: 50%;
        }

        .cta-card::after {
            content: '';
            position: absolute;
            bottom: -40%;
            left: -10%;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
        }

        .cta-content {
            position: relative;
            z-index: 1;
        }

        .cta h2 {
            font-size: clamp(1.6rem, 3vw, 2.4rem);
            font-weight: 800;
            color: var(--white);
            line-height: 1.2;
            letter-spacing: -0.02em;
            margin-bottom: 16px;
        }

        .cta p {
            font-size: 1.05rem;
            color: var(--green-200);
            margin-bottom: 36px;
            max-width: 550px;
            margin-left: auto;
            margin-right: auto;
        }

        .cta-actions {
            display: flex;
            justify-content: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        /* ========== FOOTER ========== */
        .footer {
            padding: 60px 0 0;
            background: var(--gray-900);
            color: var(--gray-300);
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            padding-bottom: 48px;
            border-bottom: 1px solid var(--gray-800);
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }

        .footer-logo {
            width: 38px;
            height: 38px;
            background: var(--green-600);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--white);
            font-size: 1rem;
            font-weight: 900;
        }

        .footer-brand-name {
            font-size: 1.1rem;
            font-weight: 800;
            color: var(--white);
        }

        .footer-about p {
            font-size: 0.88rem;
            color: var(--gray-400);
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .footer-socials {
            display: flex;
            gap: 10px;
        }

        .footer-socials a {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--gray-800);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-400);
            text-decoration: none;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .footer-socials a:hover {
            background: var(--green-600);
            color: var(--white);
        }

        .footer-heading {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--white);
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 20px;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 10px;
        }

        .footer-links a {
            text-decoration: none;
            color: var(--gray-400);
            font-size: 0.88rem;
            transition: color 0.2s;
        }

        .footer-links a:hover {
            color: var(--green-400);
        }

        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 24px 0;
            font-size: 0.82rem;
            color: var(--gray-500);
        }

        .footer-bottom a {
            color: var(--green-400);
            text-decoration: none;
            font-weight: 600;
        }

        /* ========== ANIMATIONS ========== */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(40px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-12px);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.4;
            }
        }

        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease, transform 0.7s ease;
        }

        .animate-on-scroll.visible {
            opacity: 1;
            transform: translateY(0);
        }

        /* ========== RESPONSIVE ========== */
        @media (max-width: 992px) {
            .hero .container {
                grid-template-columns: 1fr;
                gap: 40px;
                text-align: center;
            }

            .hero p {
                margin: 0 auto 32px;
            }

            .hero-actions {
                justify-content: center;
            }

            .hero-stats {
                justify-content: center;
            }

            .hero-visual {
                max-width: 560px;
                margin: 0 auto;
            }

            .features-grid,
            .testimonials-grid,
            .pricing-grid {
                grid-template-columns: 1fr 1fr;
            }

            .how-steps {
                grid-template-columns: 1fr 1fr;
            }

            .how-steps::before {
                display: none;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
                gap: 32px;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }

            .cta-card {
                padding: 48px 32px;
            }
        }

        @media (max-width: 768px) {
            .navbar-links {
                display: none;
            }

            .navbar-cta .btn-outline {
                display: none;
            }

            .hamburger {
                display: flex;
            }

            .features-grid,
            .testimonials-grid,
            .pricing-grid {
                grid-template-columns: 1fr;
            }

            .how-steps {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 32px;
            }

            .footer-bottom {
                flex-direction: column;
                gap: 8px;
                text-align: center;
            }

            .float-card {
                display: none;
            }

            .mockup-nav {
                display: none;
            }
        }

        @media (max-width: 480px) {
            .hero h1 {
                font-size: 1.9rem;
            }

            .hero-stats {
                flex-direction: column;
                gap: 16px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .cta-card {
                padding: 36px 20px;
            }

            .trusted-logos {
                gap: 24px;
            }
        }
    </style>
</head>

<body>

    <!-- ====== NAVBAR ====== -->
    <nav class="navbar" id="navbar">
        <div class="container">
            <a href="#" class="navbar-brand">
                <div class="navbar-logo"><i class="bi bi-mortarboard-fill"></i></div>
                <span class="navbar-brand-text">Sekolah<span>Ku</span></span>
            </a>

            <ul class="navbar-links">
                <li><a href="#fitur">Fitur</a></li>
                <li><a href="#cara-kerja">Cara Kerja</a></li>
                <li><a href="#testimoni">Testimoni</a></li>
                <li><a href="#harga">Harga</a></li>
            </ul>

            <div class="navbar-cta">
                <a href="{{ route('login') }}" class="btn btn-outline btn-sm">Masuk</a>
                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Daftar Sekolah</a>
                <button class="hamburger" onclick="document.querySelector('.navbar-links').classList.toggle('show')"
                    aria-label="Menu">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </nav>

    <!-- ====== MAIN CONTENT ====== -->
    <main id="main-content">

    <!-- ====== HERO ====== -->
    <section class="hero" id="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="hero-badge-dot"></span>
                    Platform SaaS Manajemen Sekolah #1 di Indonesia
                </div>

                <h1>
                    Kelola Sekolah Lebih<br>
                    <span class="highlight">Cerdas & Efisien</span>
                </h1>

                <p>SekolahKu-Apps menyatukan E-Rapor, Keuangan BOSP, Presensi Digital, dan Pembayaran SPP dalam satu
                    platform terpadu — mudah, cepat, dan terkoneksi WhatsApp.</p>

                <div class="hero-actions">
                    <a href="{{ route('register') }}" class="btn btn-primary">
                        <i class="bi bi-building-add"></i> Daftar Sekolah / Yayasan
                    </a>
                    <a href="#fitur" class="btn btn-outline">
                        <i class="bi bi-grid-fill"></i> Jelajahi Fitur
                    </a>
                </div>

                <div class="hero-stats">
                    <div class="hero-stat">
                        <div class="hero-stat-number">500+</div>
                        <div class="hero-stat-label">Sekolah Aktif</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">25K+</div>
                        <div class="hero-stat-label">Siswa Terdata</div>
                    </div>
                    <div class="hero-stat">
                        <div class="hero-stat-number">99.9%</div>
                        <div class="hero-stat-label">Uptime Server</div>
                    </div>
                </div>
            </div>

            <div class="hero-visual">
                <!-- Floating accent cards -->
                <div class="float-card float-card-1">
                    <div class="float-card-icon" style="background: var(--green-50); color: var(--green-600);">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <div class="float-card-title">SPP Lunas!</div>
                    <div class="float-card-desc">Bukti terverifikasi otomatis</div>
                </div>

                <div class="float-card float-card-2">
                    <div class="float-card-icon" style="background: #fef3c7; color: #d97706;">
                        <i class="bi bi-whatsapp"></i>
                    </div>
                    <div class="float-card-title">WA Terkirim</div>
                    <div class="float-card-desc">Notifikasi ke orang tua</div>
                </div>

                <!-- Browser mockup -->
                <div class="hero-mockup">
                    <div class="mockup-toolbar">
                        <div class="mockup-dot"></div>
                        <div class="mockup-dot"></div>
                        <div class="mockup-dot"></div>
                        <div class="mockup-url">sekolahku-apps.id/dashboard</div>
                    </div>
                    <div class="mockup-body">
                        <div class="mockup-sidebar">
                            <div class="mockup-nav">
                                <div class="mockup-nav-item active"><i class="bi bi-grid-1x2-fill"></i> Dashboard</div>
                                <div class="mockup-nav-item"><i class="bi bi-person-check"></i> Presensi</div>
                                <div class="mockup-nav-item"><i class="bi bi-wallet2"></i> SPP & Tagihan</div>
                                <div class="mockup-nav-item"><i class="bi bi-cash-stack"></i> BendaharaKu</div>
                                <div class="mockup-nav-item"><i class="bi bi-journal-text"></i> E-Rapor</div>
                                <div class="mockup-nav-item"><i class="bi bi-bookmark-star"></i> Anekdot</div>
                            </div>
                            <div class="mockup-content">
                                <div class="mockup-kpi-grid">
                                    <div class="mockup-kpi">
                                        <div class="mockup-kpi-label">Total Siswa</div>
                                        <div class="mockup-kpi-value">248</div>
                                    </div>
                                    <div class="mockup-kpi">
                                        <div class="mockup-kpi-label">Hadir Hari Ini</div>
                                        <div class="mockup-kpi-value" style="color: var(--emerald);">96%</div>
                                    </div>
                                    <div class="mockup-kpi">
                                        <div class="mockup-kpi-label">SPP Lunas</div>
                                        <div class="mockup-kpi-value" style="color: #0d9488;">89%</div>
                                    </div>
                                    <div class="mockup-kpi">
                                        <div class="mockup-kpi-label">Talangan BOSP</div>
                                        <div class="mockup-kpi-value" style="font-size: 1rem;">Rp 2.4jt</div>
                                    </div>
                                </div>
                                <div class="mockup-chart">
                                    <div class="mockup-chart-title">Kehadiran Mingguan</div>
                                    <div class="mockup-chart-bars">
                                        <div class="mockup-bar" style="height: 75%; background: var(--green-200);">
                                        </div>
                                        <div class="mockup-bar" style="height: 90%; background: var(--green-300);">
                                        </div>
                                        <div class="mockup-bar" style="height: 65%; background: var(--green-200);">
                                        </div>
                                        <div class="mockup-bar" style="height: 100%; background: var(--green-500);">
                                        </div>
                                        <div class="mockup-bar" style="height: 85%; background: var(--green-400);">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== TRUSTED BY ====== -->
    <section class="trusted">
        <div class="container">
            <div class="trusted-label">Dipercaya oleh ratusan lembaga pendidikan di seluruh Indonesia</div>
            <div class="trusted-logos">
                <div class="trusted-item"><i class="bi bi-building"></i> Dinas Pendidikan</div>
                <div class="trusted-item"><i class="bi bi-mortarboard"></i> TK & PAUD</div>
                <div class="trusted-item"><i class="bi bi-book"></i> SD / MI</div>
                <div class="trusted-item"><i class="bi bi-bank"></i> Yayasan Pendidikan</div>
                <div class="trusted-item"><i class="bi bi-globe"></i> Sekolah Swasta</div>
            </div>
        </div>
    </section>

    <!-- ====== FEATURES ====== -->
    <section class="features" id="fitur">
        <div class="container">
            <div class="features-header animate-on-scroll">
                <div class="section-badge"><i class="bi bi-stars"></i> Fitur Unggulan</div>
                <h2 class="section-title" style="margin-top: 16px;">Semua yang Sekolah Butuhkan,<br>dalam Satu Platform
                </h2>
                <p class="section-subtitle">Terintegrasi penuh — dari presensi pagi hingga laporan keuangan akhir
                    semester, SekolahKu-Apps mengotomatisasi seluruh alur kerja sekolah Anda.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon"><i class="bi bi-file-earmark-text-fill"></i></div>
                    <h3>E-Rapor Digital</h3>
                    <p>Penilaian narasi, capaian per elemen kurikulum, dan unduh rapor resmi dalam format PDF — sesuai
                        regulasi Kurikulum Merdeka.</p>
                    <span class="feature-tag">Kurikulum Merdeka Ready</span>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon"><i class="bi bi-person-check-fill"></i></div>
                    <h3>Presensi Dual Mode</h3>
                    <p>Guru isi presensi kelas dari grid pagi hari, atau siswa absen mandiri lewat akun masing-masing —
                        notifikasi WhatsApp otomatis ke orang tua.</p>
                    <span class="feature-tag">Otomatis WA ke Ortu</span>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon"><i class="bi bi-qr-code-scan"></i></div>
                    <h3>SPP & Pembayaran QRIS</h3>
                    <p>Generate tagihan SPP massal, orang tua scan QRIS sekolah & upload bukti bayar, bendahara tinggal
                        verifikasi satu klik.</p>
                    <span class="feature-tag">Tanpa Payment Gateway</span>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon"><i class="bi bi-cash-stack"></i></div>
                    <h3>BendaharaKu & LPJ BOSP</h3>
                    <p>Catat pengeluaran talangan pribadi, lampirkan foto nota, ajukan reimburse, dan
                        cetak rekap LPJ BOSP resmi.</p>
                    <span class="feature-tag">Laporan Dinas-Ready</span>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon"><i class="bi bi-journal-bookmark-fill"></i></div>
                    <h3>Catatan Anekdot</h3>
                    <p>Dokumentasi peristiwa & capaian perkembangan siswa dalam timeline visual — lengkap dengan
                        lampiran foto dan analisis capaian.</p>
                    <span class="feature-tag">6 Elemen Perkembangan</span>
                </div>

                <div class="feature-card animate-on-scroll">
                    <div class="feature-icon"><i class="bi bi-whatsapp"></i></div>
                    <h3>WhatsApp Otomatis</h3>
                    <p>Notifikasi ketidakhadiran, pengingat tagihan SPP, dan kuitansi digital langsung ke nomor WhatsApp
                        orang tua — tanpa SMS, tanpa biaya ekstra.</p>
                    <span class="feature-tag">Fonnte API Terintegrasi</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== HOW IT WORKS ====== -->
    <section class="how-it-works" id="cara-kerja">
        <div class="container">
            <div class="how-header animate-on-scroll">
                <div class="section-badge"><i class="bi bi-lightning-fill"></i> Mulai dalam Hitungan Menit</div>
                <h2 class="section-title" style="margin-top: 16px;">Cara Kerja SekolahKu-Apps</h2>
                <p class="section-subtitle">Empat langkah sederhana untuk mentransformasi manajemen sekolah Anda menjadi
                    serba digital dan efisien.</p>
            </div>

            <div class="how-steps">
                <div class="how-step animate-on-scroll">
                    <div class="how-step-number">1</div>
                    <h3 class="how-step-title">Daftar & Buat Profil</h3>
                    <p>Daftarkan sekolah Anda, isi profil, upload QRIS, dan atur tahun ajaran aktif.</p>
                </div>

                <div class="how-step animate-on-scroll">
                    <div class="how-step-number">2</div>
                    <h3 class="how-step-title">Input Data Master</h3>
                    <p>Tambahkan data siswa, guru, dan buat rombongan belajar — bisa import dari Excel.</p>
                </div>

                <div class="how-step animate-on-scroll">
                    <div class="how-step-number">3</div>
                    <h3 class="how-step-title">Operasional Harian</h3>
                    <p>Presensi digital setiap pagi, catat talangan, dan kelola tagihan SPP bulanan.</p>
                </div>

                <div class="how-step animate-on-scroll">
                    <div class="how-step-number">4</div>
                    <h3 class="how-step-title">Unduh Laporan</h3>
                    <p>Cetak E-Rapor, Rekap LPJ BOSP, dan laporan kehadiran dalam satu klik.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== STATS ====== -->
    <section class="stats">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-item animate-on-scroll">
                    <div class="stat-item-number" data-count="500">500+</div>
                    <div class="stat-item-label">Sekolah Terregistrasi</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-item-number" data-count="25000">25.000+</div>
                    <div class="stat-item-label">Data Siswa Aktif</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-item-number" data-count="1200000">1.2jt+</div>
                    <div class="stat-item-label">Notifikasi WA Terkirim</div>
                </div>
                <div class="stat-item animate-on-scroll">
                    <div class="stat-item-number" data-count="99">99.9%</div>
                    <div class="stat-item-label">Uptime & Ketersediaan</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== TESTIMONIALS ====== -->
    <section class="testimonials" id="testimoni">
        <div class="container">
            <div class="testimonials-header animate-on-scroll">
                <div class="section-badge"><i class="bi bi-chat-quote-fill"></i> Kata Mereka</div>
                <h2 class="section-title" style="margin-top: 16px;">Dipercaya Ratusan Sekolah<br>di Seluruh Indonesia
                </h2>
                <p class="section-subtitle">Dengarkan langsung dari para kepala sekolah, guru, dan bendahara yang sudah
                    merasakan manfaat SekolahKu-Apps.</p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card animate-on-scroll">
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <blockquote>"Sejak pakai SekolahKu-Apps, laporan LPJ BOSP kami tidak pernah terlambat lagi.
                        Pencatatan talangan sangat cepat, tinggal foto nota langsung tercatat. Luar biasa!"</blockquote>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">SA</div>
                        <div>
                            <div class="testimonial-name">Siti Aminah, S.Pd.</div>
                            <div class="testimonial-role">Bendahara — TK Negeri Pembina Bandung</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card animate-on-scroll">
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                    </div>
                    <blockquote>"Fitur WhatsApp otomatis ke orang tua sangat membantu. Mereka langsung tahu kalau
                        anaknya tidak hadir, tanpa harus telepon satu per satu. Efisiensi luar biasa."</blockquote>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">BR</div>
                        <div>
                            <div class="testimonial-name">Budi Raharjo, M.Pd.</div>
                            <div class="testimonial-role">Kepala Sekolah — SD Islam Terpadu Cimahi</div>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card animate-on-scroll">
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                            class="bi bi-star-fill"></i><i class="bi bi-star-half"></i>
                    </div>
                    <blockquote>"E-Rapor langsung bisa didownload PDF, lengkap dengan narasi per elemen kurikulum. Saya
                        tidak perlu lagi ketik manual di Word. Guru-guru di sekolah kami sangat terbantu."</blockquote>
                    <div class="testimonial-author">
                        <div class="testimonial-avatar">NW</div>
                        <div>
                            <div class="testimonial-name">Nurhayati Wulan, S.Pd.</div>
                            <div class="testimonial-role">Guru Kelas A — TK Aisyiyah Surabaya</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ====== PRICING ====== -->
    @php
        $plans = $plans ?? \App\Models\SubscriptionPlan::where('is_active', true)->get();
        $availableFeatures = \App\Models\SubscriptionPlan::availableFeatures();
    @endphp
    <section class="pricing" id="harga">
        <div class="container">
            <div class="pricing-header animate-on-scroll">
                <div class="section-badge"><i class="bi bi-tag-fill"></i> Paket Harga</div>
                <h2 class="section-title" style="margin-top: 16px;">Harga Terjangkau,<br>Manfaat Luar Biasa</h2>
                <p class="section-subtitle">Mulai gratis dan upgrade kapan saja. Tanpa kontrak tahunan, tanpa biaya
                    tersembunyi.</p>
            </div>

            <div class="pricing-grid">
                @forelse($plans as $plan)
                    @php
                        $isPopular = in_array(strtolower($plan->code), ['pro', 'professional']) || ($loop->count > 1 && $loop->iteration == 2);
                    @endphp
                    <div class="pricing-card {{ $isPopular ? 'popular' : '' }} animate-on-scroll">
                        @if($isPopular)
                            <div class="pricing-popular-badge">Paling Populer</div>
                        @endif
                        <div class="pricing-plan-name">{{ $plan->name }}</div>
                        <div class="pricing-plan-desc">{{ $plan->description ?? 'Paket layanan manajemen sekolah modern.' }}
                        </div>
                        <div class="pricing-price">
                            @if($plan->price == 0)
                                <span class="pricing-currency">Rp </span>
                                <span class="pricing-amount">0</span>
                                <span class="pricing-period">/ bulan</span>
                            @else
                                <span class="pricing-currency">Rp </span>
                                <span class="pricing-amount">{{ number_format($plan->price, 0, ',', '.') }}</span>
                                <span class="pricing-period">/ bulan</span>
                            @endif
                        </div>
                        <ul class="pricing-features">
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                {{ $plan->max_schools > 1 ? 'Multi-Sekolah (' . $plan->max_schools . ' Unit)' : '1 Sekolah / Lembaga' }}
                            </li>
                            <li>
                                <i class="bi bi-check-circle-fill"></i>
                                {{ $plan->max_siswas == 0 ? 'Unlimited Siswa' : 'Max ' . number_format($plan->max_siswas) . ' Siswa' }}
                            </li>
                            @foreach($availableFeatures as $fKey => $fLabel)
                                @if($plan->hasFeature($fKey))
                                    <li><i class="bi bi-check-circle-fill"></i> {{ $fLabel }}</li>
                                @endif
                            @endforeach
                        </ul>

                        <a href="{{ route('register', ['plan' => $plan->code]) }}"
                            class="btn {{ $isPopular ? 'btn-primary' : 'btn-outline' }}">
                            <i class="bi bi-check2-circle me-1"></i> Pilih {{ $plan->name }}
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center text-muted py-4">Belum ada paket langganan yang diatur oleh Superadmin.
                    </div>
                @endforelse
            </div>
        </div>
    </section>


    <!-- ====== CTA ====== -->
    <section class="cta">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content animate-on-scroll">
                    <h2>Siap Digitalisasi Sekolah Anda?<br>Mulai Sekarang — Gratis!</h2>
                    <p>Bergabung bersama 500+ sekolah yang sudah mempercayakan manajemen operasional mereka pada
                        SekolahKu-Apps.</p>
                    <div class="cta-actions">
                        <a href="{{ route('register') }}" class="btn btn-white">
                            <i class="bi bi-building-add"></i> Daftar Sekolah Sekarang
                        </a>
                        <a href="https://wa.me/6283878537818" target="_blank" class="btn btn-white">
                            <i class="bi bi-whatsapp"></i> Konsultasi via WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </main>

    <!-- ====== FOOTER ====== -->
    <footer class="footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <div class="footer-brand">
                        <div class="footer-logo"><i class="bi bi-mortarboard-fill"></i></div>
                        <div class="footer-brand-name">SekolahKu-Apps</div>
                    </div>
                    <p>Platform SaaS manajemen sekolah all-in-one yang dikembangkan oleh <strong>DnD Tech
                            Solutions</strong>. Misi kami: digitalisasi pendidikan Indonesia, satu sekolah pada satu
                        waktu.</p>
                    <div class="footer-socials">
                        <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                        <a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a>
                        <a href="#" aria-label="WhatsApp"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>

                <div>
                    <h3 class="footer-heading">Produk</h3>
                    <ul class="footer-links">
                        <li><a href="#fitur">Fitur Lengkap</a></li>
                        <li><a href="#harga">Harga & Paket</a></li>
                        <li><a href="#">Roadmap</a></li>
                        <li><a href="#">Changelog</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-heading">Perusahaan</h3>
                    <ul class="footer-links">
                        <li><a href="{{ route('pages.about') }}">Tentang Kami</a></li>
                        <li><a href="{{ route('pages.contact') }}">Hubungi Kami</a></li>
                    </ul>
                </div>

                <div>
                    <h3 class="footer-heading">Dukungan</h3>
                    <ul class="footer-links">
                        <li><a href="#">Pusat Bantuan</a></li>
                        <li><a href="#">Dokumentasi API</a></li>
                        <li><a href="#">Kebijakan Privasi</a></li>
                        <li><a href="#">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
            </div>

            <div class="footer-bottom">
                <span>&copy; {{ date('Y') }} SekolahKu-Apps — Produk <a href="#">DnD Tech Solutions</a>. Hak Cipta
                    Dilindungi.</span>
                <span>Dibuat dengan <i class="bi bi-heart-fill" style="color: #ef4444;"></i> di Indonesia</span>
            </div>
        </div>
    </footer>

    <!-- ====== SCRIPTS ====== -->
    <script>
        // Navbar scroll effect
        const navbar = document.getElementById('navbar');
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 50);
        });

        // Scroll-triggered animations (Intersection Observer)
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -40px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, idx) => {
                if (entry.isIntersecting) {
                    // Add stagger delay per sibling card
                    const siblings = entry.target.parentElement.querySelectorAll('.animate-on-scroll');
                    const index = Array.from(siblings).indexOf(entry.target);
                    entry.target.style.transitionDelay = `${index * 0.08}s`;
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => observer.observe(el));

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>

</html>