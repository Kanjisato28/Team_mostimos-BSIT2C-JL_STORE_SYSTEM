<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2 align-items-center">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item active"><i class="fas fa-chart-line mr-1"></i> Analytics Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      
      <!-- Two column layout: Main content on left, Quick Actions on right -->
      <div class="row">
        <!-- LEFT SIDE - Main Dashboard Content -->
        <div class="col-md-9">
          <!-- Stat boxes -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success" style="background: linear-gradient(135deg, #28a745 0%, #2ecc71 100%);">
                <div class="inner">
                  <h3>&#8369;<?= number_format($today_total, 2) ?></h3>
                  <p>Today's Sales</p>
                </div>
                <div class="icon"><i class="fas fa-chart-line"></i></div>  
                <a href="<?= base_url('sales') ?>" class="small-box-footer">View Sales <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info" style="background: linear-gradient(135deg, #17a2b8 0%, #00bcd4 100%);">
                <div class="inner">
                  <h3><?= $today_count ?></h3>
                  <p>Transactions Today</p>
                </div>
                <div class="icon"><i class="fas fa-receipt"></i></div>
                <a href="<?= base_url('sales') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-warning" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
                <div class="inner">
                  <h3><?= $total_products ?></h3>
                  <p>Total Products</p>
                </div>
                <div class="icon"><i class="fas fa-boxes"></i></div>
                <a href="<?= base_url('products') ?>" class="small-box-footer">Manage Products <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box <?= $low_stock_count > 0 ? 'bg-danger' : 'bg-secondary' ?>" style="<?= $low_stock_count > 0 ? 'background: linear-gradient(135deg, #dc3545 0%, #f44336 100%);' : '' ?>">
                <div class="inner">
                  <h3><?= $low_stock_count ?></h3>
                  <p>Low Stock Alerts</p>
                </div>
                <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                <a href="<?= base_url('inventory') ?>" class="small-box-footer">View Inventory <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>

          <!-- Row 1: Revenue Chart & Profit Margin -->
          <div class="row">
            <div class="col-lg-8">
              <div class="card card-primary card-outline">
                <div class="card-header border-0">
                  <h3 class="card-title"><i class="fas fa-chart-line mr-2 text-success"></i> Revenue Trend (Last 12 Months)</h3>
                  <div class="card-tools">
                    <div class="btn-group">
                      <button type="button" class="btn btn-sm btn-outline-success active" id="btnRevenue">Revenue</button>
                      <button type="button" class="btn btn-sm btn-outline-info" id="btnProfit">Profit</button>
                    </div>
                  </div>
                </div>
                <div class="card-body p-3">
                  <canvas id="revenueChart" height="140" style="max-height: 320px;"></canvas>
                </div>
              </div>
            </div>
            
            <div class="col-lg-4">
              <div class="card card-info card-outline">
                <div class="card-header border-0">
                  <h3 class="card-title"><i class="fas fa-percentage mr-2 text-info"></i> Profit Margin Trend</h3>
                </div>
                <div class="card-body">
                  <canvas id="marginChart" height="200"></canvas>
                  <div class="mt-3 text-center">
                    <small class="text-muted">Last 6 months profit margin trend</small>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 2: Category Sales & Top Products -->
          <div class="row">
            <div class="col-lg-4">
              <div class="card card-warning card-outline">
                <div class="card-header border-0">
                  <h3 class="card-title"><i class="fas fa-chart-pie mr-2 text-warning"></i> Sales by Category</h3>
                </div>
                <div class="card-body">
                  <canvas id="categoryChart" height="250"></canvas>
                  <div class="mt-3">
                    <?php foreach($category_sales as $cat): ?>
                      <div class="d-flex justify-content-between align-items-center mb-2">
                        <span><i class="fas fa-circle mr-2" style="color: <?= $cat['percentage'] ? '#28a745' : '#6c757d' ?>"></i> <?= $cat['name'] ?></span>
                        <span class="badge badge-success">₱<?= number_format($cat['total_sales'], 2) ?></span>
                      </div>
                    <?php endforeach; ?>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-lg-8">
              <div class="card card-success card-outline">
                <div class="card-header border-0">
                  <h3 class="card-title"><i class="fas fa-trophy mr-2 text-success"></i> Top Selling Products</h3>
                  <div class="card-tools">
                    <span class="badge badge-success p-2">Last 3 months</span>
                  </div>
                </div>
                <div class="card-body p-0">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>Product</th>
                          <th>SKU</th>
                          <th>Units Sold</th>
                          <th>Revenue</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php if(!empty($top_products)): ?>
                          <?php foreach($top_products as $product): ?>
                            <tr>
                              <td><?= esc($product['name']) ?></td>
                              <td><code><?= esc($product['sku']) ?></code></td>
                              <td><span class="badge bg-info"><?= $product['total_sold'] ?></span></td>
                              <td>₱<?= number_format($product['total_revenue'], 2) ?></td>
                            </tr>
                          <?php endforeach; ?>
                        <?php else: ?>
                          <tr><td colspan="4" class="text-center">No sales data available</td>
                        <?php endif; ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Row 3: Daily Sales for Current Month & Yearly Comparison -->
          <div class="row">
            <div class="col-lg-6">
              <div class="card card-info card-outline">
                <div class="card-header border-0">
                  <h3 class="card-title"><i class="fas fa-calendar-day mr-2 text-info"></i> Daily Sales - <?= date('F Y') ?></h3>
                </div>
                <div class="card-body">
                  <canvas id="dailyChart" height="200"></canvas>
                </div>
              </div>
            </div>
            
            <div class="col-lg-6">
              <div class="card card-secondary card-outline">
                <div class="card-header border-0">
                  <h3 class="card-title"><i class="fas fa-chart-line mr-2 text-secondary"></i> Year-over-Year Comparison</h3>
                </div>
                <div class="card-body">
                  <canvas id="yearlyChart" height="200"></canvas>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- RIGHT SIDEBAR - Quick Actions -->
        <div class="col-md-3">
          <div class="card card-warning card-outline">
            <div class="card-header border-0" style="background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);">
              <h3 class="card-title text-white"><i class="fas fa-bolt mr-2"></i> Quick Actions</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool text-white" data-card-widget="collapse">
                  <i class="fas fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="card-body">
              <div class="d-flex flex-column">
                <a href="<?= base_url('sales/create') ?>" class="btn btn-gradient-success btn-block mb-3 text-left">
                  <i class="fas fa-shopping-cart mr-2"></i> New Sale
                </a>
                <a href="<?= base_url('products') ?>" class="btn btn-gradient-primary btn-block mb-3 text-left">
                  <i class="fas fa-boxes mr-2"></i> Manage Products
                </a>
                <a href="<?= base_url('inventory') ?>" class="btn btn-gradient-warning btn-block mb-3 text-left">
                  <i class="fas fa-warehouse mr-2"></i> Stock Adjustment
                </a>
                <a href="<?= base_url('customers') ?>" class="btn btn-gradient-info btn-block mb-3 text-left">
                  <i class="fas fa-users mr-2"></i> Customers
                </a>
              </div>
            </div>
          </div>
          
          <!-- Optional: Add a small info card below quick actions -->
          <div class="card bg-gradient-info text-white">
            <div class="card-body">
              <h5 class="card-title"><i class="fas fa-chart-line"></i> Quick Stats</h5>
              <p class="card-text mt-2">
                <small>Today's Goal Progress</small><br>
                <strong><?= number_format(($today_total / max($monthly_target ?? 100000, 1)) * 100, 1) ?>%</strong>
                <div class="progress progress-xs mt-1">
                  <div class="progress-bar" style="width: <?= ($today_total / max($monthly_target ?? 100000, 1)) * 100 ?>%"></div>
                </div>
              </p>
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
// Parse PHP data to JavaScript
const revenueData = <?= json_encode($revenue_analytics) ?>;
const profitMargin = <?= json_encode($profit_margin) ?>;
const categorySales = <?= json_encode($category_sales) ?>;
const dailySales = <?= json_encode($daily_sales) ?>;
const yearlyComparison = <?= json_encode($yearly_comparison) ?>;

// 1. Revenue Chart (Line with gradient)
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
let revenueChart;

function initRevenueChart(showRevenue = true) {
    const months = revenueData.map(r => r.month);
    const revenue = revenueData.map(r => parseFloat(r.total_revenue));
    const profit = revenueData.map(r => parseFloat(r.total_profit));
    
    const datasets = [{
        label: showRevenue ? 'Revenue (₱)' : 'Profit (₱)',
        data: showRevenue ? revenue : profit,
        borderColor: showRevenue ? '#28a745' : '#17a2b8',
        backgroundColor: showRevenue ? 'rgba(40, 167, 69, 0.1)' : 'rgba(23, 162, 184, 0.1)',
        borderWidth: 3,
        pointRadius: 4,
        pointHoverRadius: 6,
        pointBackgroundColor: showRevenue ? '#28a745' : '#17a2b8',
        pointBorderColor: '#fff',
        pointBorderWidth: 2,
        tension: 0.4,
        fill: true
    }];
    
    if (revenueChart) {
        revenueChart.destroy();
    }
    
    revenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: { labels: months, datasets: datasets },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            let val = context.raw;
                            return `${label}: ₱ ${val.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",")}`;
                        }
                    }
                },
                legend: { position: 'top', labels: { usePointStyle: true, boxWidth: 8 } }
            },
            scales: {
                y: { 
                    beginAtZero: true,
                    ticks: { callback: function(value) { return '₱' + value.toLocaleString(); } }
                }
            }
        }
    });
}

