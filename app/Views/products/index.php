<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Products</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item active">Products</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Product List</h3>
              <div class="float-right">
                <button class="btn btn-primary" data-toggle="modal" data-target="#AddNewModal">
                  <i class="fa fa-plus-circle"></i> Add Product
                </button>
              </div>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped table-sm">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th style="display:none;">id</th>
                    <th>SKU</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="AddNewModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form id="addForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Add Product</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>SKU</label>
                    <input type="text" name="sku" class="form-control" placeholder="Auto-generated if blank" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Category</label>
                    <select name="category_id" class="form-control">
                      <option value="">-- Select Category --</option>
                      <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Supplier</label>
                    <select name="supplier_id" class="form-control">
                      <option value="">-- Select Supplier --</option>
                      <?php foreach ($suppliers as $sup): ?>
                        <option value="<?= $sup['id'] ?>"><?= esc($sup['name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Cost Price</label>
                    <input type="number" step="0.01" name="cost_price" class="form-control" value="0" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Selling Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" name="selling_price" class="form-control" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Unit</label>
                    <input type="text" name="unit" class="form-control" value="pcs" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Initial Stock</label>
                    <input type="number" name="stock_quantity" class="form-control" value="0" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Reorder Level</label>
                    <input type="number" name="reorder_level" class="form-control" value="5" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea name="description" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Save</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1">
      <div class="modal-dialog modal-lg">
        <form id="editForm">
          <?= csrf_field() ?>
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"><i class="far fa-edit"></i> Edit Product</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <input type="hidden" id="editId" name="id">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Product Name <span class="text-danger">*</span></label>
                    <input type="text" id="editName" name="name" class="form-control" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>SKU</label>
                    <input type="text" id="editSku" name="sku" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Category</label>
                    <select id="editCategoryId" name="category_id" class="form-control">
                      <option value="">-- Select Category --</option>
                      <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Supplier</label>
                    <select id="editSupplierId" name="supplier_id" class="form-control">
                      <option value="">-- Select Supplier --</option>
                      <?php foreach ($suppliers as $sup): ?>
                        <option value="<?= $sup['id'] ?>"><?= esc($sup['name']) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Cost Price</label>
                    <input type="number" step="0.01" id="editCostPrice" name="cost_price" class="form-control" />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Selling Price <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" id="editSellingPrice" name="selling_price" class="form-control" required />
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <label>Unit</label>
                    <input type="text" id="editUnit" name="unit" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Stock Quantity</label>
                    <input type="number" id="editStockQuantity" name="stock_quantity" class="form-control" />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label>Reorder Level</label>
                    <input type="number" id="editReorderLevel" name="reorder_level" class="form-control" />
                  </div>
                </div>
              </div>
              <div class="form-group">
                <label>Description</label>
                <textarea id="editDescription" name="description" class="form-control" rows="2"></textarea>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times-circle"></i> Cancel</button>
              <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Update</button>
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
<script src="<?= base_url('js/products/products.js') ?>"></script>
<?= $this->endSection() ?>
