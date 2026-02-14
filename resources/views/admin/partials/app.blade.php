<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- begin::Meta Basic -->
    <meta charset="utf-8">
    <meta name="theme-color" content="#316AFF">
    <meta name="robots" content="index, follow">
    <meta name="author" content="DMRC LMS">
    <meta name="format-detection" content="telephone=no">
    <meta name="keywords" content="DMRC, Learning Management System, LMS, Admin Dashboard, Education, Student Management">
    <meta name="description" content="DMRC Learning Management System Admin Dashboard - Manage students, courses, and educational content efficiently.">
    <!-- end::Meta Basic -->

    <!-- begin::Mobile Specific -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- end::Mobile Specific -->

<!-- boostrap  and boostrap icon css-->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
          <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <!-- end boostrap and icon-->


    <!-- begin::Favicon Tags -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <!-- end::Favicon Tags -->

    <!-- begin::Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <!-- end::Google Fonts -->

    <!-- begin::Required Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flaticon/css/all/all.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/lucide/lucide.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/node-waves/waves.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-select/css/bootstrap-select.min.css') }}">
    <!-- end::Required Stylesheet -->

    <!-- begin::CSS Stylesheet -->
    <link rel="stylesheet" href="{{ asset('assets/libs/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/styles.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/venue.css') }}">
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE0p4Yv5p0p1LVp0p1LVp0p1LVp0p1LVp0p1LVp0p1LVp0p1==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- end::CSS Stylesheet -->

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'DMRC LMS') }} - @yield('title', 'Admin Panel')</title>

    <!-- Vite Assets -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<style>
  .page-layout{
    display:flex;
    flex-direction: row;
   
  }
