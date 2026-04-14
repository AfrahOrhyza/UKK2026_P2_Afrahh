<!DOCTYPE html>
<html lang="en" class="light-style layout-menu-fixed" dir="ltr"
  data-theme="theme-default"
  data-assets-path="{{ asset('assets/') }}"
  data-template="vertical-menu-template-free">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"/>
  <title>@yield('title', 'Dashboard') | Parkir</title>
  <meta name="description" content="" />

  <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
  <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

  {{-- Bootstrap Icons --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css"/>

  <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
  <script src="{{ asset('assets/js/config.js') }}"></script>

  @stack('styles')
</head>

<body>
  <div class="layout-wrapper layout-content-navbar">
    <div class="layout-container">

      {{-- ===== SIDEBAR ===== --}}
      <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
        <div class="app-brand demo">
  <a href="{{ route('dashboard') }}" class="app-brand-link">
            <span class="app-brand-logo demo">
              {{-- Logo Sneat --}}
              <svg width="25" viewBox="0 0 25 42" xmlns="http://www.w3.org/2000/svg">
                <defs>
                  <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 Z" id="path-1"></path>
                </defs>
                <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                  <use fill="#696cff" xlink:href="#path-1"></use>
                </g>
              </svg>
            </span>
            <span class="app-brand-text demo menu-text fw-bolder ms-2">Parkir</span>
          </a>
          <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
            <i class="bx bx-chevron-left bx-sm align-middle"></i>
          </a>
        </div>

        <div class="menu-inner-shadow"></div>

        <ul class="menu-inner py-1">
          {{-- Dashboard --}}
          @php
              $role = auth()->user()->role ?? null;
          @endphp

          <li class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
              <a href="{{ route('dashboard') }}" class="menu-link">
                  <i class="menu-icon tf-icons bx bx-home-circle"></i>
                  <div>Dashboard</div>
              </a>
          </li>

          <li class="menu-header small text-uppercase">
            <span class="menu-header-text">Master Data</span>
          </li>
@php
    $role = auth()->user()->role ?? null;
@endphp

{{-- ================= ADMIN MENU SAJA ================= --}}
@if($role === 'admin')

    {{-- Kelola User --}}
    <li class="menu-item {{ request()->routeIs('user.*') ? 'active' : '' }}">
        <a href="{{ route('user.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-group"></i>
            <div>Kelola User</div>
        </a>
    </li>

    {{-- Tarif --}}
    <li class="menu-item {{ request()->routeIs('tarif.*') ? 'active' : '' }}">
        <a href="{{ route('tarif.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-dollar-circle"></i>
            <div>Tarif</div>
        </a>
    </li>

    {{-- Area Parkir --}}
    <li class="menu-item {{ request()->routeIs('area.*') ? 'active' : '' }}">
        <a href="{{ route('area.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-map-alt"></i>
            <div>Area Parkir</div>
        </a>
    </li>

    {{-- Kendaraan --}}
    <li class="menu-item {{ request()->routeIs('kendaraan.*') ? 'active' : '' }}">
        <a href="{{ route('kendaraan.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-car"></i>
            <div>Kendaraan</div>
        </a>
    </li>

    {{-- Log Aktivitas --}}
    <li class="menu-item {{ request()->routeIs('log.*') ? 'active' : '' }}">
        <a href="{{ route('log.index') }}" class="menu-link">
            <i class="menu-icon tf-icons bx bx-history"></i>
            <div>Log Aktivitas</div>
        </a>
    </li>

@endif

        </ul>
      </aside>
      {{-- ===== / SIDEBAR ===== --}}

      {{-- ===== LAYOUT PAGE ===== --}}
      <div class="layout-page">

        {{-- Navbar --}}
        <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
          <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
              <i class="bx bx-menu bx-sm"></i>
            </a>
          </div>

          <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
            <div class="navbar-nav align-items-center">
              <div class="nav-item d-flex align-items-center">
                <i class="bx bx-search fs-4 lh-0"></i>
                <input type="text" class="form-control border-0 shadow-none" placeholder="Search..." />
              </div>
            </div>

            <ul class="navbar-nav flex-row align-items-center ms-auto">
              {{-- User Dropdown --}}
              <li class="nav-item navbar-dropdown dropdown-user dropdown">
                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                  <div class="avatar avatar-online">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center"
                         style="width:40px;height:40px;">
                      <span class="text-white fw-bold">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                      </span>
                    </div>
                  </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                  <li>
                    <a class="dropdown-item" href="#">
                      <div class="d-flex">
                        <div class="flex-grow-1">
                          <span class="fw-semibold d-block">{{ auth()->user()->name ?? '' }}</span>
                          <small class="text-muted">{{ ucfirst(auth()->user()->role ?? '') }}</small>
                        </div>
                      </div>
                    </a>
                  </li>
                  <li><div class="dropdown-divider"></div></li>
                  <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit">Logout</button>
                    </form>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
        {{-- / Navbar --}}

        {{-- Content Wrapper --}}
        <div class="content-wrapper">
          <div class="container-xxl flex-grow-1 container-p-y">

            {{-- ✅ KONTEN HALAMAN DI-RENDER DI SINI --}}
            @yield('content')

          </div>

          {{-- Footer --}}
          <footer class="content-footer footer bg-footer-theme">
            <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
              <div class="mb-2 mb-md-0">
                &copy; {{ date('Y') }} Sistem Parkir
              </div>
            </div>
          </footer>

          <div class="content-backdrop fade"></div>
        </div>
        {{-- / Content Wrapper --}}

      </div>
      {{-- / Layout Page --}}

    </div>
    <div class="layout-overlay layout-menu-toggle"></div>
  </div>

  {{-- Scripts --}}
  <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
  <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
  <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
  <script src="{{ asset('assets/js/main.js') }}"></script>

  @stack('scripts')
</body>
</html>