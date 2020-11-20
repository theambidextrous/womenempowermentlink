<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>{{ config('app.name') }}</title>
  <meta content="" name="descriptison">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link href="{{ asset('outer/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('outer/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{ asset('outer/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{ asset('outer/vendor/icofont/icofont.min.css')}}" rel="stylesheet">
  <link href="{{ asset('outer/vendor/boxicons/css/boxicons.min.css')}}" rel="stylesheet">
  <link href="{{ asset('outer/vendor/owl.carousel/assets/owl.carousel.min.css')}}" rel="stylesheet">
  <link href="{{ asset('outer/vendor/venobox/venobox.css')}}" rel="stylesheet">
  <link href="{{ asset('outer/vendor/aos/aos.css')}}" rel="stylesheet">

  <!-- Template Main CSS File -->
  <link href="{{ asset('outer/css/style.css') }}" rel="stylesheet">
  <style>
    #header .logo img {
      max-height: 80px;
    }
    .nav-menu .get-started_l a {
      background: #ff5167;
      color: #fff;
      border-radius: 50px;
      margin: 0 15px;
      padding: 10px 25px;
    }
  </style>
</head>

<body>

  <!-- ======= Header ======= -->
  <header id="header" class="fixed-top  header-transparent ">
    <div class="container d-flex align-items-center">

      <div class="logo mr-auto">
        <!-- <h1 class="text-light"><a href="index.html">Appland</a></h1> -->
        <a href="{{route('welcome')}}"><img src="{{asset('outer/img/wel-logo.png')}}" alt="" class="img-fluid"></a>
      </div>

      <nav class="nav-menu d-none d-lg-block">
        <ul>
          <!-- <li><a href="{{route('welcome')}}">Home</a></li> -->
          <!-- <li><a href="{{route('welcome')}}">About eLearning</a></li> -->
          <li class="get-started_l"><a href="{{route('login')}}">Login</a></li>
          <li class="get-started"><a href="{{route('register')}}">Enroll Now</a></li>
        </ul>
      </nav><!-- .nav-menu -->

    </div>
  </header><!-- End Header -->

  @yield('content')
  
  </main><!-- End #main -->

  <!-- ======= Footer ======= -->
  <footer id="footer">

    <div class="footer-top">
      <div class="container">
        <div class="row">

          <div class="col-lg-3 col-md-6 footer-contact" data-aos="fade-up">
            <h3>{{ config('app.name') }}</h3>
            <p>
            Plot 2/388 Kirichwa Gardens<br>
            Elgeyo Marakwet Rd, Kilimani<br>
            P.O. Box 22574 – 00100 Nairobi,Kenya<br>
            Phone:+254 711 907 132 | +245 732 574 060<br>
            Email:info@wel.or.ke
            </p>
          </div>

          <div class="col-lg-3 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="100">
            <h4>Navigation</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="https://wel.or.ke">Women's Empowerment Link</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="https://wel.or.ke/">About us</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="https://wel.or.ke/contact/">Contact</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="200">
            <h4>Quick Links</h4>
            <ul>
              <li><i class="bx bx-chevron-right"></i> <a href="https://wel.or.ke/blog/">Blog</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="https://wel.or.ke/blog/terms-of-service">Terms of service</a></li>
              <li><i class="bx bx-chevron-right"></i> <a href="https://wel.or.ke/blog/privacy-policy">Privacy policy</a></li>
            </ul>
          </div>

          <div class="col-lg-3 col-md-6 footer-links" data-aos="fade-up" data-aos-delay="300">
            <h4>Connect with us</h4>
            <p>Get with WEL via facebook, twitter and instagram.</p>
            <div class="social-links mt-3">
              <a href="https://twitter.com/WEL2030" class="twitter"><i class="bx bxl-twitter"></i></a>
              <a href="https://www.facebook.com/Womens-Empowerment-Link-The-Official-WEL-Page-232964263436207/" class="facebook"><i class="bx bxl-facebook"></i></a>
              <a href="https://www.youtube.com/channel/UCwQHpR92Omusvj-ztpoBA9w?view_as=subscriber" class="instagram"><i class="bx bxl-youtube"></i></a>
            </div>
          </div>

        </div>
      </div>
    </div>

    <div class="container py-4">
      <div class="copyright">
        &copy; Copyright <strong><span>{{ config('app.name') }}</span></strong>. All Rights Reserved
      </div>
      <div class="credits">
        <!-- Designed by <a href="{{route('welcome')}}">{{ config('app.name') }}</a> -->
      </div>
    </div>
  </footer><!-- End Footer -->

  <a href="#" class="back-to-top"><i class="icofont-simple-up"></i></a>

  <!-- Vendor JS Files -->
  <script src="{{ asset('outer/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('outer/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('outer/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
  <!-- <script src="{{ asset('outer/vendor/php-email-form/validate.js') }}"></script> -->
  <script src="{{ asset('outer/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('outer/vendor/venobox/venobox.min.js') }}"></script>
  <script src="{{ asset('outer/vendor/aos/aos.js') }}"></script>

  <!-- Template Main JS File -->
  <script src="{{ asset('outer/js/main.js') }}"></script>

</body>

</html>