</style>
<body>
  <div class="page-layout">

    <!-- begin::Page Header -->
    <header class="app-header">
      <div class="app-header-inner">
        <button class="app-toggler" type="button" aria-label="app toggler">
          <span></span>
          <span></span>
          <span></span>
        </button>
        <div class="app-header-start d-none d-md-flex">
          <form class="d-flex align-items-center h-100 w-lg-250px w-xxl-300px position-relative" action="#">
            <button type="button" class="btn btn-sm border-0 position-absolute start-0 ms-3 p-0">
              <i class="fi fi-rr-search"></i>
            </button>
            <input type="text" class="form-control rounded-5 ps-5" placeholder="Search anything's" data-bs-toggle="modal" data-bs-target="#searchResultsModal">
          </form>
          <ul class="navbar-nav gap-4 flex-row d-none d-xxl-flex">
            <li class="nav-item">
              <a class="nav-link" href="analytics.html">Reports & Analytics</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="pages/faq.html">Help</a>
            </li>
          </ul>
        </div>
        <div class="app-header-end">
          <div class="px-lg-3 px-2 ps-0 d-flex align-items-center">
            <div class="dropdown">
              <button class="btn btn-icon btn-action-gray rounded-circle waves-effect waves-light position-relative" id="ld-theme" type="button" data-bs-auto-close="outside" aria-expanded="false" data-bs-toggle="dropdown">
                <i class="fi fi-rr-brightness scale-1x theme-icon-active"></i>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li>
                  <button type="button" class="dropdown-item d-flex gap-2 align-items-center" data-bs-theme-value="light" aria-pressed="false">
                    <i class="fi fi-rr-brightness scale-1x" data-theme="light"></i> Light
                  </button>
                </li>
                <li>
                  <button type="button" class="dropdown-item d-flex gap-2 align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                    <i class="fi fi-rr-moon scale-1x" data-theme="dark"></i> Dark
                  </button>
                </li>
                <li>
                  <button type="button" class="dropdown-item d-flex gap-2 align-items-center" data-bs-theme-value="auto" aria-pressed="true">
                    <i class="fi fi-br-circle-half-stroke scale-1x" data-theme="auto"></i> Auto
                  </button>
                </li>
              </ul>
            </div>
          </div>
          <div class="vr my-3"></div>
          <div class="d-flex align-items-center gap-sm-2 gap-0 px-lg-4 px-sm-2 px-1">
            <a href="email/inbox.html" class="btn btn-icon btn-action-gray rounded-circle waves-effect waves-light position-relative">
              <i class="fi fi-rr-envelope"></i>
              <span class="position-absolute top-0 end-0 p-1 mt-1 me-1 bg-danger border border-3 border-light rounded-circle">
                <span class="visually-hidden">New alerts</span>
              </span>
            </a>
            <div class="dropdown text-end">
              <button type="button" class="btn btn-icon btn-action-gray rounded-circle waves-effect waves-light" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
                <i class="fi fi-rr-bell"></i>
              </button>
              <div class="dropdown-menu dropdown-menu-lg-end p-0 w-300px mt-2">
                <div class="px-3 py-3 border-bottom d-flex justify-content-between align-items-center">
                  <h6 class="mb-0">Notifications <span class="badge badge-sm rounded-pill bg-primary ms-2">9</span>
                  </h6>
                  <i class="bi bi-x-lg cursor-pointer"></i>
                </div>
                <div class="p-2" style="height: 300px;" data-simplebar>
                  <ul class="list-group list-group-hover list-group-smooth list-group-unlined">
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div class="avatar avatar-xs avatar-status-success rounded-circle me-1">
                        <img src="{{ asset('assets/images/avatar/avatar2.webp') }}" alt="">
                      </div>
                      <div class="ms-2 me-auto">
                        <h6 class="mb-0">Emma Smith</h6>
                        <small class="text-body d-block">Need to update details.</small>
                        <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">7 hr ago</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div class="avatar avatar-xs bg-success rounded-circle text-white">D</div>
                      <div class="ms-2 me-auto">
                        <h6 class="mb-0">Design Team</h6>
                        <small class="text-body d-block">Check your shared folder.</small>
                        <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">6 hr ago</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div class="avatar avatar-xs bg-dark rounded-circle text-white">
                        <i class="fi fi-rr-lock"></i>
                      </div>
                      <div class="ms-2 me-auto">
                        <h6 class="mb-0">Security Update</h6>
                        <small class="text-body d-block">Password successfully set.</small>
                        <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">5 hr ago</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div class="avatar avatar-xs bg-info rounded-circle text-white">
                        <i class="fi fi-rr-shopping-cart"></i>
                      </div>
                      <div class="ms-2 me-auto">
                        <h6 class="mb-0">Invoice #1432</h6>
                        <small class="text-body d-block">has been paid Amount: $899.00</small>
                        <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">5 hr ago</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div class="avatar avatar-xs bg-danger rounded-circle text-white">R</div>
                      <div class="ms-2 me-auto">
                        <h6 class="mb-0">Emma Smith</h6>
                        <small class="text-body d-block">added you to Dashboard Analytics</small>
                        <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">5 hr ago</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div class="avatar avatar-xs avatar-status-success rounded-circle me-1">
                        <img src="{{ asset('assets/images/avatar/avatar3.webp') }}" alt="">
                      </div>
                      <div class="ms-2 me-auto">
                        <h6 class="mb-0">Olivia Clark</h6>
                        <small class="text-body d-block">You can now view the "Report".</small>
                        <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">4 hr ago</small>
                      </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                      <div class="avatar avatar-xs avatar-status-danger rounded-circle me-1">
                        <img src="{{ asset('assets/images/avatar/avatar5.webp') }}" alt="">
                      </div>
                      <div class="ms-2 me-auto">
                        <h6 class="mb-0">Isabella Walker</h6>
                        <small class="text-body d-block">@Isabella please review.</small>
                        <small class="text-muted position-absolute end-0 top-0 mt-2 me-3">2 hr ago</small>
                      </div>
                    </li>
                  </ul>
                </div>
                <div class="p-2">
                  <a href="javascript:void(0);" class="btn w-100 btn-primary waves-effect waves-light">View all notifications</a>
                </div>
              </div>
            </div>
            <a href="calendar.html" class="btn btn-icon btn-action-gray rounded-circle waves-effect waves-light">
              <i class="fi fi-rr-calendar"></i>
            </a>
          </div>
          <div class="vr my-3"></div>
          <div class="dropdown text-end ms-sm-3 ms-2 ms-lg-4">
            <a href="#" class="d-flex align-items-center py-2" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
              <div class="text-end me-2 d-none d-lg-inline-block">
                <div class="fw-bold text-dark">Robert Brown</div>
                <small class="text-body d-block lh-sm">
                  <i class="fi fi-rr-angle-down text-3xs me-1"></i> Manager
                </small>
              </div>
              <div class="avatar avatar-sm rounded-circle avatar-status-success">
                <img src="{{ asset('assets/images/avatar/avatar1.webp') }}" alt="">
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end w-225px mt-1">
              <li class="d-flex align-items-center p-2">
                <div class="avatar avatar-sm rounded-circle">
                  <img src="{{ asset('assets/images/avatar/avatar1.webp') }}" alt="">
                </div>
                <div class="ms-2">
                  <div class="fw-bold text-dark">Robert Brown </div>
                  <small class="text-body d-block lh-sm">robert@gmail.com</small>
                </div>
              </li>
              <li>
                <div class="dropdown-divider my-1"></div>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="profile.html">
                  <i class="fi fi-rr-user scale-1x"></i> View Profile
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="task-management.html">
                  <i class="fi fi-rr-note scale-1x"></i> My Task
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="pages/faq.html">
                  <i class="fi-rs-interrogation scale-1x"></i> Help Center
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="settings.html">
                  <i class="fi fi-rr-settings scale-1x"></i> Account Settings
                </a>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2" href="pages/pricing.html">
                  <i class="fi fi-rr-usd-circle scale-1x"></i> Upgrade Plan
                </a>
              </li>
              <li>
                <div class="dropdown-divider my-1"></div>
              </li>
              <li>
                <a class="dropdown-item d-flex align-items-center gap-2 text-danger" href="authentication/login-basic.html">
                  <i class="fi-sr-exit scale-1x"></i> Log Out
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </header>
    <!-- end::Page Header -->

    @include('admin.partials.sidebar')

    <!-- begin::Main Content -->
    <main class="app-main">
      @yield('content')
    </main>
    <!-- end::Main Content -->

  </div>

