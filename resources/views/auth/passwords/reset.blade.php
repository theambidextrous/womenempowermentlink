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

      <div class="col-lg-4" style="background: #fff;padding: 21px;border-radius:8px;">
        <div class="section-title">
        <h2>Change Password</h2>
        </div>
        <form action="{{ route('password.update') }}" method="POST" class="php-email-form">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">

          <div class="form-group">
            <input placeholder="your email address" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $email ?? old('email') }}" required />
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="form-group">
            <input placeholder="new password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required/>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>

          <div class="form-group">
            <input placeholder="new password" type="password" class="form-control" name="password_confirmation" required/>
          </div>

          <div class="text-center">
            <button type="submit">Change Password</button>
          </div>
        </form>
      </div>
     
      <div class="col-lg-4"></div>
    </div>

  </div>
</section><!-- End Contact Section -->
@endsection
