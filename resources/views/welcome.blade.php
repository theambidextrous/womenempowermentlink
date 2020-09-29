@extends('layouts.outer')

@section('content')
<!-- ======= Hero Section ======= -->
<style>
.contact .php-email-form input, .contact .php-email-form textarea {
    border-radius: 10px;
}
</style>
<section id="hero" class="d-flex align-items-center">

<div class="container">
  <div class="row">
    <div class="col-lg-6 d-lg-flex flex-lg-column justify-content-center align-items-stretch pt-5 pt-lg-0 order-2 order-lg-1" data-aos="fade-up">
      <div>
        <h1>Strengthening Inclusive Leadership</h1>
        <h2>Learn, attempt quizes and guage yourself free from anywhere around the country. Get the WEL eLearning App</h2>
        <a href="{{route('welcome')}}" class="download-btn"><i class="bx bxl-play-store"></i> Google Play</a>
        <a href="{{route('welcome')}}" class="download-btn"><i class="bx bxl-apple"></i> App Store</a>
      </div>
    </div>
    <div class="col-lg-6 d-lg-flex flex-lg-column align-items-stretch order-1 order-lg-2 hero-img" data-aos="fade-up">
      <img src="{{ asset('outer/img/hero-img_new.png') }}" class="img-fluid" alt="">
    </div>
  </div>
</div>

</section><!-- End Hero -->

<main id="main">

<!-- ======= App Features Section ======= -->
<section id="features" class="features">
  <div class="container">

    <div class="section-title">
      <h2>What is Offered</h2>
      <!-- <p>Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p> -->
    </div>

    <div class="row no-gutters">
      <div class="col-xl-7 d-flex align-items-stretch order-2 order-lg-1">
        <div class="content d-flex flex-column justify-content-center">
          <div class="row">
            <div class="col-md-6 icon-box" data-aos="fade-up">
              <i class="bx bx-receipt"></i>
              <h4>Short Courses</h4>
              <p>Consequuntur sunt aut quasi enim aliquam quae harum pariatur laboris nisi ut aliquip</p>
            </div>
            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="100">
              <i class="bx bx-cube-alt"></i>
              <h4>Live trainings</h4>
              <p>Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt</p>
            </div>
            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="200">
              <i class="bx bx-images"></i>
              <h4>Self-paced Downloadable Content</h4>
              <p>Aut suscipit aut cum nemo deleniti aut omnis. Doloribus ut maiores omnis facere</p>
            </div>
            <div class="col-md-6 icon-box" data-aos="fade-up" data-aos-delay="300">
              <i class="bx bx-shield"></i>
              <h4>Quizes & Grades</h4>
              <p>Expedita veritatis consequuntur nihil tempore laudantium vitae denat pacta</p>
            </div>
          </div>
        </div>
      </div>
      <div class="image col-xl-5 d-flex align-items-stretch justify-content-center order-1 order-lg-2" data-aos="fade-left" data-aos-delay="100">
        <img src="{{ asset('outer/img/features.svg') }}" class="img-fluid" alt="">
      </div>
    </div>

  </div>
</section><!-- End App Features Section -->

<!-- ======= Details Section ======= -->
<section id="details" class="details">
  <div class="container">

    <div class="row content">
      <div class="col-md-4" data-aos="fade-right">
        <img src="{{ asset('outer/img/details-1.png') }}" class="img-fluid" alt="">
      </div>
      <div class="col-md-8 pt-4" data-aos="fade-up">
        <h3>About WEL eLearning</h3>
        <p class="font-italic">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
          magna aliqua.
        </p>
        <ul>
          <li><i class="icofont-check"></i> Ullamco laboris nisi ut aliquip ex ea commodo consequat.</li>
          <li><i class="icofont-check"></i> Duis aute irure dolor in reprehenderit in voluptate velit.</li>
          <li><i class="icofont-check"></i> Iure at voluptas aspernatur dignissimos doloribus repudiandae.</li>
          <li><i class="icofont-check"></i> Est ipsa assumenda id facilis nesciunt placeat sed doloribus praesentium.</li>
        </ul>
        <p>
          Voluptas nisi in quia excepturi nihil voluptas nam et ut. Expedita omnis eum consequatur non. Sed in asperiores aut repellendus. Error quisquam ab maiores. Quibusdam sit in officia
        </p>
      </div>
    </div>
    

  </div>
</section><!-- End Details Section -->


<!-- ======= Contact Section ======= -->
<section id="contact" class="contact">
  <div class="container">

    <div class="section-title">
      <h2>Contact</h2>
      <p>You can reach out to us through the following contact information. You may as well send us a message through the phone below.</p>
    </div>

    <div class="row">

      <div class="col-lg-6">
        <div class="row">
          <div class="col-lg-6 info" data-aos="fade-up">
            <i class="bx bx-map"></i>
            <h4>Address</h4>
            <p>207, Nairobi Groove<br>NRB, </p>
          </div>
          <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="100">
            <i class="bx bx-phone"></i>
            <h4>Call Us</h4>
            <p>+254(0)722000000<br>+254(0)722000000</p>
          </div>
          <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="200">
            <i class="bx bx-envelope"></i>
            <h4>Email Us</h4>
            <p>support@wel-elearning.com<br>info@wel-elearning.com</p>
          </div>
          <div class="col-lg-6 info" data-aos="fade-up" data-aos-delay="300">
            <i class="bx bx-time-five"></i>
            <h4>Working Hours</h4>
            <p>Mon - Fri: 9AM to 5PM<br>Sunday: 9AM to 1PM</p>
          </div>
        </div>
      </div>

      <div class="col-lg-6">
        <form method="post" role="form" class="php-email-form" data-aos="fade-up">
          <div class="form-group">
            <input placeholder="Your Name" type="text" name="name" class="form-control" id="name" data-rule="minlen:4" data-msg="Please enter at least 4 chars" />
            <div class="validate"></div>
          </div>
          <div class="form-group">
            <input placeholder="Your Email" type="email" class="form-control" name="email" id="email" data-rule="email" data-msg="Please enter a valid email" />
            <div class="validate"></div>
          </div>
          <div class="form-group">
            <input placeholder="Subject" type="text" class="form-control" name="subject" id="subject" data-rule="minlen:4" data-msg="Please enter at least 8 chars of subject" />
            <div class="validate"></div>
          </div>
          <div class="form-group">
            <textarea placeholder="Message" class="form-control" name="message" rows="5" data-rule="required" data-msg="Please write something for us"></textarea>
            <div class="validate"></div>
          </div>
          <div class="mb-3">
            <div class="loading">Loading</div>
            <div class="error-message"></div>
            <div class="sent-message">Your message has been sent. Thank you!</div>
          </div>
          <div class="text-center"><button type="submit">Send Message</button></div>
        </form>
      </div>

    </div>

  </div>
</section><!-- End Contact Section -->
@endsection
