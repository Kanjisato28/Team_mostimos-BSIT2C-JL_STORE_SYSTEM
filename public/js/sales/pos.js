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
            </td>
        </tr>`);
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
             data-product='${JSON.stringify(product).replace(/'/g, "&apos;")}'>
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

// Show GCash QR code modal
function showGCashQR() {
    const total = parseFloat($('#totalDisplay').text());
    if (total <= 0) {
        toastr.warning('Cart total must be greater than 0');
        return;
    }
    
    if (cart.length === 0) {
        toastr.warning('Cart is empty. Please add items first.');
        return;
    }
    
    // Update amount in modal
    $('#gcashAmountInModal').text(formatMoney(total));
    
    // Show the modal
    $('#gcashPaymentModal').modal('show');
}

// Process cash sale
function processCashSale() {
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    const total = parseFloat($('#totalDisplay').text());
    
    if (amountPaid < total) {
        toastr.error(`Insufficient payment. Please pay ₱${formatMoney(total)}`);
        return false;
    }
    
    const processBtn = $('#processSaleBtn');
    const originalText = processBtn.html();
    processBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
    
    $.ajax({
        url: baseUrl + 'sales/store',
        method: 'POST',
        data: {
            [csrfName]: csrfToken,
            cart: JSON.stringify(cart),
            customer_id: $('#customerId').val(),
            discount: $('#discountInput').val(),
            tax: $('#taxInput').val(),
            payment_method: 'cash',
            amount_paid: amountPaid,
            notes: $('#saleNotes').val()
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#receiptInvoice').text(res.invoice_no);
                $('#receiptTotal').text(formatMoney(total));
                $('#receiptChange').text(formatMoney(amountPaid - total));
                $('#receiptModal').modal('show');
                
                cart = [];
                renderCart();
                $('#amountPaid').val('');
                $('#paymentMethod').val('cash');
                $('#saleNotes').val('');
                $('#discountInput').val(0);
                $('#taxInput').val(0);
                
                toastr.success('Sale completed successfully!');
            } else {
                toastr.error(res.message || 'Failed to process sale.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            toastr.error('An error occurred while processing the sale.');
        },
        complete: function() {
            processBtn.html(originalText).prop('disabled', false);
        }
    });
}

// Process GCash sale
function processGCashSale(gcashReference) {
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    const amountPaid = parseFloat($('#amountPaid').val()) || 0;
    const total = parseFloat($('#totalDisplay').text());
    
    if (amountPaid < total) {
        toastr.error(`Please pay the full amount: ₱${formatMoney(total)}`);
        return false;
    }
    
    const confirmBtn = $('#confirmGCashPayment');
    const originalText = confirmBtn.html();
    confirmBtn.html('<i class="fas fa-spinner fa-spin"></i> Processing...').prop('disabled', true);
    
    let notes = $('#saleNotes').val();
    if (gcashReference) {
        notes += (notes ? ' | ' : '') + `GCash Ref: ${gcashReference}`;
    }
    
    $.ajax({
        url: baseUrl + 'sales/store',
        method: 'POST',
        data: {
            [csrfName]: csrfToken,
            cart: JSON.stringify(cart),
            customer_id: $('#customerId').val(),
            discount: $('#discountInput').val(),
            tax: $('#taxInput').val(),
            payment_method: 'gcash',
            amount_paid: amountPaid,
            notes: notes,
            gcash_reference: gcashReference
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#gcashPaymentModal').modal('hide');
                
                $('#receiptInvoice').text(res.invoice_no);
                $('#receiptTotal').text(formatMoney(total));
                $('#receiptChange').text(formatMoney(amountPaid - total));
                $('#receiptModal').modal('show');
                
                cart = [];
                renderCart();
                $('#amountPaid').val('');
                $('#paymentMethod').val('cash');
                $('#saleNotes').val('');
                $('#discountInput').val(0);
                $('#taxInput').val(0);
                $('#gcashQRBtn').hide();
                
                toastr.success('GCash payment processed successfully!');
            } else {
                toastr.error(res.message || 'Failed to process GCash payment.');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
            toastr.error('An error occurred while processing the GCash payment.');
        },
        complete: function() {
            confirmBtn.html(originalText).prop('disabled', false);
        }
    });
}

$(document).ready(function() {
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
            }).fail(function() {
                toastr.error('Error searching products');
            });
        }, 300);
    });
    
    // Add product on card click
    $(document).on('click', '.product-card', function () {
        try {
            const product = $(this).data('product');
            if (product && product.stock_quantity > 0) {
                addToCart(product);
                toastr.success(product.name + ' added to cart.', '', { timeOut: 800 });
            }
        } catch(e) {
            console.error('Error adding product:', e);
        }
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
            $('#paymentMethod').val('cash');
            $('#amountPaid').val('');
            $('#discountInput').val(0);
            $('#taxInput').val(0);
            updateTotals();
        }
    });
    
    // Recalculate on discount/tax/payment change
    $('#discountInput, #taxInput, #amountPaid').on('input', updateTotals);
    
    // Show QR code when GCash is selected
    $('#paymentMethod').on('change', function() {
        const selectedMethod = $(this).val();
        if (selectedMethod === 'gcash') {
            if ($('#gcashQRBtn').length === 0) {
                $('#amountPaid').after(`
                    <button type="button" id="gcashQRBtn" class="btn btn-info btn-sm btn-block mt-2">
                        <i class="fas fa-qrcode"></i> Show GCash QR Code
                    </button>
                `);
                $('#gcashQRBtn').on('click', showGCashQR);
            }
            
            const total = parseFloat($('#totalDisplay').text());
            if (total > 0) {
                $('#amountPaid').val(total);
                updateTotals();
            }
        } else {
            $('#gcashQRBtn').hide();
            $('#amountPaid').val('');
            updateTotals();
        }
    });
    
    // Process sale button click
    $('#processSaleBtn').on('click', function () {
        const paymentMethod = $('#paymentMethod').val();
        
        if (cart.length === 0) {
            toastr.warning('Cart is empty. Please add items first.');
            return;
        }
        
        if (paymentMethod === 'gcash') {
            showGCashQR();
        } else {
            processCashSale();
        }
    });
    
    // Confirm GCash Payment - THIS PROCESSES THE SALE
    $(document).on('click', '#confirmGCashPayment', function() {
        const gcashReference = $('#gcashReference').val().trim();
        const total = parseFloat($('#totalDisplay').text());
        const amountPaid = parseFloat($('#amountPaid').val()) || 0;
        
        if (cart.length === 0) {
            toastr.warning('Cart is empty. Please add items first.');
            $('#gcashPaymentModal').modal('hide');
            return;
        }
        
        if (amountPaid < total) {
            toastr.error(`Please ensure the full amount (₱${formatMoney(total)}) is paid via GCash`);
            return;
        }
        
        const confirmMessage = gcashReference 
            ? `Confirm GCash payment of ₱${formatMoney(total)} with reference: ${gcashReference}?`
            : `Confirm GCash payment of ₱${formatMoney(total)}?`;
        
        if (confirm(confirmMessage)) {
            processGCashSale(gcashReference);
        }
    });
    
    // Enter key support
    $(document).on('keypress', '#gcashReference', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            $('#confirmGCashPayment').click();
        }
    });
    
    // Reset modal when closed
    $('#gcashPaymentModal').on('hidden.bs.modal', function() {
        $('#gcashReference').val('');
    });
});