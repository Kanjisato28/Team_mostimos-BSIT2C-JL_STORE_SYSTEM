<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">JL Store Sales & Inventory Dashboard</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Dashboard</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- Total Products -->
          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #4e73df, #1cc88a); color:white;">
              <div class="inner">
                <h3>320</h3>
                <p>Total Products</p>
              </div>
              <div class="icon">
                <i class="ion ion-pricetag"></i>
              </div>
              <a href="#" class="small-box-footer" style="color:white;">View Products <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Total Sales -->
          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: #4e73df; color:white;">
              <div class="inner">
                <h3>1,250</h3>
                <p>Total Sales</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="#" class="small-box-footer" style="color:white;">View Sales <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Total Customers -->
          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: #1cc88a; color:white;">
              <div class="inner">
                <h3>85</h3>
                <p>Total Customers</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="#" class="small-box-footer" style="color:white;">View Customers <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>

          <!-- Stock Alerts -->
          <div class="col-lg-3 col-6">
            <div class="small-box" style="background: linear-gradient(135deg, #4e73df, #1cc88a); color:white;">
              <div class="inner">
                <h3>12</h3>
                <p>Stock Alerts</p>
              </div>
              <div class="icon">
                <i class="ion ion-alert-circled"></i>
              </div>
              <a href="#" class="small-box-footer" style="color:white;">Check Inventory <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
        </div>
      </div>
    </section>
</div>
<?= $this->endSection() ?>