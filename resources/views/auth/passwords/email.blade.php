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
        <h2>Password Reset</h2>
        </div>
        @if (session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        <form action="{{ route('password.email') }}" method="POST" class="php-email-form">
            @csrf
          <div class="form-group">
            <input placeholder="your email address" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required />
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="text-center">
            <button type="submit">Send Password Reset Link</button>
          </div>
        </form>
      </div>
     
      <div class="col-lg-4"></div>
    </div>

  </div>
</section><!-- End Contact Section -->
@endsection
