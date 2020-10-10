@extends('layouts.outer')

@section('content')
<!-- ======= Hero Section ======= -->
<style>
.contact .php-email-form input, .contact .php-email-form textarea {
    border-radius: 10px;
}
</style>

<main id="main">

<!-- ======= login Section ======= -->
<section id="contact" class="contact">
  <div class="container">

    <div class="row">

      <div class="col-lg-4"></div>

      <div class="col-lg-4" style="background: #f7f2f2;padding: 21px;border-radius:8px;">
        <div class="section-title">
        <h2>Verify your email address</h2>
        </div>
        @if (session('resent'))
            <div class="alert alert-success" role="alert">
                {{ __('A fresh verification link has been sent to your email address.') }}
            </div>
        @endif
        {{ __('Before proceeding, please check your email for a verification link.') }}
        {{ __('If you did not receive the email') }},
        <form action="{{ route('verification.resend') }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
        </form>
      </div>
     
      <div class="col-lg-4"></div>
    </div>

  </div>
</section><!-- End Contact Section -->
@endsection
