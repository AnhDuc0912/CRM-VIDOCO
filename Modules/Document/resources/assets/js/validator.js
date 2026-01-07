// Form validation
function showError(element, message) {
    element.classList.add('is-invalid');
    const feedback = element.parentElement.querySelector('.invalid-feedback') ||
        element.closest('.col-md-4, .col-md-6, .col-md-12, .mb-3')?.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = message;
        feedback.classList.add('show');
    }

    // If element is enhanced by select2, also mark select2 UI
    try {
        const next = element.nextElementSibling;
        if (next && next.classList && next.classList.contains('select2')) {
            const sel = next.querySelector('.select2-selection');
            if (sel) sel.classList.add('is-invalid');
        }
    } catch (e) {}
}

function clearError(element) {
    element.classList.remove('is-invalid');
    const feedback = element.parentElement.querySelector('.invalid-feedback') ||
        element.closest('.col-md-4, .col-md-6, .col-md-12, .mb-3')?.querySelector('.invalid-feedback');
    if (feedback) {
        feedback.textContent = '';
        feedback.classList.remove('show');
    }

    // Clear select2 UI invalid state
    try {
        const next = element.nextElementSibling;
        if (next && next.classList && next.classList.contains('select2')) {
            const sel = next.querySelector('.select2-selection');
            if (sel) sel.classList.remove('is-invalid');
        }
    } catch (e) {}
}

function validateField(field) {
    clearError(field);
    const isSelect = field.tagName === 'SELECT';

    if (field.hasAttribute('required') || field.classList.contains('validate') || isSelect) {
        let value;
        if (isSelect) {
            if (field.multiple) {
                value = Array.from(field.selectedOptions).map(o => o.value).filter(v => v !== '');
            } else {
                value = field.value;
            }
        } else {
            value = field.value.trim();
        }

        const empty = Array.isArray(value) ? value.length === 0 : (value === null || value === undefined || value === '');

        if (empty) {
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
        console.log(form);

    if (!form) return;

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

    // Form submit validation - validate all visible fields and show all errors
    form.addEventListener('submit', function (e) {
        e.preventDefault();

        let isValid = true;

        // All visible inputs/selects/textareas are considered required
        const candidateFields = Array.from(form.querySelectorAll('input:not([type="hidden"]), textarea, select'));

        // Ensure hidden type_id is validated too
        const typeId = form.querySelector('[name="type_id"]');
        if (typeId) candidateFields.push(typeId);

        // Validate every candidate (do not short-circuit so all errors show)
        candidateFields.forEach(field => {
            if (!validateField(field)) isValid = false;
        });

        if (isValid) {
            form.submit();
            return;
        }

        // Scroll to first error
        const firstError = form.querySelector('.is-invalid');
        if (firstError) {
            firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            try { firstError.focus(); } catch (e) {}
        }
    });
});