<!--Boostrap js-->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
</script>
<!-- end boostrap -->
    <!-- begin::GXON Page Scripts -->
    <script src="{{ asset('assets/libs/global/global.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sortable/Sortable.min.js') }}"></script>
    <script src="{{ asset('assets/libs/chartjs/chart.js') }}"></script>
    <script src="{{ asset('assets/libs/flatpickr/flatpickr.min.js') }}"></script>
    <script src="{{ asset('assets/libs/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/libs/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dashboard.js') }}"></script>
    <script src="{{ asset('assets/js/todolist.js') }}"></script>
    <script src="{{ asset('assets/js/appSettings.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
      // Menu toggle functionality
      document.addEventListener('DOMContentLoaded', function() {
        // Handle menu arrow clicks for Master Management items
        const menuArrows = document.querySelectorAll('.menu-arrow > .menu-link');
        
        menuArrows.forEach(function(menuLink) {
          menuLink.addEventListener('click', function(e) {
            e.preventDefault();
            
            const menuItem = this.parentElement;
            const submenu = menuItem.querySelector('.menu-inner');
            
            // Toggle current submenu
            if (submenu) {
              submenu.classList.toggle('show');
              menuItem.classList.toggle('open');
            }
            
            // Close other submenus in the same level
            const parentMenu = menuItem.parentElement;
            parentMenu.querySelectorAll('.menu-arrow').forEach(function(otherItem) {
              if (otherItem !== menuItem) {
                const otherSubmenu = otherItem.querySelector('.menu-inner');
                if (otherSubmenu) {
                  otherSubmenu.classList.remove('show');
                  otherItem.classList.remove('open');
                }
              }
            });
          });
        });
        
        // Handle submenu item clicks to prevent closing
        const submenuLinks = document.querySelectorAll('.menu-inner .menu-link');
        submenuLinks.forEach(function(link) {
          link.addEventListener('click', function(e) {
            // Allow navigation but prevent menu from closing
            e.stopPropagation();
          });
        });
      });
    </script>
</body>
</html>