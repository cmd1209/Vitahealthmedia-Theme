(function () {
  document.querySelectorAll('.contact__form .wpforms-form').forEach(function (form) {
    const button = form.querySelector('button[type="submit"]');
    if (!button) return;
    let disabledByTheme = false;

    function requiredFieldsFilled() {
      return Array.from(form.querySelectorAll('input, select, textarea')).every(function (field) {
        const required = field.required || field.getAttribute('aria-required') === 'true' ||
          field.classList.contains('wpforms-field-required');
        if (!required || field.disabled || field.type === 'hidden' || !field.getClientRects().length) return true;
        if (field.type === 'radio' || field.type === 'checkbox') {
          return Array.from(form.elements).some(function (other) {
            return other.name === field.name && other.type === field.type && !other.disabled && other.checked;
          });
        }
        return field.value.trim() !== '';
      });
    }

    function update() {
      if (!requiredFieldsFilled()) {
        // Do not take ownership of a lock set by WPForms during submission.
        if (!button.disabled) {
          disabledByTheme = true;
          button.disabled = true;
        }
      } else if (disabledByTheme) {
        disabledByTheme = false;
        button.disabled = false;
      }
    }

    form.addEventListener('input', update);
    form.addEventListener('change', update);
    form.addEventListener('reset', function () { setTimeout(update, 0); });
    window.addEventListener('pageshow', update);
    // Reapply the gate if WPForms restores its button while required fields are empty.
    new MutationObserver(update).observe(button, { attributes: true, attributeFilter: ['disabled'] });
    update();
  });
})();
