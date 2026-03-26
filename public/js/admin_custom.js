/**
 * Custom JS to fix global Select2 issues across all pages.
 * Especially fixes focus and selection issues when Select2 is used inside Bootstrap Modals.
 */

$(document).ready(function () {
    // 1. Fix: Select2 search box not focusable in Bootstrap Modals
    // This handles both Bootstrap 4 (used by AdminLTE 3) and potential Bootstrap 5 usages.
    if ($.fn.modal && $.fn.modal.Constructor) {
        $.fn.modal.Constructor.prototype._enforceFocus = function () { };
    }
    // For Bootstrap 5+ (if used via global 'bootstrap' object)
    if (window.bootstrap && window.bootstrap.Modal && window.bootstrap.Modal.prototype) {
        window.bootstrap.Modal.prototype._enforceFocus = function () { };
    }


    // 2. Fix: Ensure search field gets focus when Select2 opens
    // Added a small timeout for better compatibility with different browsers/versions
    $(document).on('select2:open', function (e) {
        setTimeout(function () {
            const searchField = document.querySelector('.select2-search-field, .select2-search__field');
            if (searchField) {
                searchField.focus();
            }
        }, 10);
    });

    // 3. Global Flatpickr Initialization
    function initPickers(scope = document) {
        // Initialize Date Pickers
        $(scope).find('input[type="date"], .datepicker').each(function () {
            // Check if already initialized to avoid double pickers
            if (this._flatpickr) return;

            flatpickr(this, {
                dateFormat: "Y-m-d",
                allowInput: true,
                altInput: true,
                altFormat: "F j, Y",
                disableMobile: "true"
            });
        });

        // Initialize Time Pickers
        $(scope).find('input[type="time"], .timepicker').each(function () {
            if (this._flatpickr) return;

            flatpickr(this, {
                enableTime: true,
                noCalendar: true,
                dateFormat: "H:i",
                time_24hr: false, // Set to true if you want 24h format
                allowInput: true,
                disableMobile: "true"
            });
        });
    }

    // Initial load
    initPickers();

    // Re-initialize when modals are shown (for dynamically loaded content)
    $(document).on('shown.bs.modal', function (e) {
        initPickers(e.target);
    });

    console.log('Admin Custom JS loaded: Select2 and Flatpickr fixes applied.');
});

