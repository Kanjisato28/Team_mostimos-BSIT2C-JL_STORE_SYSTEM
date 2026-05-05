<style type="text/css">
/* ===== SIDEBAR ENHANCEMENTS ===== */

/* Sidebar base styling */
.main-sidebar {
    transition: all 0.3s ease;
    box-shadow: 2px 0 8px rgba(0, 0, 0, 0.05);
}

/* Brand area enhancement */
.brand-link {
    transition: all 0.3s ease;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2) !important;
    background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%) !important;
}

.brand-image {
    transition: transform 0.2s ease;
}

.brand-link:hover .brand-image {
    transform: scale(1.05);
}

.brand-text {
    font-weight: 500 !important;
    letter-spacing: 0.5px;
}

/* Navigation links */
.nav-sidebar .nav-link {
    position: relative;
    transition: all 0.25s ease;
    margin: 4px 8px;
    border-radius: 10px;
    padding: 10px 16px;
}

/* Orange left accent bar - more modern */
.nav-sidebar .nav-link::before {
    content: "";
    position: absolute;
    left: 0;
    top: 50%;
    height: 0;
    width: 4px;
    background: linear-gradient(135deg, #ff8c00, #ff6b00);
    border-radius: 0 4px 4px 0;
    transform: translateY(-50%);
    transition: all 0.25s ease;
    opacity: 0;
}

/* Show accent bar on hover & active */
.nav-sidebar .nav-link.active::before,
.nav-sidebar .nav-link:hover::before {
    height: 55%;
    opacity: 1;
}

/* Enhanced gradient background */
.nav-sidebar .nav-link:hover,
.nav-sidebar .nav-link.active {
    background: linear-gradient(
        98deg,
        rgba(255, 140, 0, 0.08) 0%,
        rgba(255, 107, 0, 0.02) 100%
    ) !important;
    box-shadow: 0 2px 6px rgba(255, 140, 0, 0.08) !important;
}

/* Submenu items */
.nav-treeview .nav-link {
    padding: 8px 16px 8px 40px !important;
    margin: 2px 8px;
    border-radius: 8px;
}

.nav-treeview .nav-link:hover,
.nav-treeview .nav-link.active {
    background: linear-gradient(
        98deg,
        rgba(255, 140, 0, 0.06) 0%,
        rgba(255, 107, 0, 0.01) 100%
    ) !important;
}

.nav-treeview .nav-link::before {
    left: 16px;
    width: 3px;
}

/* Icon enhancements */
.nav-sidebar .nav-icon {
    transition: all 0.25s ease;
    font-size: 1.1rem;
}

.nav-sidebar .nav-link:hover .nav-icon,
.nav-sidebar .nav-link.active .nav-icon {
    transform: translateX(2px);
    color: #ff8c00 !important;
}

/* Chevron animation */
.nav-sidebar .nav-link .right {
    transition: transform 0.25s ease;
}

.nav-sidebar .nav-item.menu-open > .nav-link .right {
    transform: rotate(-90deg);
}

/* Header styling */
.nav-header {
    font-size: 0.7rem;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    font-weight: 600;
    padding: 16px 16px 8px 16px;
    color: #8c8c8c !important;
}

/* ===== DARK MODE ENHANCEMENTS ===== */
body.dark-mode .main-sidebar {
    background: linear-gradient(180deg, #1a1d21 0%, #0f1115 100%);
}

body.dark-mode .main-sidebar .nav-link {
    color: #e0e0e0 !important;
}

body.dark-mode .main-sidebar .nav-link p {
    color: #e0e0e0 !important;
}

body.dark-mode .main-sidebar .nav-icon {
    color: #a0a0a0 !important;
}

body.dark-mode .main-sidebar .nav-link.active .nav-icon,
body.dark-mode .main-sidebar .nav-link:hover .nav-icon {
    color: #ff9a2e !important;
}

body.dark-mode .main-sidebar .nav-link.active,
body.dark-mode .main-sidebar .nav-link:hover {
    background: linear-gradient(
        98deg,
        rgba(255, 140, 0, 0.12) 0%,
        rgba(255, 107, 0, 0.03) 100%
    ) !important;
}

body.dark-mode .nav-header {
    color: #6c6e74 !important;
}

/* Active link text color enhancement */
.nav-sidebar .nav-link.active {
    color: #ff8c00 !important;
    font-weight: 500;
}

body.dark-mode .nav-sidebar .nav-link.active {
    color: #ff9a2e !important;
}

/* Subtle scrollbar for sidebar */
.sidebar {
    scrollbar-width: thin;
    scrollbar-color: #ff8c40 #e0e0e0;
}

.sidebar::-webkit-scrollbar {
    width: 5px;
}

.sidebar::-webkit-scrollbar-track {
    background: #e0e0e0;
    border-radius: 10px;
}

.sidebar::-webkit-scrollbar-thumb {
    background: #ff8c40;
    border-radius: 10px;
}

body.dark-mode .sidebar::-webkit-scrollbar-track {
    background: #2a2d31;
}

body.dark-mode .sidebar::-webkit-scrollbar-thumb {
    background: #ff9a2e;
}

/* Tooltip effect on collapsed sidebar (if AdminLTE mini-sidebar is enabled) */
.sidebar-mini .nav-link:hover {
    border-radius: 10px;
}

/* Ripple effect on click */
.nav-link {
    position: relative;
    overflow: hidden;
}

.nav-link:after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 0;
    height: 0;
    border-radius: 50%;
    background: rgba(255, 140, 0, 0.2);
    transform: translate(-50%, -50%);
    transition: width 0.4s ease, height 0.4s ease;
}

.nav-link:active:after {
    width: 120px;
    height: 120px;
}
</style>

<aside class="main-sidebar sidebar-light-primary elevation-5" id="mainSidebar">
    <div class="brand-link" id="brandLink" style="cursor: default; border-bottom: 1px solid rgba(255,255,255,0.15);">
        <img src="<?= base_url('assets/adminlte/dist/img/AdminLTELogo.png') ?>" 
             alt="JL Store Logo" 
             class="brand-image img-circle elevation-3" 
             style="opacity: .9">
        <span class="brand-text font-weight-bold" style="color: white; letter-spacing: 0.5px;">JL Store</span>
        <small style="display: block; color: rgba(255,255,255,0.8); font-size: 10px; margin-top: 2px;">Sales & Inventory</small>
    </div>
    
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <!-- Dashboard -->
                <li class="nav-item">
                    <a href="<?= base_url('dashboard') ?>" class="nav-link <?= is_active(1, 'dashboard') ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                        <?php if(is_active(1, 'dashboard') == 'active'): ?>
                            <span class="badge badge-warning ml-auto" style="background: #ff8c00;">Live</span>
                        <?php endif; ?>
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
                                <span class="badge badge-info ml-auto" style="background: #17a2b8;">Data</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="<?= base_url('sales/create') ?>" class="nav-link <?= is_active(2, 'sales/create') ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>New Sale (POS)</p>
                                <span class="badge badge-success ml-auto" style="background: #28a745;">+</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Inventory -->
                <li class="nav-item">
                    <a href="<?= base_url('inventory') ?>" class="nav-link <?= is_active(1, 'inventory') ?>">
                        <i class="nav-icon fas fa-warehouse"></i>
                        <p>Inventory</p>
                        <?php if(is_active(1, 'inventory') == 'active'): ?>
                            <i class="fas fa-check-circle ml-auto" style="color: #28a745; font-size: 12px;"></i>
                        <?php endif; ?>
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

                <!-- System Header with Divider -->
                <li class="nav-header">
                    <span>SYSTEM</span>
                </li>
                
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

                <!-- Optional: Add a subtle separator at bottom -->
                <li class="nav-item mt-4">
                    <div class="text-center small text-muted" style="opacity: 0.5; font-size: 10px;">
                        <i class="fas fa-code-branch"></i> v1.0
                    </div>
                </li>

            </ul>
        </nav>
    </div>
</aside>