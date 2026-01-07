// Form validation
function showError(element, message) {
    element.classList.add('is-invalid');
    const feedback = element.parentElement.querySelector('.invalid-feedback') ||
        element.closest('.col-md-4, .col-md-6, .col-md-12, .mb-3')?.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
        feedback.classList.add('show');
    }
}

function clearError(element) {
    element.classList.remove('is-invalid');
    const feedback = element.parentElement.querySelector('.invalid-feedback') ||
        element.closest('.col-md-4, .col-md-6, .col-md-12, .mb-3')?.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = '';
        feedback.classList.remove('show');
    }
}

function validateField(field) {
    clearError(field);

    if (field.hasAttribute('required') || field.classList.contains('validate')) {
        const value = field.value.trim();

        if (!value) {
            const label = field.closest('.mb-3, .col-md-4, .col-md-6, .col-md-12')?.querySelector('label')?.textContent || 'Trường này';
            showError(field, `${label.replace('*', '').trim()} là bắt buộc`);
            return false;
        }

        // Email validation
        if (field.type === 'email' && value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(value)) {
                showError(field, 'Email không hợp lệ');
                return false;
            }
        }

        // Date validation
        if (field.type === 'date' && value) {
            const effectiveDate = document.querySelector('[name="effective_date"]')?.value;
            const expirationDate = document.querySelector('[name="expiration_date"]')?.value;

            if (field.name === 'expiration_date' && effectiveDate && value < effectiveDate) {
                showError(field, 'Ngày hết hạn phải sau ngày hiệu lực');
                return false;
            }
        }
    }

    return true;
}

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('documentForm');

    // Real-time validation on blur
    form.addEventListener('blur', function (e) {
        if (e.target.matches('input, textarea, select')) {
            validateField(e.target);
        }
    }, true);

    // Clear error on input
    form.addEventListener('input', function (e) {
        if (e.target.matches('input, textarea, select')) {
            clearError(e.target);
        }
    }, true);

    // Form submit validation
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        let isValid = true;
        const requiredFields = form.querySelectorAll('[required], .validate');

        // Validate title
        const title = form.querySelector('[name="title"]');
        if (!validateField(title)) isValid = false;

        // Validate type_id
        const typeId = form.querySelector('[name="type_id"]');
        if (!typeId.value) {
            const typeText = form.querySelector('#document_type_text');
            showError(typeText, 'Vui lòng chọn loại văn bản');
            isValid = false;
        }

        // Validate dynamic fields based on document type
        const type = typeId.value;
        if (type == 1) {
            const toInternal = form.querySelector('[name="to_internal[]"]');
            if (toInternal && !toInternal.value) {
                showError(toInternal, 'Vui lòng chọn người nhận (nội bộ)');
                isValid = false;
            }
        } else if (type == 2 || type == 3) {
            const receivers = form.querySelector('[name="receivers[]"]');
            if (receivers && !receivers.value) {
                showError(receivers, 'Vui lòng chọn người nhận');
                isValid = false;
            }
        }

        // Validate all other required fields
        requiredFields.forEach(field => {
            if (!validateField(field)) isValid = false;
        });

        if (isValid) {
            form.submit();
        } else {
            // Scroll to first error
            const firstError = form.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
    });
});