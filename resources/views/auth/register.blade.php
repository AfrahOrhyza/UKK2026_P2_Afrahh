@extends('auth.auth')

@section('form')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card">
        <div class="card-body">

          <!-- Logo -->
          <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              {{-- SVG logo kamu di sini --}}
              <span class="app-brand-text demo text-body fw-bolder">Sneat</span>
            </a>
          </div>

          <h4 class="mb-2">Adventure starts here 🚀</h4>
          <p class="mb-4">Make your app management easy and fun!</p>

          {{-- Tampilkan error --}}
          @if ($errors->any())
            <div class="alert alert-danger mb-3">
              @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
              @endforeach
            </div>
          @endif

          <form id="formAuthentication" class="mb-3"
                action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-3">
              <label for="name" class="form-label">Nama Lengkap</label>
              <input
                type="text"
                class="form-control @error('name') is-invalid @enderror"
                id="name"
                name="name"
                value="{{ old('name') }}"
                placeholder="Masukkan nama kamu"
                autofocus
              />
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="email" class="form-label">Email</label>
              <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                value="{{ old('email') }}"
                placeholder="Masukkan email kamu"
              />
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password">Password</label>
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
              @error('password')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3 form-password-toggle">
              <label class="form-label" for="password_confirmation">
                Konfirmasi Password
              </label>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password_confirmation"
                  class="form-control"
                  name="password_confirmation"
                  placeholder="············"
                />
                <span class="input-group-text cursor-pointer">
                  <i class="bx bx-hide"></i>
                </span>
              </div>
            </div>

            <div class="mb-3">
              <div class="form-check">
                <input class="form-check-input @error('terms') is-invalid @enderror"
                       type="checkbox" id="terms-conditions" name="terms" />
                <label class="form-check-label" for="terms-conditions">
                  Saya setuju dengan
                  <a href="#">privacy policy &amp; terms</a>
                </label>
                @error('terms')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <button class="btn btn-primary d-grid w-100" type="submit">
              Sign up
            </button>
          </form>

          <p class="text-center">
            <span>Already have an account?</span>
            <a href="{{ route('login') }}">
              <span>Sign in instead</span>
            </a>
          </p>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection