<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- Stat boxes -->
      <div class="row">
        <div class="col-lg-3 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3>&#8369;<?= number_format($today_total, 2) ?></h3>
              <p>Today's Sales</p>
            </div>
            <div class="icon"><i class="fas fa-cash-register"></i></div>  
            <a href="<?= base_url('sales') ?>" class="small-box-footer">View Sales <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3><?= $today_count ?></h3>
              <p>Transactions Today</p>
            </div>
            <div class="icon"><i class="ion ion-bag"></i></div>
            <a href="<?= base_url('sales') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3><?= $total_products ?></h3>
              <p>Total Products</p>
            </div>
            <div class="icon"><i class="fas fa-boxes"></i></div>
            <a href="<?= base_url('products') ?>" class="small-box-footer">Manage Products <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <div class="col-lg-3 col-6">
          <div class="small-box <?= $low_stock_count > 0 ? 'bg-danger' : 'bg-secondary' ?>">
            <div class="inner">
              <h3><?= $low_stock_count ?></h3>
              <p>Low Stock Alerts</p>
            </div>
            <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
            <a href="<?= base_url('inventory') ?>" class="small-box-footer">View Inventory <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
      </div>

      <div class="row">
        <!-- Monthly Sales Chart -->
        <div class="col-lg-8">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-chart-bar mr-1"></i> Monthly Sales (Last 6 Months)</h3>
            </div>
            <div class="card-body">
              <canvas id="salesChart" height="120"></canvas>
            </div>
          </div>
        </div>

        <!-- Quick Actions -->
        <div class="col-lg-4">
          <div class="card card-warning card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-bolt mr-1"></i> Quick Actions</h3>
            </div>
            <div class="card-body p-0">
              <ul class="list-group list-group-flush">
                <li class="list-group-item">
                  <a href="<?= base_url('sales/create') ?>" class="btn btn-success btn-block">
                    <i class="fas fa-plus-circle"></i> New Sale (POS)
                  </a>
                </li>
                <li class="list-group-item">
                  <a href="<?= base_url('products') ?>" class="btn btn-primary btn-block">
                    <i class="fas fa-boxes"></i> Manage Products
                  </a>
                </li>
                <li class="list-group-item">
                  <a href="<?= base_url('inventory') ?>" class="btn btn-warning btn-block">
                    <i class="fas fa-warehouse"></i> Stock Adjustment
                  </a>
                </li>
                <li class="list-group-item">
                  <a href="<?= base_url('customers') ?>" class="btn btn-info btn-block">
                    <i class="fas fa-users"></i> Customers (<?= $total_customers ?>)
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
const monthlySales = <?= json_encode($monthly_sales) ?>;
const labels = monthlySales.map(r => r.month);
const totals = monthlySales.map(r => parseFloat(r.total));

const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
  type: 'bar',
  data: {
    labels: labels.length ? labels : ['No Data'],
    datasets: [{
      label: 'Sales (PHP)',
      data: totals.length ? totals : [0],
      backgroundColor: 'rgba(40, 167, 69, 0.6)',
      borderColor: 'rgba(40, 167, 69, 1)',
      borderWidth: 1
    }]
  },
  options: {
    scales: {
      y: { beginAtZero: true }
    },
    responsive: true
  }
});
</script>
<?= $this->endSection() ?>
