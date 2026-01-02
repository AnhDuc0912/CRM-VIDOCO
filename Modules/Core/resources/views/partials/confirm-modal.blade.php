<!-- Confirm Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmModalLabel">
                    <span id="confirmModalTitle">Xác nhận</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="confirmModalMessage">Bạn có chắc chắn muốn thực hiện hành động này?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i>Hủy
                </button>
                <button type="button" class="btn btn-info" id="confirmModalBtn">
                    <i class="bx bx-check me-1"></i>Xác nhận
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Confirm Modal Functions
    function showConfirmModal(options = {}) {
        const {
            title = 'Xác nhận',
                message = 'Bạn có chắc chắn muốn thực hiện hành động này?',
                confirmText = 'Xác nhận',
                cancelText = 'Hủy',
                confirmClass = 'btn-danger',
                onConfirm = null,
                onCancel = null
        } = options;

        // Set modal content
        document.getElementById('confirmModalTitle').textContent = title;
        document.getElementById('confirmModalMessage').textContent = message;

        const confirmBtn = document.getElementById('confirmModalBtn');
        confirmBtn.textContent = confirmText;
        confirmBtn.className = `btn ${confirmClass}`;

        const cancelBtn = document.querySelector('#confirmModal .btn-danger');
        cancelBtn.textContent = cancelText;

        // Remove previous event listeners
        const newConfirmBtn = confirmBtn.cloneNode(true);
        confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

        const newCancelBtn = cancelBtn.cloneNode(true);
        cancelBtn.parentNode.replaceChild(newCancelBtn, cancelBtn);

        // Add new event listeners
        newConfirmBtn.addEventListener('click', function() {
            if (onConfirm && typeof onConfirm === 'function') {
                onConfirm();
            }
            const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
            if (modal) {
                modal.hide();
            }
        });

        newCancelBtn.addEventListener('click', function() {
            if (onCancel && typeof onCancel === 'function') {
                onCancel();
            }
        });

        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'), {
            backdrop: 'static',
            keyboard: false
        });
        modal.show();

        // Focus on cancel button by default for better accessibility
        setTimeout(() => {
            newCancelBtn.focus();
        }, 100);
    }

    // Common confirm functions
    function confirmDelete(url, message = 'Bạn có chắc chắn muốn xóa mục này?') {
        showConfirmModal({
            title: 'Xác nhận xóa',
            message: message,
            confirmText: 'Xóa',
            confirmClass: 'btn-danger',
            onConfirm: function() {
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

    function confirmAction(url, method = 'POST', message = 'Bạn có chắc chắn muốn thực hiện hành động này?') {
        showConfirmModal({
            title: 'Xác nhận hành động',
            message: message,
            confirmText: 'Thực hiện',
            confirmClass: 'btn-info',
            onConfirm: function() {
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

    function confirmAjaxAction(url, method = 'POST', data = {}, message =
        'Bạn có chắc chắn muốn thực hiện hành động này?') {
        showConfirmModal({
            title: 'Xác nhận hành động',
            message: message,
            confirmText: 'Thực hiện',
            confirmClass: 'btn-info',
            onConfirm: function() {
                $.ajax({
                    url: url,
                    method: method,
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            Lobibox.notify('success', {
                                title: 'Thành công',
                                msg: response.message ||
                                    'Hành động đã được thực hiện thành công',
                                position: 'top right',
                                delay: 5000,
                                sound: false,
                                width: 350,
                                showClass: 'fadeInDown',
                                hideClass: 'fadeOutUp',
                                icon: 'bx bx-check-circle',
                                rounded: true,
                                class: 'my-lobibox-toast'
                            });

                            // Reload page or update UI
                            if (response.reload) {
                                location.reload();
                            }
                        } else {
                            Lobibox.notify('error', {
                                title: 'Lỗi',
                                msg: response.message || 'Có lỗi xảy ra',
                                position: 'top right',
                                delay: 5000,
                                sound: false,
                                width: 350,
                                showClass: 'fadeInDown',
                                hideClass: 'fadeOutUp',
                                icon: 'bx bx-error',
                                rounded: true,
                                class: 'my-lobibox-toast'
                            });
                        }
                    },
                    error: function(xhr) {
                        Lobibox.notify('error', {
                            title: 'Lỗi',
                            msg: 'Có lỗi xảy ra khi thực hiện hành động',
                            position: 'top right',
                            delay: 5000,
                            sound: false,
                            width: 350,
                            showClass: 'fadeInDown',
                            hideClass: 'fadeOutUp',
                            icon: 'bx bx-error',
                            rounded: true,
                            class: 'my-lobibox-toast'
                        });
                    }
                });
            }
        });
    }
</script>
