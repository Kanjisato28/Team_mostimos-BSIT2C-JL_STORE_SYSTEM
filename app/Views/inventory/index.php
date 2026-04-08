<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Inventory Management</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item active">Inventory</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">

      <!-- Low Stock Alerts -->
      <?php if (!empty($low_stock)): ?>
      <div class="card card-danger card-outline">
        <div class="card-header">
          <h3 class="card-title"><i class="fas fa-exclamation-triangle text-danger mr-1"></i> Low Stock Alerts (<?= count($low_stock) ?>)</h3>
          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
          </div>
        </div>
        <div class="card-body p-0">
          <table class="table table-sm table-bordered table-striped mb-0">
            <thead class="thead-light">
              <tr>
                <th>Product</th>
                <th>Category</th>
                <th>Current Stock</th>
                <th>Reorder Level</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($low_stock as $item): ?>
              <tr class="<?= $item['stock_quantity'] == 0 ? 'table-danger' : 'table-warning' ?>">
                <td><?= esc($item['name']) ?></td>
                <td><?= esc($item['category_name'] ?? '-') ?></td>
                <td><strong><?= $item['stock_quantity'] ?></strong> <?= esc($item['unit']) ?></td>
                <td><?= $item['reorder_level'] ?></td>
                <td>
                  <button class="btn btn-sm btn-success adjustBtn"
                    data-id="<?= $item['id'] ?>"
                    data-name="<?= esc($item['name']) ?>"
                    data-stock="<?= $item['stock_quantity'] ?>">
                    <i class="fas fa-plus"></i> Restock
                  </button>
                </td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php endif; ?>

      <!-- Stock Adjustment History -->
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Stock Adjustment History</h3>
          <div class="float-right">
            <button class="btn btn-primary" data-toggle="modal" data-target="#adjustModal">
              <i class="fas fa-warehouse"></i> Stock Adjustment
            </button>
          </div>
        </div>
        <div class="card-body">
          <table id="example1" class="table table-bordered table-striped table-sm">
            <thead>
              <tr>
                <th>No.</th>
                <th style="display:none;">id</th>
                <th>Product</th>
                <th>SKU</th>
                <th>Type</th>
                <th>Qty</th>
                <th>Before</th>
                <th>After</th>
                <th>Reason</th>
                <th>By</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody></tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="adjustModal" tabindex="-1">
      <div class="modal-dialog">
        <form id="adjustForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fas fa-warehouse"></i> Stock Adjustment</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label>Product <span class="text-danger">*</span></label>
                <input type="hidden" id="adjustProductId" name="product_id">
                <input type="text" id="adjustProductSearch" class="form-control" placeholder="Search product..." autocomplete="off" />
                <div id="adjustProductList" class="list-group mt-1" style="display:none; position:absolute; z-index:1000; width:calc(100% - 30px);"></div>
                <small id="adjustProductName" class="text-success font-weight-bold"></small>
              </div>
              <div class="form-group">
                <label>Adjustment Type <span class="text-danger">*</span></label>
                <select name="type" id="adjustType" class="form-control" required>
                  <option value="in">Stock In (Add)</option>
                  <option value="out">Stock Out (Deduct)</option>
                  <option value="adjustment">Direct Adjustment (Set)</option>
                </select>
              </div>
              <div class="form-group">
                <label>Quantity <span class="text-danger">*</span></label>
                <input type="number" name="quantity" class="form-control" min="1" required />
              </div>
              <div class="form-group">
                <label>Reason</label>
                <input type="text" name="reason" class="form-control" placeholder="e.g. Purchase delivery, Damaged goods..." />
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Apply Adjustment</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>
<div class="toasts-top-right fixed" style="position: fixed; top: 1rem; right: 1rem; z-index: 9999;"></div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>const baseUrl = "<?= base_url() ?>";</script>
<script src="<?= base_url('js/inventory/inventory.js') ?>"></script>
<?= $this->endSection() ?>
