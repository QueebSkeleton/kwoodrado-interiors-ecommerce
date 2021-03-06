<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <!-- Brand Logo -->
  <a href="/admin/dashboard.php" class="brand-link">
    <img src="/admin/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">Kwoodrado Int.</span>
  </a>

  <!-- Sidebar -->
  <div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
      <div class="image">
        <i class="fas fa-user-tie fa-2x text-light"></i>
      </div>
      <div class="info">
        <a href="#" class="d-block">Business Owner</a>
      </div>
    </div>

    <!-- SidebarSearch Form -->
    <div class="form-inline">
      <div class="input-group" data-widget="sidebar-search">
        <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
        <div class="input-group-append">
          <button class="btn btn-sidebar">
            <i class="fas fa-search fa-fw"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <!-- Dashboard -->
        <li class="nav-item">
          <a href="/admin/dashboard.php" class="nav-link">
            <i class="nav-icon fas fa-tachometer-alt"></i>
            <p>Dashboard</p>
          </a>
        </li>
        <!-- Orders -->
        <li class="nav-item">
          <a href="#" class="nav-link">
            <i class="nav-icon fas fa-shopping-cart"></i>
            <p>
              Orders
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>
          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="/admin/orders/all-orders.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>All</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/orders/new-orders.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>New</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/orders/processing-orders.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Processing</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/orders/delivered-orders.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Delivered</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/orders/denied-orders.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Denied</p>
              </a>
            </li>
            <li class="nav-item">
              <a href="/admin/orders/cancelled-orders.php" class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>Cancelled</p>
              </a>
            </li>
          </ul>
        </li>
        <!-- Customers -->
        <li class="nav-item">
          <a href="/admin/customers/panel.php" class="nav-link">
            <i class="nav-icon fas fa-users"></i>
            <p>Customers</p>
          </a>
        </li>
        <!-- Products -->
        <li class="nav-item">
          <a href="/admin/products/panel.php" class="nav-link">
            <i class="nav-icon fas fa-table"></i>
            <p>Products</p>
          </a>
        </li>
        <!-- Categories -->
        <li class="nav-item">
          <a href="/admin/categories/panel.php" class="nav-link">
            <i class="nav-icon fas fa-list"></i>
            <p>Categories</p>
          </a>
        </li>
        <!-- Logout -->
        <li class="nav-item">
          <a href="/admin/logout.php" class="nav-link">
            <i class="nav-icon fas fa-reply"></i>
            <p>Logout</p>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.sidebar-menu -->
  </div>
  <!-- /.sidebar -->
</aside>
