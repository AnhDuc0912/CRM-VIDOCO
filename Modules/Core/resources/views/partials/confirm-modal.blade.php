<script>
    /**
     * Confirm action with SWAL
     */
    function confirmAction(url, method = 'POST', message = 'Bạn có chắc chắn muốn thực hiện hành động này?') {
        Swal.fire({
            title: 'Xác nhận',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Thực hiện',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                if (method !== 'POST') {
                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = method;
                    form.appendChild(methodField);
                }

                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    /**
     * Confirm proposal action with SWAL - show 2 options
     */
    function confirmProposalAction(proposalId) {
        Swal.fire({
            title: 'Duyệt báo giá',
            text: 'Bạn muốn chuyển báo giá này thành:',
            icon: 'question',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            denyButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Đơn hàng',
            denyButtonText: 'Hợp đồng',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create order
                submitProposalAction(proposalId, 'convert-to-order');
            } else if (result.isDenied) {
                // Create contract
                submitProposalAction(proposalId, 'convert-to-contract');
            }
        });
    }

    /**
     * Submit proposal conversion form
     */
    function submitProposalAction(proposalId, action) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/proposals/' + proposalId + '/' + action;

        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'PUT';

        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }

    /**
     * Confirm delete with SWAL
     */
    function confirmDelete(url, message = 'Bạn có chắc chắn muốn xóa mục này?') {
        Swal.fire({
            title: 'Xác nhận xóa',
            text: message,
            icon: 'error',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form and submit
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = url;

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        });
    }

    /**
     * Confirm AJAX action with SWAL
     */
    function confirmAjaxAction(url, method = 'POST', data = {}, message = 'Bạn có chắc chắn muốn thực hiện hành động này?') {
        Swal.fire({
            title: 'Xác nhận',
            text: message,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Thực hiện',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Thành công',
                                text: response.message || 'Hành động đã được thực hiện thành công',
                                confirmButtonColor: '#3085d6'
                            }).then(() => {
                                if (response.reload) {
                                    location.reload();
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Lỗi',
                                text: response.message || 'Có lỗi xảy ra',
                                confirmButtonColor: '#d33'
                            });
                        }
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: 'Có lỗi xảy ra khi thực hiện hành động',
                            confirmButtonColor: '#d33'
                        });
                    }
                });
            }
        });
    }
</script>
