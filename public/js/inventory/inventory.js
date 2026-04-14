function showToast(type, message) {
    type === 'success' ? toastr.success(message, 'Success') : toastr.error(message, 'Error');
}

// Open adjust modal from low stock alert button
$(document).on('click', '.adjustBtn', function () {
    const id = $(this).data('id');
    const name = $(this).data('name');
    $('#adjustProductId').val(id);
    $('#adjustProductSearch').val(name);
    $('#adjustProductName').text(name);
    $('#adjustType').val('in');
    $('#adjustModal').modal('show');
});

// Product search in adjustment modal
let searchTimer = null;
$('#adjustProductSearch').on('input', function () {
    clearTimeout(searchTimer);
    const q = $(this).val().trim();
    $('#adjustProductId').val('');
    $('#adjustProductName').text('');
    if (q.length < 1) {
        $('#adjustProductList').hide().empty();
        return;
    }
    searchTimer = setTimeout(function () {
        $.get(baseUrl + 'products/search', { q: q }, function (data) {
            const list = $('#adjustProductList');
            list.empty();
            if (data.length === 0) {
                list.hide();
                return;
            }
            data.forEach(function (p) {
                list.append(`<a href="#" class="list-group-item list-group-item-action py-1 px-2 productOption"
                    data-id="${p.id}" data-name="${p.name}" data-stock="${p.stock_quantity}">
                    <strong>${p.name}</strong> <small class="text-muted">(Stock: ${p.stock_quantity} ${p.unit})</small>
                </a>`);
            });
            list.show();
        });
    }, 300);
});

$(document).on('click', '.productOption', function (e) {
    e.preventDefault();
    const id = $(this).data('id');
    const name = $(this).data('name');
    $('#adjustProductId').val(id);
    $('#adjustProductSearch').val(name);
    $('#adjustProductName').text('Selected: ' + name);
    $('#adjustProductList').hide().empty();
});

$(document).on('click', function (e) {
    if (!$(e.target).closest('#adjustProductSearch, #adjustProductList').length) {
        $('#adjustProductList').hide();
    }
});

$('#adjustForm').on('submit', function (e) {
    e.preventDefault();
    if (!$('#adjustProductId').val()) {
        showToast('error', 'Please select a product.');
        return;
    }
    $.ajax({
        url: baseUrl + 'inventory/adjust',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#adjustModal').modal('hide');
                $('#adjustForm')[0].reset();
                $('#adjustProductName').text('');
                $('#adjustProductList').hide();
                showToast('success', res.message + ' New stock: ' + res.new_stock);
                setTimeout(() => location.reload(), 1200);
            } else {
                showToast('error', res.message || 'Adjustment failed.');
            }
        },
        error: function () { showToast('error', 'An error occurred.'); }
    });
});

$(document).ready(function () {
    $('#example1').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: baseUrl + 'inventory/fetchRecords',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'product_name' },
            { data: 'sku', defaultContent: '-' },
            {
                data: 'type',
                render: function (d) {
                    const map = { in: 'badge-success', out: 'badge-danger', adjustment: 'badge-info' };
                    return `<span class="badge ${map[d] || 'badge-secondary'}">${d.toUpperCase()}</span>`;
                }
            },
            { data: 'quantity' },
            { data: 'previous_stock' },
            { data: 'new_stock' },
            { data: 'reason', defaultContent: '-' },
            { data: 'adjusted_by', defaultContent: '-' },
            { data: 'created_at' }
        ],
        responsive: true, autoWidth: false
    });
});
