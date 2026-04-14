function showToast(type, message) {
    type === 'success' ? toastr.success(message, 'Success') : toastr.error(message, 'Error');
}

$('#addForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'products/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addForm')[0].reset();
                showToast('success', 'Product added successfully!');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', res.message || 'Failed to add product.');
            }
        },
        error: function () { showToast('error', 'An error occurred.'); }
    });
});

$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    $.ajax({
        url: baseUrl + 'products/edit/' + id,
        method: 'GET',
        dataType: 'json',
        success: function (res) {
            if (res.data) {
                const d = res.data;
                $('#editId').val(d.id);
                $('#editName').val(d.name);
                $('#editSku').val(d.sku);
                $('#editCategoryId').val(d.category_id);
                $('#editSupplierId').val(d.supplier_id);
                $('#editCostPrice').val(d.cost_price);
                $('#editSellingPrice').val(d.selling_price);
                $('#editUnit').val(d.unit);
                $('#editStockQuantity').val(d.stock_quantity);
                $('#editReorderLevel').val(d.reorder_level);
                $('#editDescription').val(d.description);
                $('#editModal').modal('show');
            }
        },
        error: function () { showToast('error', 'Error fetching data.'); }
    });
});

$('#editForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'products/update',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#editModal').modal('hide');
                showToast('success', 'Product updated successfully!');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', res.message || 'Update failed.');
            }
        },
        error: function () { showToast('error', 'An error occurred.'); }
    });
});

$(document).on('click', '.deleteBtn', function () {
    const id = $(this).data('id');
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!confirm('Delete this product?')) return;
    $.ajax({
        url: baseUrl + 'products/delete/' + id,
        method: 'POST',
        data: { _method: 'DELETE', [csrfName]: csrfToken },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                showToast('success', 'Product deleted.');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', res.message || 'Delete failed.');
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
            url: baseUrl + 'products/fetchRecords',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        },
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'sku' },
            { data: 'name' },
            { data: 'category_name' },
            {
                data: 'selling_price',
                render: function (d) { return '₱' + parseFloat(d).toFixed(2); }
            },
            {
                data: 'stock_quantity',
                render: function (d, type, row) {
                    const badge = d <= row.reorder_level
                        ? `<span class="badge badge-danger">${d}</span>`
                        : `<span class="badge badge-success">${d}</span>`;
                    return badge + ' ' + row.unit;
                }
            },
            {
                data: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    return `
                    <button class="btn btn-sm btn-warning edit-btn" data-id="${row.id}"><i class="far fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger deleteBtn" data-id="${row.id}"><i class="fas fa-trash-alt"></i></button>`;
                }
            }
        ],
        responsive: true, autoWidth: false
    });
});
