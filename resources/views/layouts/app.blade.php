@include('layouts.head')

<body>
  <div class="container-scroller">
    @include('layouts.navbar')
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.html -->
      @include('layouts.sidebar')
      <!-- partial -->
       
        <div class="main-panel">
      @yield('content')
       @include('layouts.footer')
    </div>
    <!-- main-panel ends -->
  </div>

    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

  <!-- plugins:js -->
  <script src="{{ asset('dist/assets/vendors/js/vendor.bundle.base.js') }}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page -->
  <script src="{{ asset('dist/assets/vendors/chart.js/chart.umd.js') }}"></script>
  <script src="{{ asset('dist/assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
  <!-- End plugin js for this page -->
  <!-- inject:js -->
  <script src="{{ asset('dist/assets/js/off-canvas.js') }}"></script>
  <script src="{{ asset('dist/assets/js/misc.js') }}"></script>
  <script src="{{ asset('dist/assets/js/settings.js') }}"></script>
  <script src="{{ asset('dist/assets/js/todolist.js') }}"></script>
  <script src="{{ asset('dist/assets/js/jquery.cookie.js') }}"></script>
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="{{ asset('dist/assets/js/dashboard.js') }}"></script>
  <!-- End custom js for this page -->
  @stack('scripts')
</body>

</html>