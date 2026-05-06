<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Point of Sale</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('sales') ?>">Sales</a></li>
            <li class="breadcrumb-item active">New Sale</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row">

        <!-- Left: Product Search -->
        <div class="col-lg-7">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-search mr-1"></i> Search Products</h3>
            </div>
            <div class="card-body">
              <div class="input-group mb-3">
                <input type="text" id="productSearch" class="form-control form-control-lg" placeholder="Search by name or SKU...">
                <div class="input-group-append">
                  <span class="input-group-text"><i class="fas fa-search"></i></span>
                </div>
              </div>
              <div id="productResults" class="row"></div>
            </div>
          </div>
        </div>

        <!-- Right: Cart & Payment -->
        <div class="col-lg-5">
          <div class="card card-success card-outline">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-shopping-cart mr-1"></i> Cart</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-sm btn-danger" id="clearCart">
                  <i class="fas fa-trash"></i> Clear
                </button>
              </div>
            </div>
            <div class="card-body p-0">
              <table class="table table-sm table-bordered mb-0" id="cartTable">
                <thead class="thead-light">
                  <tr>
                    <th>Product</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Sub</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody id="cartBody">
                  <tr id="emptyCartRow">
                    <td colspan="5" class="text-center text-muted py-3">
                      <i class="fas fa-shopping-cart fa-2x mb-2 d-block"></i>
                      Cart is empty
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="card-footer">
              <!-- Customer -->
              <div class="form-group mb-2">
                <label class="font-weight-bold">Customer</label>
                <select id="customerId" class="form-control form-control-sm">
                  <option value="">-- Walk-in Customer --</option>
                  <?php foreach ($customers as $c): ?>
                    <option value="<?= $c['id'] ?>"><?= esc($c['name']) ?></option>
                  <?php endforeach; ?>
                </select>
              </div>

              <!-- Totals -->
              <table class="table table-sm table-borderless mb-2">
                <tr>
                  <td>Subtotal:</td>
                  <td class="text-right font-weight-bold">&#8369;<span id="subtotalDisplay">0.00</span></td>
                </tr>
                <tr>
                  <td>Discount:</td>
                  <td class="text-right">
                    <input type="number" id="discountInput" class="form-control form-control-sm text-right" value="0" min="0" step="0.01" style="width:100px;display:inline-block;">
                  </td>
                </tr>
                <tr>
                  <td>Tax:</td>
                  <td class="text-right">
                    <input type="number" id="taxInput" class="form-control form-control-sm text-right" value="0" min="0" step="0.01" style="width:100px;display:inline-block;">
                  </td>
                </tr>
                <tr class="bg-success text-white">
                  <td class="font-weight-bold">TOTAL:</td>
                  <td class="text-right font-weight-bold" style="font-size:1.2em;">&#8369;<span id="totalDisplay">0.00</span></td>
                </tr>
              </table>

              <!-- Payment -->
              <div class="row">
                <div class="col-6">
                  <div class="form-group mb-1">
                    <label class="font-weight-bold">Payment Method</label>
                    <select id="paymentMethod" class="form-control form-control-sm">
                      <option value="cash">Cash</option>
                      <option value="gcash">GCash</option>
                    </select>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group mb-1">
                    <label class="font-weight-bold">Amount Paid</label>
                    <input type="number" id="amountPaid" class="form-control form-control-sm" value="0" min="0" step="0.01" />
                  </div>
                </div>
              </div>
              <div class="alert alert-info p-2 mb-2">
                Change: <strong>&#8369;<span id="changeDisplay">0.00</span></strong>
              </div>
              <div class="form-group mb-2">
                <label>Notes</label>
                <input type="text" id="saleNotes" class="form-control form-control-sm" placeholder="Optional notes..." />
              </div>

              <button type="button" id="processSaleBtn" class="btn btn-success btn-block btn-lg" disabled>
                <i class="fas fa-check-circle"></i> Process Sale
              </button>
              <a href="<?= base_url('sales') ?>" class="btn btn-secondary btn-block">
                <i class="fas fa-arrow-left"></i> Cancel
              </a>
            </div>
          </div>
        </div>

      </div>
    </div>
  </section>
</div>

<!-- Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" data-backdrop="static">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-success text-white">
        <h5 class="modal-title"><i class="fas fa-check-circle"></i> Sale Completed!</h5>
      </div>
      <div class="modal-body text-center">
        <p class="lead">Invoice: <strong id="receiptInvoice"></strong></p>
        <p>Total: <strong>&#8369;<span id="receiptTotal"></span></strong></p>
        <p>Change: <strong>&#8369;<span id="receiptChange"></span></strong></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" onclick="window.location.href='<?= base_url('sales') ?>'">
          <i class="fas fa-list"></i> View Sales
        </button>
        <button type="button" class="btn btn-success" onclick="window.location.href='<?= base_url('sales/create') ?>'">
          <i class="fas fa-plus"></i> New Sale
        </button>
      </div>
    </div>
  </div>
