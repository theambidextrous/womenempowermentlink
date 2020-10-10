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

      <div class="col-lg-3"></div>

      <div class="col-lg-6" style="background: #f7f2f2;padding: 21px;border-radius:8px;">
        <div class="section-title">
        <h2>Register</h2>
        </div>
        <form action="{{route('register')}}" method="POST" class="php-email-form">
            @csrf
          <div class="form-group">
            <input placeholder="first & last name" type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required />
            @error('name')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <input placeholder="your email address" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required />
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <input placeholder="your phone" type="phone" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required />
            @error('phone')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <input placeholder="set password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required/>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <input placeholder="confirm password" type="password" class="form-control" name="password_confirmation" required/>
          </div>
          <div class="text-center">
            <button type="submit">Register</button>
          </div>
        </form>
      </div>
     
      <div class="col-lg-3"></div>
    </div>

  </div>
</section><!-- End Contact Section -->
@endsection
