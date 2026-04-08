<style type="text/css">
.nav-sidebar .nav-link {
    position: relative;
    transition: background 0.2s ease;
}

/* Orange left bar */
.nav-sidebar .nav-link::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 4px;
    background: blue;
    border-radius: 0 3px 3px 0;

    transform: scaleY(0);
    transform-origin: top;
    transition: transform 0.25s ease;
}

/* Show orange bar on hover & active */
.nav-sidebar .nav-link.active::before,
.nav-sidebar .nav-link:hover::before {
    transform: scaleY(1);
}

/* SUPER LIGHT GRADIENT */
.nav-sidebar .nav-link:hover,
.nav-sidebar .nav-link.active {
    background: linear-gradient(
        to right,
        rgba(255, 165, 0, 0.05),   /* extremely light orange */
        rgba(255, 165, 0, 0.01)    /* almost invisible */
    ) !important;
    box-shadow: none !important;
}

/* Submenu items same gradient */
.nav-treeview .nav-link:hover,
.nav-treeview .nav-link.active {
    background: linear-gradient(
        to right,
        rgba(255, 165, 0, 0.05),
        rgba(255, 165, 0, 0.01)
    ) !important;
    box-shadow: none !important;
}

/* Sidebar links text and icons in dark mode */
body.dark-mode .main-sidebar .nav-link {
    color: #fff !important;
}

body.dark-mode .main-sidebar .nav-link p {
    color: #fff !important;
}

body.dark-mode .main-sidebar .nav-icon {
    color: #fff !important;
}

/* Active or hovered link */
body.dark-mode .main-sidebar .nav-link.active,
body.dark-mode .main-sidebar .nav-link:hover {
    background-color: rgba(255, 255, 255, 0.1) !important; /* slightly lighter bg on hover/active */
}

</style>


<aside class="main-sidebar sidebar-light-light sidebar-light elevation-5" id="mainSidebar">
<div class="brand-link bg-warning" id="brandLink" style="cursor: default; border-bottom: 1px rgba(255, 255, 255);">
    <img src="<?= base_url('assets/adminlte/dist/img/AdminLTELogo.png') ?>" 
         alt="AdminLTE Logo" 
         class="brand-image img-circle elevation-3" 
         style="opacity: .8">
    <span class="brand-text font-weight-light" style="color: white">JL Store Sales & Inventory</span>
</div>
  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

        <!-- Dashboard -->
        <li class="nav-item">
          <a href="<?= base_url('dashboard') ?>" class="nav-link <?= is_active(1, 'dashboard') ?>">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>

        <!-- Sales -->
        <li class="nav-item <?= is_menu_open(1, ['sales']) ?>">
          <a href="#" class="nav-link <?= is_active(1, 'sales') ?>">
            <i class="nav-icon fas fa-cash-register"></i>
            <p>Sales <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('sales') ?>" class="nav-link <?= is_active(2, 'sales') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Sales List</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('sales/create') ?>" class="nav-link <?= is_active(2, 'sales/create') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>New Sale (POS)</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- Inventory -->
        <li class="nav-item">
          <a href="<?= base_url('inventory') ?>" class="nav-link <?= is_active(1, 'inventory') ?>">
            <i class="nav-icon fas fa-warehouse"></i>
            <p>Inventory</p>
          </a>
        </li>

        <!-- Products -->
        <li class="nav-item">
          <a href="<?= base_url('products') ?>" class="nav-link <?= is_active(1, 'products') ?>">
            <i class="nav-icon fas fa-boxes"></i>
            <p>Products</p>
          </a>
        </li>

        <!-- Master Data -->
        <li class="nav-item <?= is_menu_open(1, ['categories', 'suppliers', 'customers']) ?>">
          <a href="#" class="nav-link <?= is_active(1, 'categories') || is_active(1, 'suppliers') || is_active(1, 'customers') ? 'active' : '' ?>">
            <i class="nav-icon fas fa-database"></i>
            <p>Master Data <i class="right fas fa-angle-left"></i></p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="<?= base_url('categories') ?>" class="nav-link <?= is_active(2, 'categories') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Categories</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('suppliers') ?>" class="nav-link <?= is_active(2, 'suppliers') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Suppliers</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="<?= base_url('customers') ?>" class="nav-link <?= is_active(2, 'customers') ?>">
                <i class="far fa-circle nav-icon"></i>
                <p>Customers</p>
              </a>
            </li>
          </ul>
        </li>

        <!-- System -->
        <li class="nav-header">SYSTEM</li>
        <li class="nav-item">
          <a href="<?= base_url('users') ?>" class="nav-link <?= is_active(1, 'users') ?>">
            <i class="nav-icon fas fa-users-cog"></i>
            <p>User Accounts</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url('log') ?>" class="nav-link <?= is_active(1, 'log') ?>">
            <i class="nav-icon fas fa-history"></i>
            <p>Activity Logs</p>
          </a>
        </li>

      </ul>
    </nav>
  </div>
</aside>
