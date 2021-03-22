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

      <div class="col-lg-6" style="background: #fff;padding: 21px;border-radius:8px;">
        <div class="section-title">
        <h2>Register</h2>
        </div>
        <form action="{{route('register')}}" method="POST" class="php-email-form">
            @csrf
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>First & Last Name</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required />
                @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required />
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group">
                <label>Phone number</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required />
                @error('phone')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Gender</label>
                <select id="gender" name="gender" class="form-control" required>
                  <option value="nn">Select Gender</option>
                  <option value="FEMALE">Female</option>
                  <option value="MALE">Male</option>
                  <option value="MIXED">Mixed Gender</option>
                  <option value="other">Other</option>
                </select>
                @error('gender')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group">
                <small>If OTHER, please specify below</small>
                <input type="text" name="gender_other" class="form-control"/>
              </div>

              <div class="form-group">
                <label>Special needs</label>
                <select id="special_needs" name="special_needs" class="form-control" required>
                  <option value="none">No special need</option>
                  <option value="DEAF">Deaf</option>
                  <option value="BLIND">Blind</option>
                  <option value="other">Other</option>
                </select>
                @error('special_needs')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
              <div class="form-group">
                <small>If OTHER, please specify below</small>
                <input type="text" name="special_other" class="form-control"/>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label>County</label>
                <input type="text" name="county" class="form-control @error('county') is-invalid @enderror" value="{{ old('county') }}" required />
                @error('county')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label>Constituency</label>
                <input type="text" name="constituency" class="form-control @error('constituency') is-invalid @enderror" value="{{ old('constituency') }}" required />
                @error('constituency')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Ward</label>
                <input type="text" name="ward" class="form-control @error('ward') is-invalid @enderror" value="{{ old('ward') }}" required />
                @error('ward')
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
            </div>
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
