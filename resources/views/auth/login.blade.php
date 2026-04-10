@extends('auth.auth')

@section('form')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">

          <!-- Logo (sama seperti sebelumnya) -->
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              {{-- SVG logo kamu di sini --}}
              <span class="app-brand-text demo text-body fw-bolder">Sneat</span>
            </a>
          </div>

          <h4 class="mb-2">Welcome to Sneat! 👋</h4>
          <p class="mb-4">Please sign-in to your account and start the adventure</p>

          {{-- Tampilkan error --}}
          @if ($errors->any())
            <div class="alert alert-danger mb-3">
              @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          @endif

          <form id="formAuthentication" class="mb-3"
                action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email kamu"
                autofocus
              />
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3 form-password-toggle">
              <div class="d-flex justify-content-between">
                <label class="form-label" for="password">Password</label>
                {{-- <a href="#"><small>Forgot Password?</small></a> --}}
              </div>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password"
                  class="form-control @error('password') is-invalid @enderror"
                  name="password"
                  placeholder="············"
                />
                <span class="input-group-text cursor-pointer">
                  <i class="bx bx-hide"></i>
                </span>
              </div>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input" type="checkbox"
                       name="remember" id="remember-me" />
                <label class="form-check-label" for="remember-me">
                  Remember Me
                </label>
              </div>
            </div>

            <div class="mb-3">
              <button class="btn btn-primary d-grid w-100" type="submit">
                Sign in
              </button>
            </div>
          </form>

          <p class="text-center">
            <span>New on our platform?</span>
            <a href="{{ route('register') }}">
              <span>Create an account</span>
            </a>
          </p>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection