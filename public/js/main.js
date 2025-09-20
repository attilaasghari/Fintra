// public/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap tooltips if needed later
    // Also, Persian number formatting can be added here
    // Initialize Persian datepicker for all jalali-date-input fields
    if (typeof $ !== 'undefined' && typeof $.fn.persianDatepicker !== 'undefined') {
        $('.jalali-date-input').each(function() {
            // If input is empty, set to current Jalali date in YYYY-MM-DD
            if (!$(this).val()) {
                try {
                    // Force persian calendar for today
                    var today = new persianDate().toCalendar('persian').format('YYYY-MM-DD');
                    $(this).val(today);
                } catch (e) {}
            }
            $(this).persianDatepicker({
                format: 'YYYY-MM-DD',
                autoClose: true,
                initialValueType: 'persian',
                calendarType: 'persian',
                persianDigit: false,
                toolbox: {
                    enabled: true,
                    todayButton: {
                        enabled: true,
                        text: { fa: 'امروز', en: 'Today' }
                    },
                    submitButton: { enabled: false },
                    calendarSwitch: { enabled: false }
                },
                navigator: { enabled: true },
                dayPicker: { enabled: true, highlightToday: true },
                monthPicker: { enabled: true },
                yearPicker: { enabled: true }
            });
        });
    }
    console.log('سامانه حسابداری شخصی بارگذاری شد.');
});// Prevent duplicate initialization
if (typeof window.appInitialized === 'undefined') {
    window.appInitialized = true;
    
    document.addEventListener('DOMContentLoaded', function() {
        console.log('سامانه حسابداری شخصی بارگذاری شد.');
        
        // Initialize any other event listeners here
        // For example:
        // initializeAccountEvents();
        // initializeTransactionEvents();
    });
}

// If you need account-specific events, create a separate function
function initializeAccountEvents() {
    // Your account-related JavaScript here
    console.log('Account events initialized');
}