// Format Helper for maintaining number format during validation
$(document).ready(function() {

  // Function to format number to Vietnamese locale
  function formatVietnameseNumber(value) {
    const numericValue = value.toString().replace(/[^\d]/g, '');
    if (numericValue && numericValue.length > 0) {
      return parseInt(numericValue).toLocaleString('vi-VN');
    }
    return value;
  }

  // Function to check if a value needs formatting
  function needsFormatting(value) {
    if (!value) return false;
    const numericValue = value.replace(/[^\d]/g, '');
    return numericValue.length > 3 && !value.includes('.');
  }

  // Maintain format for price and total fields
  function maintainFormat() {
    $('.service-price, .service-total').each(function() {
      const $element = $(this);
      const currentValue = $element.val();

      if (needsFormatting(currentValue)) {
        const formatted = formatVietnameseNumber(currentValue);
        $element.val(formatted);
      }
    });
  }

  // Set up periodic format checking
  setInterval(maintainFormat, 100);

  // Also trigger on various events
  $(document).on('focusout blur change', '.service-price, .service-total', function() {
    setTimeout(maintainFormat, 10);
  });

  // Trigger after validation events
  $(document).on('keyup input', '.service-price, .service-total', function() {
    const $this = $(this);
    setTimeout(function() {
      const currentValue = $this.val();
      if (needsFormatting(currentValue)) {
        const formatted = formatVietnameseNumber(currentValue);
        $this.val(formatted);
      }
    }, 50);
  });

  // Global function to restore format
  window.maintainNumberFormat = maintainFormat;
});