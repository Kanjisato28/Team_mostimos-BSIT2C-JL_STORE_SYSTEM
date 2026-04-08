<?= $this->extend('theme/template') ?>

<?= $this->section('content') ?>
<div class="content-wrapper">
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"><h1 class="m-0">Sale Receipt</h1></div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="<?= base_url('dashboard') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('sales') ?>">Sales</a></li>
            <li class="breadcrumb-item active"><?= esc($sale['invoice_no']) ?></li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title">
                Invoice: <strong><?= esc($sale['invoice_no']) ?></strong>
                <span class="badge badge-<?= $sale['status'] === 'completed' ? 'success' : 'danger' ?> ml-2">
                  <?= ucfirst($sale['status']) ?>
                </span>
              </h3>
              <div class="card-tools">
                <button onclick="window.print()" class="btn btn-sm btn-default">
                  <i class="fas fa-print"></i> Print
                </button>
                <a href="<?= base_url('sales') ?>" class="btn btn-sm btn-secondary">
                  <i class="fas fa-arrow-left"></i> Back
                </a>
              </div>
            </div>
            <div class="card-body">
              <div class="row mb-3">
                <div class="col-md-6">
                  <strong>Customer:</strong> <?= esc($sale['customer_name'] ?? 'Walk-in Customer') ?><br>
                  <strong>Cashier:</strong> <?= esc($sale['cashier_name'] ?? '') ?><br>
                  <strong>Date:</strong> <?= date('M d, Y', strtotime($sale['sale_date'])) ?>
                </div>
                <div class="col-md-6 text-right">
                  <strong>Payment:</strong> <?= strtoupper($sale['payment_method']) ?><br>
                  <strong>Amount Paid:</strong> &#8369;<?= number_format($sale['amount_paid'], 2) ?><br>
                  <strong>Change:</strong> &#8369;<?= number_format($sale['change_amount'], 2) ?>
                </div>
              </div>

              <table class="table table-bordered table-sm">
                <thead class="thead-light">
                  <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Unit</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Subtotal</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1; foreach ($sale['items'] as $item): ?>
                  <tr>
                    <td><?= $i++ ?></td>
                    <td><?= esc($item['product_name']) ?></td>
                    <td><?= esc($item['unit']) ?></td>
                    <td class="text-right">&#8369;<?= number_format($item['unit_price'], 2) ?></td>
                    <td class="text-right"><?= $item['quantity'] ?></td>
                    <td class="text-right">&#8369;<?= number_format($item['subtotal'], 2) ?></td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
                <tfoot>
                  <tr>
                    <td colspan="5" class="text-right"><strong>Subtotal:</strong></td>
                    <td class="text-right">&#8369;<?= number_format($sale['subtotal'], 2) ?></td>
                  </tr>
                  <?php if ($sale['discount'] > 0): ?>
                  <tr>
                    <td colspan="5" class="text-right">Discount:</td>
                    <td class="text-right">- &#8369;<?= number_format($sale['discount'], 2) ?></td>
                  </tr>
                  <?php endif; ?>
                  <?php if ($sale['tax'] > 0): ?>
                  <tr>
                    <td colspan="5" class="text-right">Tax:</td>
                    <td class="text-right">+ &#8369;<?= number_format($sale['tax'], 2) ?></td>
                  </tr>
                  <?php endif; ?>
                  <tr class="table-success">
                    <td colspan="5" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>&#8369;<?= number_format($sale['total_amount'], 2) ?></strong></td>
                  </tr>
                </tfoot>
              </table>

              <?php if ($sale['notes']): ?>
              <p><strong>Notes:</strong> <?= esc($sale['notes']) ?></p>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
<?= $this->endSection() ?>
