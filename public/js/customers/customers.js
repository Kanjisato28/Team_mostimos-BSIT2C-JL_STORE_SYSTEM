function showToast(type, message) {
    type === 'success' ? toastr.success(message, 'Success') : toastr.error(message, 'Error');
}

$('#addForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'customers/save',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (res) {
            if (res.status === 'success') {
                $('#AddNewModal').modal('hide');
                $('#addForm')[0].reset();
                showToast('success', 'Customer added successfully!');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', res.message || 'Failed to add customer.');
            }
        },
        error: function () { showToast('error', 'An error occurred.'); }
    });
});

$(document).on('click', '.edit-btn', function () {
    const id = $(this).data('id');
    $.ajax({
        url: baseUrl + 'customers/edit/' + id,
        method: 'GET',
        dataType: 'json',
        success: function (res) {
            if (res.data) {
                $('#editId').val(res.data.id);
                $('#editName').val(res.data.name);
                $('#editPhone').val(res.data.phone);
                $('#editEmail').val(res.data.email);
                $('#editAddress').val(res.data.address);
                $('#editModal').modal('show');
            }
        },
        error: function () { showToast('error', 'Error fetching data.'); }
    });
});

$('#editForm').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
        url: baseUrl + 'customers/update',
        method: 'POST',
        data: $(this).serialize(),
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#editModal').modal('hide');
                showToast('success', 'Customer updated successfully!');
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
    if (!confirm('Delete this customer?')) return;
    $.ajax({
        url: baseUrl + 'customers/delete/' + id,
        method: 'POST',
        data: { _method: 'DELETE', [csrfName]: csrfToken },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                showToast('success', 'Customer deleted.');
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
            url: baseUrl + 'customers/fetchRecords',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        },
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'name' },
            { data: 'phone' },
            { data: 'email' },
            { data: 'address' },
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
