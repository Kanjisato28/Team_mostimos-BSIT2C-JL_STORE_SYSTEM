function showToast(type, message) {
    type === 'success' ? toastr.success(message, 'Success') : toastr.error(message, 'Error');
}

$(document).on('click', '.voidBtn', function () {
    const id = $(this).data('id');
    const csrfName = $('meta[name="csrf-name"]').attr('content');
    const csrfToken = $('meta[name="csrf-token"]').attr('content');
    if (!confirm('Void this sale? Stock will be restored.')) return;
    $.ajax({
        url: baseUrl + 'sales/void/' + id,
        method: 'POST',
        data: { _method: 'POST', [csrfName]: csrfToken },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                showToast('success', 'Sale voided successfully.');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('error', res.message || 'Failed to void.');
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
            url: baseUrl + 'sales/fetchRecords',
            type: 'POST',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        },
        order: [[0, 'desc']],
        columns: [
            { data: 'row_number' },
            { data: 'id', visible: false },
            { data: 'invoice_no' },
            { data: 'customer_name', defaultContent: 'Walk-in' },
            {
                data: 'total_amount',
                render: function (d) { return '₱' + parseFloat(d).toFixed(2); }
            },
            {
                data: 'payment_method',
                render: function (d) { return d.toUpperCase(); }
            },
            {
                data: 'status',
                render: function (d) {
                    return d === 'completed'
                        ? '<span class="badge badge-success">Completed</span>'
                        : '<span class="badge badge-danger">Voided</span>';
                }
            },
            { data: 'sale_date' },
            {
                data: null, orderable: false, searchable: false,
                render: function (data, type, row) {
                    const voidBtn = row.status === 'completed'
                        ? `<button class="btn btn-sm btn-danger voidBtn" data-id="${row.id}" title="Void"><i class="fas fa-ban"></i></button>`
                        : '';
                    return `
                    <a href="${baseUrl}sales/view/${row.id}" class="btn btn-sm btn-info" title="View"><i class="fas fa-eye"></i></a>
                    ${voidBtn}`;
                }
            }
        ],
        responsive: true, autoWidth: false
    });
});
