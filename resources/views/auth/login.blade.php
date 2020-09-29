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
        <h2>Login</h2>
        </div>
        <form action="{{route('login')}}" method="POST" class="php-email-form">
            @csrf
          <div class="form-group">
            <input placeholder="your email address" type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required />
            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <input placeholder="your password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required/>
            @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
          </div>
          <div class="form-group">
            <div class="form-check text-center">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                    <label class="form-check-label" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
          </div>
          <div class="text-center">
            <button type="submit">Login</button>
            @if (Route::has('password.request'))
                <a class="btn btn-link" href="{{ route('password.request') }}">
                    {{ __('Forgot Your Password?') }}
                </a>
            @endif
          </div>
        </form>
      </div>
     
      <div class="col-lg-4"></div>
    </div>

  </div>
</section><!-- End Contact Section -->
@endsection