// Toggle between revenue and profit
document.getElementById('btnRevenue')?.addEventListener('click', () => {
    document.getElementById('btnRevenue').classList.add('active');
    document.getElementById('btnProfit').classList.remove('active');
    initRevenueChart(true);
});
document.getElementById('btnProfit')?.addEventListener('click', () => {
    document.getElementById('btnProfit').classList.add('active');
    document.getElementById('btnRevenue').classList.remove('active');
    initRevenueChart(false);
});

// 2. Profit Margin Chart
if (profitMargin && profitMargin.length) {
    const marginCtx = document.getElementById('marginChart').getContext('2d');
    new Chart(marginCtx, {
        type: 'line',
        data: {
            labels: profitMargin.map(m => m.month),
            datasets: [{
                label: 'Profit Margin (%)',
                data: profitMargin.map(m => parseFloat(m.profit_margin)),
                borderColor: '#ffc107',
                backgroundColor: 'rgba(255, 193, 7, 0.1)',
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#ffc107',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Profit Margin: ${context.raw}%`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: { callback: function(value) { return value + '%'; } }
                }
            }
        }
    });
}

// 3. Category Sales Pie Chart
if (categorySales && categorySales.length) {
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    const colors = ['#28a745', '#17a2b8', '#ffc107', '#dc3545', '#6f42c1', '#fd7e14', '#20c997', '#e83e8c'];
    
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: categorySales.map(c => c.name),
            datasets: [{
                data: categorySales.map(c => parseFloat(c.total_sales)),
                backgroundColor: colors.slice(0, categorySales.length),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'bottom', labels: { font: { size: 11 } } },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw;
                            const percentage = categorySales[context.dataIndex]?.percentage || 0;
                            return `${label}: ₱${value.toFixed(2)} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// 4. Daily Sales Bar Chart
if (dailySales && dailySales.length) {
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'bar',
        data: {
            labels: dailySales.map(d => d.day),
            datasets: [{
                label: 'Daily Sales (₱)',
                data: dailySales.map(d => parseFloat(d.total_sales)),
                backgroundColor: 'rgba(23, 162, 184, 0.7)',
                borderColor: '#17a2b8',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Sales: ₱ ${context.raw.toFixed(2)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: function(value) { return '₱' + value.toLocaleString(); } }
                }
            }
        }
    });
}

// 5. Yearly Comparison Chart
if (yearlyComparison) {
    const yearlyCtx = document.getElementById('yearlyChart').getContext('2d');
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    
    // Create map for sales data
    const currentMap = {};
    const lastMap = {};
    
    yearlyComparison.current_year.forEach(item => { currentMap[item.month] = parseFloat(item.total); });
    yearlyComparison.last_year.forEach(item => { lastMap[item.month] = parseFloat(item.total); });
    
    const currentData = months.map(m => currentMap[m] || 0);
    const lastData = months.map(m => lastMap[m] || 0);
    
    new Chart(yearlyCtx, {
        type: 'line',
        data: {
            labels: months,
            datasets: [
                {
                    label: yearlyComparison.current_year_label,
                    data: currentData,
                    borderColor: '#28a745',
                    backgroundColor: 'rgba(40, 167, 69, 0.1)',
                    borderWidth: 3,
                    pointRadius: 3,
                    pointBackgroundColor: '#28a745',
                    tension: 0.3,
                    fill: true
                },
                {
                    label: yearlyComparison.last_year_label,
                    data: lastData,
                    borderColor: '#ffc107',
                    backgroundColor: 'rgba(255, 193, 7, 0.1)',
                    borderWidth: 3,
                    pointRadius: 3,
                    pointBackgroundColor: '#ffc107',
                    tension: 0.3,
                    fill: true
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `${context.dataset.label}: ₱ ${context.raw.toFixed(2)}`;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { callback: function(value) { return '₱' + value.toLocaleString(); } }
                }
            }
        }
    });
}

// Initialize revenue chart
initRevenueChart(true);
</script>

<style>
.btn-gradient-success, .btn-gradient-primary, .btn-gradient-warning, .btn-gradient-info {
    transition: all 0.3s ease;
}
.btn-gradient-success:hover, .btn-gradient-primary:hover, 
.btn-gradient-warning:hover, .btn-gradient-info:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
.table-hover tbody tr:hover {
    background-color: rgba(40, 167, 69, 0.05);
    cursor: pointer;
}
/* Right sidebar styling */
.card-warning .card-header {
    border-bottom: none;
}
.btn-block {
    border-radius: 8px;
    font-weight: 500;
}
.d-flex.flex-column {
    gap: 0.5rem;
}
</style>
<?= $this->endSection() ?>