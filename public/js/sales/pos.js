let cart = [];
let searchTimeout = null;

function formatMoney(amount) {
    return parseFloat(amount).toFixed(2);
}

function updateTotals() {
    let subtotal = cart.reduce((sum, item) => sum + item.subtotal, 0);
    let discount = parseFloat($('#discountInput').val()) || 0;
    let tax = parseFloat($('#taxInput').val()) || 0;
    let total = subtotal - discount + tax;
    let amountPaid = parseFloat($('#amountPaid').val()) || 0;
    let change = amountPaid - total;

    $('#subtotalDisplay').text(formatMoney(subtotal));
    $('#totalDisplay').text(formatMoney(total));
    $('#changeDisplay').text(formatMoney(Math.max(0, change)));

    $('#processSaleBtn').prop('disabled', cart.length === 0 || amountPaid < total);
}

function renderCart() {
    const tbody = $('#cartBody');
    tbody.empty();

    if (cart.length === 0) {
        tbody.append(`<tr id="emptyCartRow">
            <td colspan="5" class="text-center text-muted py-3">
                <i class="fas fa-shopping-cart fa-2x mb-2 d-block"></i>Cart is empty
            </td></tr>`);
    } else {
        cart.forEach((item, idx) => {
            tbody.append(`<tr>
                <td>${item.name}</td>
                <td>
                    <input type="number" class="form-control form-control-sm qty-input"
                        min="1" max="${item.max_stock}" value="${item.quantity}"
                        data-idx="${idx}" style="width:65px;">
                </td>
                <td>₱${formatMoney(item.unit_price)}</td>
                <td>₱${formatMoney(item.subtotal)}</td>
                <td>
                    <button class="btn btn-xs btn-danger remove-btn" data-idx="${idx}">
                        <i class="fas fa-times"></i>
                    </button>
                </td>
            </tr>`);
        });
    }
    updateTotals();
}

function addToCart(product) {
    const existing = cart.findIndex(i => i.product_id === product.id);
    if (existing >= 0) {
        if (cart[existing].quantity < product.stock_quantity) {
            cart[existing].quantity++;
            cart[existing].subtotal = cart[existing].quantity * cart[existing].unit_price;
        } else {
            toastr.warning('Max stock reached for ' + product.name);
        }
    } else {
        cart.push({
            product_id: product.id,
            name: product.name,
            unit_price: parseFloat(product.selling_price),
            quantity: 1,
            subtotal: parseFloat(product.selling_price),
            max_stock: product.stock_quantity,
            unit: product.unit
        });
    }
    renderCart();
}

function renderProductCard(product) {
    const outOfStock = product.stock_quantity <= 0;
    return `<div class="col-md-4 col-sm-6 mb-2">
        <div class="card card-sm ${outOfStock ? 'bg-light' : 'product-card'}"
             style="cursor:${outOfStock ? 'default' : 'pointer'}"
             data-product='${JSON.stringify(product)}'>
            <div class="card-body p-2 text-center">
                <div class="font-weight-bold text-truncate" title="${product.name}">${product.name}</div>
                <div class="text-success font-weight-bold">₱${formatMoney(product.selling_price)}</div>
                <div class="text-muted small">
                    ${outOfStock ? '<span class="badge badge-danger">Out of Stock</span>' : `Stock: ${product.stock_quantity} ${product.unit}`}
                </div>
            </div>
        </div>
    </div>`;
}

// Product search
$('#productSearch').on('input', function () {
    clearTimeout(searchTimeout);
    const q = $(this).val().trim();
    if (q.length < 1) {
        $('#productResults').empty();
        return;
    }
    searchTimeout = setTimeout(function () {
        $.get(baseUrl + 'products/search', { q: q }, function (data) {
            const container = $('#productResults');
            container.empty();
            if (data.length === 0) {
                container.html('<div class="col-12 text-center text-muted">No products found.</div>');
            } else {
                data.forEach(p => container.append(renderProductCard(p)));
            }
        });
    }, 300);
});

// Add product on card click
$(document).on('click', '.product-card', function () {
    const product = $(this).data('product');
    if (product.stock_quantity <= 0) return;
    addToCart(product);
    toastr.success(product.name + ' added to cart.', '', { timeOut: 800 });
});

// Quantity change
$(document).on('change', '.qty-input', function () {
    const idx = $(this).data('idx');
    const val = parseInt($(this).val()) || 1;
    const max = parseInt($(this).attr('max'));
    cart[idx].quantity = Math.min(Math.max(1, val), max);
    cart[idx].subtotal = cart[idx].quantity * cart[idx].unit_price;
    renderCart();
});

// Remove item
$(document).on('click', '.remove-btn', function () {
    cart.splice($(this).data('idx'), 1);
    renderCart();
});

// Clear cart
$('#clearCart').on('click', function () {
    if (cart.length === 0) return;
    if (confirm('Clear all items from cart?')) {
        cart = [];
        renderCart();
    }
});

// Recalculate on discount/tax/payment change
$('#discountInput, #taxInput, #amountPaid').on('input', updateTotals);

// Process sale
$('#processSaleBtn').on('click', function () {
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    const total = parseFloat($('#totalDisplay').text());
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    const change = amountPaid - total;

    $.ajax({
        url: baseUrl + 'sales/store',
        method: 'POST',
        data: {
            [csrfName]: csrfToken,
            cart: JSON.stringify(cart),
            customer_id: $('#customerId').val(),
            discount: $('#discountInput').val(),
            tax: $('#taxInput').val(),
            payment_method: $('#paymentMethod').val(),
            amount_paid: amountPaid,
            notes: $('#saleNotes').val()
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#receiptInvoice').text(res.invoice_no);
                $('#receiptTotal').text(formatMoney(total));
                $('#receiptChange').text(formatMoney(Math.max(0, change)));
                $('#receiptModal').modal('show');
            } else {
                toastr.error(res.message || 'Failed to process sale.');
            }
        },
        error: function () { toastr.error('An error occurred.'); }
    });
});
