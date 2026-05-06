<!DOCTYPE html>
<html lang="en" style="font-size: 14px;">
<head>
  <meta name="csrf-name" content="<?= csrf_token() ?>">
  <meta name="csrf-token" content="<?= csrf_hash() ?>">
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>BMIS | Dashboard</title>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700&display=fallback">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/fontawesome-free/css/all.min.css') ?>">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/dist/css/adminlte.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/daterangepicker/daterangepicker.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/summernote/summernote-bs4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>">
  <link rel="stylesheet" href="<?= base_url('assets/adminlte/plugins/toastr/toastr.min.css') ?>">
  <style>
    /* Enhanced UI Styles - Smooth & Modern */
    :root {
      --primary-gradient: linear-gradient(135deg, #28a745 0%, #20c997 100%);
      --info-gradient: linear-gradient(135deg, #17a2b8 0%, #00bcd4 100%);
      --warning-gradient: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
      --danger-gradient: linear-gradient(135deg, #dc3545 0%, #f44336 100%);
      --card-shadow: 0 10px 20px rgba(0,0,0,0.05), 0 6px 6px rgba(0,0,0,0.03);
      --card-hover-shadow: 0 20px 25px -12px rgba(0,0,0,0.1);
      --transition-smooth: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
    }

    body {
      font-family: 'Inter', 'Source Sans Pro', sans-serif;
      background: #f5f7fa;
    }

    /* Enhanced Small Boxes */
    .small-box {
      border-radius: 20px;
      overflow: hidden;
      box-shadow: var(--card-shadow);
      transition: var(--transition-smooth);
      position: relative;
      backdrop-filter: blur(2px);
    }

    .small-box:hover {
      transform: translateY(-6px);
      box-shadow: var(--card-hover-shadow);
    }

    .small-box .inner {
      padding: 20px 16px;
    }

    .small-box h3 {
      font-size: 2rem;
      font-weight: 700;
      letter-spacing: -0.5px;
      margin-bottom: 4px;
    }

    .small-box p {
      font-size: 0.9rem;
      font-weight: 500;
      text-transform: uppercase;
      letter-spacing: 0.5px;
      opacity: 0.9;
    }

    .small-box .icon {
      font-size: 4rem;
      transition: var(--transition-smooth);
      opacity: 0.25;
      right: 10px;
      top: 15px;
    }

    .small-box:hover .icon {
      transform: scale(1.1);
      opacity: 0.35;
    }

    .small-box .small-box-footer {
      padding: 8px 16px;
      background: rgba(0,0,0,0.08);
      font-weight: 500;
      transition: var(--transition-smooth);
    }

    .small-box .small-box-footer:hover {
      background: rgba(0,0,0,0.15);
      padding-left: 24px;
    }

    /* Card Enhancements */
    .card {
      border-radius: 20px;
      box-shadow: var(--card-shadow);
      transition: var(--transition-smooth);
      border: none;
      margin-bottom: 1.8rem;
    }

    .card:hover {
      box-shadow: var(--card-hover-shadow);
    }

    .card-header {
      background: transparent;
      border-bottom: 2px solid rgba(0,0,0,0.05);
      padding: 1.2rem 1.5rem;
      font-weight: 600;
    }

    .card-header .card-title {
      font-weight: 700;
      font-size: 1.2rem;
      letter-spacing: -0.3px;
    }

    .card-body {
      padding: 1.5rem;
    }

    /* Gradient buttons for Quick Actions */
    .btn-gradient-success {
      background: var(--primary-gradient);
      border: none;
      transition: var(--transition-smooth);
      font-weight: 600;
      letter-spacing: 0.3px;
      border-radius: 40px;
      padding: 12px;
    }

    .btn-gradient-primary {
      background: var(--info-gradient);
      border: none;
      transition: var(--transition-smooth);
      font-weight: 600;
      border-radius: 40px;
      padding: 12px;
    }

    .btn-gradient-warning {
      background: var(--warning-gradient);
      border: none;
      transition: var(--transition-smooth);
      font-weight: 600;
      border-radius: 40px;
      padding: 12px;
      color: #212529;
    }

    .btn-gradient-info {
      background: linear-gradient(135deg, #6f42c1 0%, #007bff 100%);
      border: none;
      transition: var(--transition-smooth);
      font-weight: 600;
      border-radius: 40px;
      padding: 12px;
    }

    .btn-gradient-success:hover, .btn-gradient-primary:hover, 
    .btn-gradient-warning:hover, .btn-gradient-info:hover {
      transform: translateY(-2px);
      filter: brightness(1.02);
      box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .list-group-item {
      border: none;
      background: transparent;
      padding: 0.8rem 1.25rem;
      transition: var(--transition-smooth);
    }

    .list-group-item:hover {
      background: rgba(0,0,0,0.02);
      padding-left: 1.5rem;
    }

    /* Chart container enhancement */
    canvas {
      border-radius: 16px;
      background: #ffffff;
      padding: 8px;
    }

    /* Breadcrumb modern */
    .breadcrumb {
      background: rgba(255,255,255,0.7);
      border-radius: 40px;
      padding: 0.5rem 1.2rem;
      backdrop-filter: blur(4px);
    }

    .content-header h1 {
      font-weight: 800;
      background: linear-gradient(135deg, #2c3e50, #3498db);
      -webkit-background-clip: text;
      background-clip: text;
      color: transparent;
      letter-spacing: -0.5px;
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

    /* Dark mode adjustments */
    body.dark-mode .card {
      background: #2d2d2d !important;
      color: #e0e0e0;
    }
    body.dark-mode .breadcrumb {
      background: rgba(0,0,0,0.4);
    }
    body.dark-mode .list-group-item {
      color: #ddd;
    }
    body.dark-mode .list-group-item:hover {
      background: rgba(255,255,255,0.05);
    }
    body.dark-mode canvas {
      background: #1e1e1e;
    }
    body.dark-mode .content-header h1 {
      background: linear-gradient(135deg, #f0f0f0, #80cbc4);
      -webkit-background-clip: text;
      background-clip: text;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <?= $this->include('theme/navbar') ?>
  <?= $this->include('theme/sidebar') ?>

  <!-- Main content area -->
  <?= $this->renderSection('content') ?>

  <footer class="main-footer no-print">
    <strong>Copyright &copy; 2025 <a href="#">Glenn IT Solutions</a> </strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> CI4.v1
    </div>
  </footer>
  <aside class="control-sidebar control-sidebar-dark">
    <div class="p-3">
      <h5>Settings</h5>
      <hr>
      <div class="form-group">
        <label>Option 1</label>
        <input type="checkbox" class="form-control">
      </div>
      <div class="form-group">
        <label>Option 2</label>
        <input type="checkbox" class="form-control">
      </div>
    </div>
  </aside>
</div>

<script src="<?= base_url('assets/adminlte/plugins/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<script src="<?= base_url('assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/chart.js/Chart.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/sparklines/sparkline.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jqvmap/jquery.vmap.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jquery-knob/jquery.knob.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/moment/moment.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/daterangepicker/daterangepicker.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/summernote/summernote-bs4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/dist/js/adminlte.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/dist/js/pages/dashboard.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables/jquery.dataTables.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/jszip/jszip.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/pdfmake/pdfmake.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/pdfmake/vfs_fonts.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/buttons.print.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') ?>"></script>
<script src="<?= base_url('assets/adminlte/plugins/toastr/toastr.min.js') ?>"></script>
<!-- QR Code Generator Library -->
<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<?= $this->renderSection('scripts') ?>

<script>
// Enhanced Theme Toggle with improved UI
const themeToggle = document.getElementById('themeToggle');
const navbar = document.getElementById('mainNavbar');
const sidebar = document.getElementById('mainSidebar');
const brandLink = document.getElementById('brandLink');

// Apply saved theme on load with micro-animations
let savedTheme = localStorage.getItem('adminlteTheme');
if(savedTheme === 'dark'){
    document.body.classList.add('dark-mode');
    navbar.classList.remove('navbar-warning');
    navbar.classList.add('navbar-dark','bg-dark');
    sidebar.classList.remove('sidebar-light');
    sidebar.classList.add('sidebar-dark-primary');
    brandLink.classList.remove('bg-warning');
    brandLink.classList.add('bg-dark');
    if(themeToggle) themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
} else {
    navbar.classList.add('navbar-warning');
    sidebar.classList.remove('sidebar-dark-primary');
    sidebar.classList.add('sidebar-light');
    brandLink.classList.remove('bg-dark');
    brandLink.classList.add('bg-warning');
    if(themeToggle) themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
}

if(themeToggle) {
    themeToggle.addEventListener('click', function(e){
        e.preventDefault();
        if(document.body.classList.contains('dark-mode')){
            document.body.classList.remove('dark-mode');
            navbar.classList.remove('navbar-dark','bg-dark');
            navbar.classList.add('navbar-warning');
            sidebar.classList.remove('sidebar-dark-primary');
            sidebar.classList.add('sidebar-light');
            brandLink.classList.remove('bg-dark');
            brandLink.classList.add('bg-warning');
            themeToggle.innerHTML = '<i class="fas fa-sun"></i>';
            localStorage.setItem('adminlteTheme','light');
            if(typeof toastr !== 'undefined') toastr.success('Light mode activated', 'Theme');
        } else {
            document.body.classList.add('dark-mode');
            navbar.classList.remove('navbar-warning');
            navbar.classList.add('navbar-dark','bg-dark');
            sidebar.classList.remove('sidebar-light');
            sidebar.classList.add('sidebar-dark-primary');
            brandLink.classList.remove('bg-warning');
            brandLink.classList.add('bg-dark');
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>';
            localStorage.setItem('adminlteTheme','dark');
            if(typeof toastr !== 'undefined') toastr.success('Dark mode activated', 'Theme');
        }
    });
}

// Optional: Add greeting based on time
$(document).ready(function() {
    const hour = new Date().getHours();
    let greeting = "Good morning";
    if (hour >= 12 && hour < 18) greeting = "Good afternoon";
    if (hour >= 18) greeting = "Good evening";
    const dashboardTitle = $('.content-header h1');
    if(dashboardTitle.length && dashboardTitle.text().trim() === "Dashboard") {
        dashboardTitle.html(`${greeting}, <span style="background: linear-gradient(135deg,#28a745,#20c997); -webkit-background-clip:text; background-clip:text; color:transparent;">Admin</span>`);
    }
});
</script>
</body>
</html>