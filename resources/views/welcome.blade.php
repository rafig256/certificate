<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'سامانه صدور گواهینامه آنلاین') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <style>
            * {
                font-family: Tahoma, Arial, sans-serif;
            }

            /* Navbar */
            .navbar {
                background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
                padding: 0.5rem 0;
                box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            }

            .navbar-brand {
                font-weight: bold;
                color: white !important;
                font-size: 1.3rem;
            }

            .navbar-nav .nav-link {
                color: rgba(255,255,255,0.9) !important;
                padding: 0.5rem 1rem !important;
                transition: all 0.3s;
            }

            .navbar-nav .nav-link:hover {
                color: white !important;
                background: rgba(255,255,255,0.1);
                border-radius: 5px;
            }

            /* Hero Slider */
            .hero-slider {
                position: relative;
                height: 500px;
                overflow: hidden;
            }

            .hero-slide {
                display: none;
                height: 500px;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
                position: relative;
            }

            .hero-slide.active {
                display: flex;
            }

            .hero-content {
                display: flex;
                align-items: center;
                justify-content: space-between;
                height: 100%;
                color: white;
                padding: 0 5%;
            }

            .hero-text {
                flex: 1;
                z-index: 2;
            }

            .hero-text h1 {
                font-size: 2.5rem;
                font-weight: bold;
                margin-bottom: 1rem;
                text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
            }

            .hero-text p {
                font-size: 1.2rem;
                line-height: 1.8;
                text-shadow: 1px 1px 2px rgba(0,0,0,0.3);
            }

            .hero-image {
                flex: 1;
                display: flex;
                justify-content: center;
                align-items: center;
            }

            .hero-image img {
                max-width: 400px;
                max-height: 400px;
                border-radius: 15px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            }

            .slider-controls {
                position: absolute;
                bottom: 20px;
                left: 50%;
                transform: translateX(-50%);
                z-index: 10;
                display: flex;
                gap: 10px;
            }

            .slider-btn {
                background: rgba(255,255,255,0.3);
                border: 2px solid white;
                color: white;
                width: 40px;
                height: 40px;
                border-radius: 50%;
                cursor: pointer;
                transition: all 0.3s;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .slider-btn:hover {
                background: rgba(255,255,255,0.5);
            }

            /* Certificate Slider */
            .cert-slider {
                padding: 60px 0;
                background: #f8f9fa;
            }

            .cert-slide-container {
                position: relative;
                overflow: hidden;
                padding: 0 50px;
            }

            .cert-slides {
                display: flex;
                transition: transform 0.5s ease;
            }

            .cert-slide {
                min-width: 100%;
                padding: 20px;
            }

            .cert-slide img {
                width: 100%;
                max-width: 800px;
                margin: 0 auto;
                display: block;
                aspect-ratio: 1.414;
                object-fit: cover;
                border-radius: 10px;
                box-shadow: 0 5px 20px rgba(0,0,0,0.15);
            }

            /* Stats Section */
            .stats-section {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                padding: 60px 0;
                color: white;
            }

            .stat-item {
                text-align: center;
                padding: 20px;
            }

            .stat-number {
                font-size: 3rem;
                font-weight: bold;
                margin-bottom: 10px;
            }

            .stat-label {
                font-size: 1.1rem;
            }

            /* Categories Section */
            .categories-section {
                padding: 60px 0;
            }

            .category-card {
                background: white;
                border-radius: 10px;
                padding: 30px;
                text-align: center;
                box-shadow: 0 3px 15px rgba(0,0,0,0.1);
                transition: transform 0.3s, box-shadow 0.3s;
                height: 100%;
                margin-bottom: 20px;
            }

            .category-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0,0,0,0.15);
            }

            .category-icon {
                font-size: 3rem;
                color: #667eea;
                margin-bottom: 15px;
            }

            /* Marquee Sections */
            .marquee-section {
                padding: 40px 0;
                overflow: hidden;
                background: white;
            }

            .marquee-section.alt {
                background: #f8f9fa;
            }

            .marquee {
                display: flex;
                animation: scroll 30s linear infinite;
            }

            .marquee-reverse {
                animation: scroll-reverse 30s linear infinite;
            }

            @keyframes scroll {
                0% { transform: translateX(0); }
                100% { transform: translateX(-50%); }
            }

            @keyframes scroll-reverse {
                0% { transform: translateX(-50%); }
                100% { transform: translateX(0); }
            }

            .marquee-item {
                flex: 0 0 200px;
                margin: 0 20px;
                text-align: center;
                padding: 20px;
                background: white;
                border-radius: 10px;
                box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            }

            .marquee-item img {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                margin-bottom: 10px;
                object-fit: cover;
            }

            /* Services Section */
            .services-section {
                padding: 60px 0;
                background: #f8f9fa;
            }

            .service-card {
                background: white;
                border-radius: 10px;
                padding: 30px;
                margin-bottom: 30px;
                box-shadow: 0 3px 15px rgba(0,0,0,0.1);
                display: flex;
                gap: 20px;
                align-items: center;
                transition: transform 0.3s;
            }

            .service-card:hover {
                transform: translateX(-5px);
            }

            .service-icon {
                font-size: 3rem;
                color: #667eea;
                min-width: 80px;
            }

            /* Testimonials Section */
            .testimonials-section {
                padding: 60px 0;
                background: white;
            }

            .testimonial-card {
                background: #f8f9fa;
                border-radius: 10px;
                padding: 30px;
                margin-bottom: 20px;
                box-shadow: 0 3px 15px rgba(0,0,0,0.1);
            }

            .testimonial-header {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 15px;
            }

            .testimonial-avatar {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
                font-weight: bold;
            }

            .rating {
                color: #ffc107;
                font-size: 1.2rem;
            }

            /* Contact Section */
            .contact-section {
                padding: 60px 0;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
            }

            .contact-info {
                background: rgba(255,255,255,0.1);
                border-radius: 10px;
                padding: 30px;
                margin-bottom: 20px;
            }

            .contact-item {
                display: flex;
                align-items: center;
                gap: 15px;
                margin-bottom: 20px;
            }

            .contact-item i {
                font-size: 1.5rem;
            }

            .contact-form {
                background: white;
                border-radius: 10px;
                padding: 30px;
                color: #333;
            }

            .contact-form .form-control {
                margin-bottom: 15px;
            }

            /* Footer */
            footer {
                background: #1a1a2e;
                color: white;
                padding: 40px 0 20px;
            }

            .footer-column h5 {
                margin-bottom: 20px;
                color: #667eea;
            }

            .footer-column ul {
                list-style: none;
                padding: 0;
            }

            .footer-column ul li {
                margin-bottom: 10px;
            }

            .footer-column ul li a {
                color: rgba(255,255,255,0.7);
                text-decoration: none;
                transition: color 0.3s;
            }

            .footer-column ul li a:hover {
                color: white;
            }

            .footer-bottom {
                border-top: 1px solid rgba(255,255,255,0.1);
                margin-top: 30px;
                padding-top: 20px;
                text-align: center;
            }

            /* Section Title */
            .section-title {
                text-align: center;
                margin-bottom: 50px;
            }

            .section-title h2 {
                font-size: 2.5rem;
                font-weight: bold;
                margin-bottom: 15px;
                color: #1a1a2e;
            }

            .section-title p {
                color: #666;
                font-size: 1.1rem;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero-slider {
                    height: auto;
                    min-height: 400px;
                }

                .hero-slide {
                    height: auto;
                    min-height: 400px;
                }

                .hero-content {
                    flex-direction: column;
                    padding: 30px 20px;
                }

                .hero-text h1 {
                    font-size: 1.8rem;
                }

                .hero-text p {
                    font-size: 1rem;
                }

                .hero-image img {
                    max-width: 250px;
                    margin-top: 20px;
                }

                .stat-number {
                    font-size: 2rem;
                }

                .service-card {
                    flex-direction: column;
                    text-align: center;
                }

                .section-title h2 {
                    font-size: 1.8rem;
                }
            }
        </style>
    </head>
    <body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="bi bi-award"></i> سامانه گواهینامه
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">خانه</a></li>
                    <li class="nav-item"><a class="nav-link" href="#certificates">نمونه گواهینامه</a></li>
                    <li class="nav-item"><a class="nav-link" href="#categories">دسته‌بندی</a></li>
                    <li class="nav-item"><a class="nav-link" href="#services">خدمات</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">تماس با ما</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Slider -->
    <section id="home" class="hero-slider">
        <div class="hero-slide active">
            <div class="container hero-content">
                <div class="hero-text">
                    <h1>گواهینامه‌های معتبر و قابل استعلام</h1>
                    <p>مهارت‌های خود را با گواهینامه‌های دیجیتال و قابل استعلام به اثبات برسانید</p>
                </div>
                <div class="hero-image">
                    <img src="https://via.placeholder.com/400x400/667eea/ffffff?text=گواهینامه+دیجیتال" alt="گواهینامه">
                </div>
            </div>
        </div>
        <div class="hero-slide">
            <div class="container hero-content">
                <div class="hero-text">
                    <h1>صدور سریع و آسان</h1>
                    <p>در کمتر از ۲۴ ساعت گواهینامه آنلاین خود را دریافت کنید</p>
                </div>
                <div class="hero-image">
                    <img src="https://via.placeholder.com/400x400/764ba2/ffffff?text=صدور+سریع" alt="صدور سریع">
                </div>
            </div>
        </div>
        <div class="hero-slide">
            <div class="container hero-content">
                <div class="hero-text">
                    <h1>امنیت و اعتبار بالا</h1>
                    <p>تمامی گواهینامه‌ها دارای کد استعلام یکتا و امضای دیجیتال هستند</p>
                </div>
                <div class="hero-image">
                    <img src="https://via.placeholder.com/400x400/f093fb/ffffff?text=امنیت+بالا" alt="امنیت">
                </div>
            </div>
        </div>
        <div class="slider-controls">
            <button class="slider-btn" onclick="prevSlide()"><i class="bi bi-chevron-right"></i></button>
            <button class="slider-btn" onclick="nextSlide()"><i class="bi bi-chevron-left"></i></button>
        </div>
    </section>

    <!-- Certificate Samples -->
    <section id="certificates" class="cert-slider">
        <div class="container">
            <div class="section-title">
                <h2>نمونه گواهینامه‌ها</h2>
                <p>نگاهی به برخی از گواهینامه‌های صادر شده</p>
            </div>
            <div class="cert-slide-container">
                <button class="slider-btn" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="prevCert()">
                    <i class="bi bi-chevron-right"></i>
                </button>
                <div class="cert-slides">
                    <div class="cert-slide">
                        <img src="https://via.placeholder.com/800x566/667eea/ffffff?text=گواهینامه+نمونه+1" alt="گواهینامه 1">
                    </div>
                    <div class="cert-slide">
                        <img src="https://via.placeholder.com/800x566/764ba2/ffffff?text=گواهینامه+نمونه+2" alt="گواهینامه 2">
                    </div>
                    <div class="cert-slide">
                        <img src="https://via.placeholder.com/800x566/f093fb/ffffff?text=گواهینامه+نمونه+3" alt="گواهینامه 3">
                    </div>
                </div>
                <button class="slider-btn" style="position: absolute; left: 0; top: 50%; transform: translateY(-50%); z-index: 10;" onclick="nextCert()">
                    <i class="bi bi-chevron-left"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="15847">0</div>
                        <div class="stat-label">کاربران</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="342">0</div>
                        <div class="stat-label">سازمان‌ها</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="128">0</div>
                        <div class="stat-label">امضا کنندگان</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="28459">0</div>
                        <div class="stat-label">گواهینامه‌های صادر شده</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section id="categories" class="categories-section">
        <div class="container">
            <div class="section-title">
                <h2>دسته‌بندی گواهینامه‌ها</h2>
                <p>انواع گواهینامه‌های قابل صدور</p>
            </div>
            <div class="row">
                <div class="col-md-4 col-sm-6">
                    <div class="category-card">
                        <div class="category-icon"><i class="bi bi-lightbulb"></i></div>
                        <h4>مهارت‌های نرم</h4>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="category-card">
                        <div class="category-icon"><i class="bi bi-gear"></i></div>
                        <h4>مهارت‌های فنی</h4>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="category-card">
                        <div class="category-icon"><i class="bi bi-trophy"></i></div>
                        <h4>تقدیرنامه</h4>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="category-card">
                        <div class="category-icon"><i class="bi bi-people"></i></div>
                        <h4>گواهی حضور در سمینار</h4>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="category-card">
                        <div class="category-icon"><i class="bi bi-book"></i></div>
                        <h4>گواهی پایان دوره</h4>
                    </div>
                </div>
                <div class="col-md-4 col-sm-6">
                    <div class="category-card">
                        <div class="category-icon"><i class="bi bi-star"></i></div>
                        <h4>سایر گواهینامه‌ها</h4>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Organizations Marquee -->
    <section class="marquee-section">
        <div class="container">
            <h3 class="text-center mb-4">سازمان‌های همکار</h3>
        </div>
        <div class="marquee">
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/667eea/ffffff?text=س1" alt="سازمان 1">
                <h6>سازمان یک</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/764ba2/ffffff?text=س2" alt="سازمان 2">
                <h6>سازمان دو</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/f093fb/ffffff?text=س3" alt="سازمان 3">
                <h6>سازمان سه</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/667eea/ffffff?text=س4" alt="سازمان 4">
                <h6>سازمان چهار</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/764ba2/ffffff?text=س5" alt="سازمان 5">
                <h6>سازمان پنج</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/f093fb/ffffff?text=س6" alt="سازمان 6">
                <h6>سازمان شش</h6>
            </div>
            <!-- Duplicate for seamless loop -->
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/667eea/ffffff?text=س1" alt="سازمان 1">
                <h6>سازمان یک</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/764ba2/ffffff?text=س2" alt="سازمان 2">
                <h6>سازمان دو</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/f093fb/ffffff?text=س3" alt="سازمان 3">
                <h6>سازمان سه</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/667eea/ffffff?text=س4" alt="سازمان 4">
                <h6>سازمان چهار</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/764ba2/ffffff?text=س5" alt="سازمان 5">
                <h6>سازمان پنج</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/f093fb/ffffff?text=س6" alt="سازمان 6">
                <h6>سازمان شش</h6>
            </div>
        </div>
    </section>

    <!-- Signers Marquee -->
    <section class="marquee-section alt">
        <div class="container">
            <h3 class="text-center mb-4">امضا کنندگان</h3>
        </div>
        <div class="marquee marquee-reverse">
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/667eea/ffffff?text=ا1" alt="امضا کننده 1">
                <h6>دکتر احمدی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/764ba2/ffffff?text=ا2" alt="امضا کننده 2">
                <h6>مهندس محمدی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/f093fb/ffffff?text=ا3" alt="امضا کننده 3">
                <h6>دکتر رضایی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/667eea/ffffff?text=ا4" alt="امضا کننده 4">
                <h6>استاد حسینی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/764ba2/ffffff?text=ا5" alt="امضا کننده 5">
                <h6>دکتر کریمی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/f093fb/ffffff?text=ا6" alt="امضا کننده 6">
                <h6>مهندس علوی</h6>
            </div>
            <!-- Duplicate for seamless loop -->
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/667eea/ffffff?text=ا1" alt="امضا کننده 1">
                <h6>دکتر احمدی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/764ba2/ffffff?text=ا2" alt="امضا کننده 2">
                <h6>مهندس محمدی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/f093fb/ffffff?text=ا3" alt="امضا کننده 3">
                <h6>دکتر رضایی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/667eea/ffffff?text=ا4" alt="امضا کننده 4">
                <h6>استاد حسینی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/764ba2/ffffff?text=ا5" alt="امضا کننده 5">
                <h6>دکتر کریمی</h6>
            </div>
            <div class="marquee-item">
                <img src="https://via.placeholder.com/80/f093fb/ffffff?text=ا6" alt="امضا کننده 6">
                <h6>مهندس علوی</h6>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="services-section">
        <div class="container">
            <div class="section-title">
                <h2>ما چه خدماتی ارائه می‌دهیم</h2>
                <p>خدمات تخصصی برای نیازهای مختلف شما</p>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="service-card">
                        <div class="service-icon"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <h4>اثبات مهارت با گواهینامه قابل استعلام</h4>
                            <p>اگر مهارتی دارید اما نمی‌توانید اثبات کنید، گواهینامه قابل استعلام بگیرید.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="service-card">
                        <div class="service-icon"><i class="bi bi-cloud-upload"></i></div>
                        <div>
                            <h4>آنلاین‌سازی گواهینامه‌های موجود</h4>
                            <p>اگر گواهینامه‌ای دارید که نشان دهنده مهارت و اعتبار شماست اما آنلاین نیست، ما برایتان آنلاین با قابلیت استعلام می‌کنیم.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="service-card">
                        <div class="service-icon"><i class="bi bi-people-fill"></i></div>
                        <div>
                            <h4>گواهی حضور و پایان دوره برای سازمان‌ها</h4>
                            <p>اگر می‌خواهید به پرسنل خود گواهی حضور در سمینار یا گواهینامه تکمیل کردن دوره بدهید، ما در خدمت شما هستیم.</p>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="service-card">
                        <div class="service-icon"><i class="bi bi-star-fill"></i></div>
                        <div>
                            <h4>تقدیرنامه برای دانش‌آموزان</h4>
                            <p>اگر برای دانش‌آموزان خود می‌خواهید تقدیرنامه‌های زیبا با قابلیت استعلام بدهید، با ما تماس بگیرید.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="testimonials-section">
        <div class="container">
            <div class="section-title">
                <h2>نظرات کاربران</h2>
                <p>آنچه کاربران ما درباره خدماتمان می‌گویند</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">م</div>
                            <div>
                                <h5>محمد رضایی</h5>
                                <div class="rating">★★★★★</div>
                            </div>
                        </div>
                        <p>سرویس عالی و سریع. گواهینامه‌ام را در کمتر از یک روز دریافت کردم و کیفیت آن بسیار بالا بود.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">ز</div>
                            <div>
                                <h5>زهرا احمدی</h5>
                                <div class="rating">★★★★★</div>
                            </div>
                        </div>
                        <p>امکانات استعلام آنلاین فوق‌العاده است. کارفرمای من به راحتی توانست اعتبار گواهینامه‌ام را تایید کند.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <div class="testimonial-header">
                            <div class="testimonial-avatar">ع</div>
                            <div>
                                <h5>علی محمدی</h5>
                                <div class="rating">★★★★★</div>
                            </div>
                        </div>
                        <p>برای مؤسسه آموزشی خودم از این سامانه استفاده می‌کنم. بسیار حرفه‌ای و قابل اعتماد است.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-title text-white">
                <h2 class="text-white">تماس با ما</h2>
                <p>برای دریافت اطلاعات بیشتر با ما در ارتباط باشید</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="contact-info">
                        <div class="contact-item">
                            <i class="bi bi-telephone"></i>
                            <div>
                                <h5>تلفن تماس</h5>
                                <p>021-12345678</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <h5>ایمیل</h5>
                                <p>info@certificate.com</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-geo-alt"></i>
                            <div>
                                <h5>آدرس</h5>
                                <p>تهران، خیابان ولیعصر</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="contact-form">
                        <h4 class="mb-4">فرم تماس با ما</h4>
                        <form>
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" placeholder="نام و نام خانوادگی">
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" placeholder="ایمیل">
                                </div>
                            </div>
                            <input type="text" class="form-control" placeholder="موضوع">
                            <textarea class="form-control" rows="5" placeholder="پیام شما"></textarea>
                            <button type="submit" class="btn btn-primary btn-lg w-100">ارسال پیام</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 footer-column">
                    <h5>درباره ما</h5>
                    <p>سامانه صدور گواهینامه آنلاین با هدف ارائه خدمات معتبر و سریع به کاربران و سازمان‌ها راه‌اندازی شده است. ما با استفاده از جدیدترین تکنولوژی‌ها، گواهینامه‌های قابل استعلام و امن را در اختیار شما قرار می‌دهیم.</p>
                </div>
                <div class="col-md-4 footer-column">
                    <h5>لینک‌های مفید</h5>
                    <ul>
                        <li><a href="#home">خانه</a></li>
                        <li><a href="#certificates">نمونه گواهینامه‌ها</a></li>
                        <li><a href="#categories">دسته‌بندی</a></li>
                        <li><a href="#services">خدمات ما</a></li>
                        <li><a href="#contact">تماس با ما</a></li>
                        <li><a href="#">راهنمای استعلام</a></li>
                        <li><a href="#">قوانین و مقررات</a></li>
                    </ul>
                </div>
                <div class="col-md-4 footer-column">
                    <h5>نمادهای اعتماد</h5>
                    <div class="d-flex gap-3 mt-3">
                        <img src="https://via.placeholder.com/100x100/667eea/ffffff?text=نماد+1" alt="نماد اعتماد" style="width: 80px; border-radius: 10px;">
                        <img src="https://via.placeholder.com/100x100/764ba2/ffffff?text=نماد+2" alt="نماد اعتماد" style="width: 80px; border-radius: 10px;">
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 1403 سامانه صدور گواهینامه آنلاین. تمامی حقوق محفوظ است.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hero Slider
        let currentSlide = 0;
        const slides = document.querySelectorAll('.hero-slide');

        function showSlide(n) {
            slides[currentSlide].classList.remove('active');
            currentSlide = (n + slides.length) % slides.length;
            slides[currentSlide].classList.add('active');
        }

        function nextSlide() {
            showSlide(currentSlide + 1);
        }

        function prevSlide() {
            showSlide(currentSlide - 1);
        }

        setInterval(nextSlide, 5000);

        // Certificate Slider
        let currentCert = 0;
        const certSlides = document.querySelector('.cert-slides');
        const totalCerts = document.querySelectorAll('.cert-slide').length;

        function showCert(n) {
            currentCert = (n + totalCerts) % totalCerts;
            certSlides.style.transform = `translateX(${currentCert * 100}%)`;
        }

        function nextCert() {
            showCert(currentCert + 1);
        }

        function prevCert() {
            showCert(currentCert - 1);
        }

        // Counter Animation
        function animateCounter(el) {
            const target = parseInt(el.getAttribute('data-target'));
            const duration = 2000;
            const step = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += step;
                if (current >= target) {
                    el.textContent = target.toLocaleString('fa-IR');
                    clearInterval(timer);
                } else {
                    el.textContent = Math.floor(current).toLocaleString('fa-IR');
                }
            }, 16);
        }

        // Trigger counter animation when section is visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.stat-number');
                    counters.forEach(counter => {
                        if (counter.textContent === '0') {
                            animateCounter(counter);
                        }
                    });
                }
            });
        }, { threshold: 0.5 });

        observer.observe(document.querySelector('.stats-section'));

        // Smooth scroll for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    // Close mobile menu if open
                    const navbarCollapse = document.querySelector('.navbar-collapse');
                    if (navbarCollapse.classList.contains('show')) {
                        bootstrap.Collapse.getInstance(navbarCollapse).hide();
                    }
                }
            });
        });
    </script>
    </body>
    </html>
