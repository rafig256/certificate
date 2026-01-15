<!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ config('app.name', 'سامانه صدور گواهینامه آنلاین') }}</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="{{asset('css/welcome.css')}}">
        {{--slider--}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

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
                    <li class="nav-item"><a class="nav-link" href="/admin">ورود</a></li>
                    <li class="nav-item"><a class="nav-link" href="/admin/register">ثبت نام</a></li>
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
                    <img src="{{ asset('storage/home/valid-certificate.jpg') }}" alt="گواهینامه معتبر">
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
                    <img src="{{asset('storage/home/speed.png')}}" alt="صدور سریع">
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
                    <img src="{{asset('storage/home/secure.jpg')}}" alt="امنیت">
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
                        <img src="{{asset('storage/home/cert-sample1.jpg')}}" alt="گواهینامه 1">
                    </div>
                    <div class="cert-slide">
                        <img src="{{asset('storage/home/cert-sample2.jpg')}}" alt="گواهینامه 2">
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
                        <div class="stat-number" data-target="{{$stat->userCount}}">0</div>
                        <div class="stat-label">کاربران</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="{{$stat->organizationCount}}">0</div>
                        <div class="stat-label">سازمان‌ها</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="{{$stat->signerCount}}">0</div>
                        <div class="stat-label">امضا کنندگان</div>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="stat-item">
                        <div class="stat-number" data-target="{{$stat->certificateCount}}">0</div>
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

    <!-- Organizations slider -->
    <section class="marquee-section">
        <div class="container">
            <h3 class="text-center mb-4">@lang('fields.organizers_title')</h3>
        </div>

        <div class="swiper org-swiper">
            <div class="swiper-wrapper">
                @foreach($organizes as $organ)
                    <div class="swiper-slide marquee-item">
                        <img src="{{ asset('storage/'.($organ->logo_path ?? 'temp/unknow.jpg')) }}">
                        <h6>{{ $organ->slug }}</h6>
                    </div>
                @endforeach
                @foreach($organizes as $organ)
                    <div class="swiper-slide marquee-item">
                        <img src="{{ asset('storage/'.($organ->logo_path ?? 'temp/unknow.jpg')) }}">
                        <h6>{{ $organ->slug }}</h6>
                    </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- Signers slider -->
    <section class="marquee-section">
        <div class="container">
            <h3 class="text-center mb-4">@lang('fields.signatories_title')</h3>
        </div>
        <div class="swiper signer-swiper">
            <div class="swiper-wrapper">
                @foreach($signatories as $signer)
                    <div class="swiper-slide marquee-item">
                        <img src="{{ asset('storage/'.($signer->logo_path ?? 'temp/unknow.jpg')) }}">
                        <h6>{{ $signer->name }}</h6>
                    </div>
                @endforeach
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
                                <p>09914190488</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-envelope"></i>
                            <div>
                                <h5>ایمیل</h5>
                                <p>rafig_256@yahoo.com</p>
                            </div>
                        </div>
                        <div class="contact-item">
                            <i class="bi bi-geo-alt"></i>
                            <div>
                                <h5>آدرس</h5>
                                <p>مشگین شهر، میدان آزادی</p>
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
                    </ul>
                </div>
                <div class="col-md-4 footer-column">
                    <h5>نمادها</h5>
                    <div class="d-flex gap-3 mt-3">
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
        slides[0].classList.add('active');

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
    <script>
        new Swiper('.org-swiper', {
            loop: true,
            slidesPerView: 'auto',
            spaceBetween: 30,
            speed: 6000,
            freeMode: true,
            freeModeMomentum: false,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
            },
        });

        new Swiper('.signer-swiper', {
            loop: true,
            slidesPerView: 'auto',
            spaceBetween: 30,
            speed: 6000,
            freeMode: true,
            freeModeMomentum: false,
            autoplay: {
                delay: 0,
                reverseDirection: true,
                disableOnInteraction: false,
            },
        });
    </script>
    </body>
    </html>