</div>
<!-- GCash Payment Modal - Fixed Version -->
<div class="modal fade" id="gcashPaymentModal" tabindex="-1" data-backdrop="static">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title">
                    <i class="fab fa-gcash"></i> GCash Payment
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <p class="mb-3">Scan this QR code using your GCash app to pay</p>
                
                <!-- QR CODE with proper fallback using canvas (more reliable) -->
                <div class="text-center mb-3" id="qrCodeContainer">
                    <img id="gcashQrImg" 
                         src="<?= base_url('assets/img/qr.png') ?>" 
                         alt="GCash QR Code"
                         style="max-width: 180px; width: 100%; height: auto; border-radius: 10px; cursor: pointer;"
                         onerror="this.onerror=null; this.style.display='none'; showFallbackQR();">
                    <div id="fallbackQR" style="display: none;"></div>
                </div>
                
                <div class="alert alert-info small">
                    <i class="fas fa-info-circle"></i> Amount: <strong>₱<span id="gcashAmountInModal">0.00</span></strong>
                </div>
                
                <div class="form-group">
                    <label>GCash Reference Number (Optional)</label>
                    <input type="text" id="gcashReference" class="form-control form-control-sm" 
                           placeholder="Enter reference number after payment">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmGCashPayment">
                    <i class="fas fa-check"></i> Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Function to show fallback QR using canvas (clean and reliable)
function showFallbackQR() {
    const container = document.getElementById('fallbackQR');
    const amount = parseFloat($('#totalDisplay').text()) || 13599;
    
    container.style.display = 'block';
    container.innerHTML = '';
    
    // Create canvas element for fallback QR
    const canvas = document.createElement('canvas');
    canvas.width = 180;
    canvas.height = 180;
    canvas.style.maxWidth = '180px';
    canvas.style.width = '100%';
    canvas.style.height = 'auto';
    canvas.style.borderRadius = '10px';
    
    const ctx = canvas.getContext('2d');
    
    // Background
    ctx.fillStyle = '#007b5e';
    ctx.fillRect(0, 0, 180, 180);
    
    // Inner white circle
    ctx.beginPath();
    ctx.arc(90, 90, 70, 0, 2 * Math.PI);
    ctx.fillStyle = '#ffffff';
    ctx.fill();
    
    // GCash text
    ctx.fillStyle = '#007b5e';
    ctx.font = 'bold 20px Arial';
    ctx.textAlign = 'center';
    ctx.fillText('GCash', 90, 82);
    
    // Amount
    ctx.font = 'bold 14px Arial';
    ctx.fillStyle = '#007b5e';
    ctx.fillText('₱' + formatMoney(amount), 90, 112);
    
    // Scan text
    ctx.font = '10px Arial';
    ctx.fillStyle = '#666';
    ctx.fillText('Scan to Pay', 90, 135);
    
    // Add some decorative dots (simulating QR pattern)
    ctx.fillStyle = '#007b5e';
    // Top left corner
    ctx.fillRect(15, 15, 8, 8);
    ctx.fillRect(25, 15, 8, 8);
    ctx.fillRect(15, 25, 8, 8);
    // Top right corner
    ctx.fillRect(157, 15, 8, 8);
    ctx.fillRect(147, 15, 8, 8);
    ctx.fillRect(157, 25, 8, 8);
    // Bottom left corner
    ctx.fillRect(15, 157, 8, 8);
    ctx.fillRect(25, 157, 8, 8);
    ctx.fillRect(15, 147, 8, 8);
    // Bottom right corner
    ctx.fillRect(157, 157, 8, 8);
    ctx.fillRect(147, 157, 8, 8);
    ctx.fillRect(157, 147, 8, 8);
    
    container.appendChild(canvas);
}

// Update GCash modal amount
function updateGCashModalAmount() {
    const total = parseFloat($('#totalDisplay').text()) || 0;
    $('#gcashAmountInModal').text(formatMoney(total));
    
    // Update fallback QR if it's visible
    const qrImg = $('#gcashQrImg');
    const fallbackDiv = $('#fallbackQR');
    
    if (qrImg.is(':visible') === false || qrImg.css('display') === 'none') {
        showFallbackQR();
    }
}
</script>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>const baseUrl = "<?= base_url() ?>";</script>
<script src="<?= base_url('js/sales/pos.js') ?>"></script>
<?= $this->endSection() ?>
