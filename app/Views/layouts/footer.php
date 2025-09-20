</div>
        </div>
    </div>
    

    <script src="/js/main.js"></script>
    <script src="/js/confirm_modal.js"></script>
    <script src="/js/persian-date.js"></script>
    <script src="/js/persian-datepicker.js"></script>
    <script>
    // Update date display if page is open past midnight
    function updateDateDisplay() {
        const dateElement = document.getElementById('current-date');
        if (dateElement) {
            // We could make an AJAX call to get the current Jalali date
            // For now, we'll just update the Gregorian date and let user refresh
            const now = new Date();
            const options = { year: 'numeric', month: '2-digit', day: '2-digit' };
            const formattedDate = now.toLocaleDateString('fa-IR', options);
            // Since we can't convert to Jalali in JS without additional library, 
            // we'll just show the current time
            const timeString = now.toLocaleTimeString('fa-IR');
            // You can replace this with an AJAX call to get Jalali date from server
        }
    }
    
    // Update date every minute
    // setInterval(updateDateDisplay, 60000);
    
    // For a more complete solution, you could add an AJAX endpoint to get current Jalali date:
    /*
    function updateJalaliDate() {
        fetch('/?action=get_current_date')
            .then(response => response.json())
            .then(data => {
                const dateElement = document.getElementById('current-date');
                if (dateElement && data.date) {
                    dateElement.innerHTML = '<i class="fas fa-calendar-day me-2"></i>' + data.date;
                }
            })
            .catch(error => console.error('Error:', error));
    }
    
    // Update every 5 minutes
    setInterval(updateJalaliDate, 300000);
    */
    </script>
    <script>
    // Initialize Persian datepicker for all date inputs
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize datepicker for Jalali date inputs
        const jalaliDateInputs = document.querySelectorAll(
            'input[name="trans_date"], ' +
            'input[name="due_date"], ' +
            'input[name="start_date"], ' +
            'input[name="payment_date"], ' +
            'input[name="end_date"]'
        );
        
        jalaliDateInputs.forEach(input => {
            // Convert existing Gregorian value to Jalali if needed
            if (input.value && input.value.match(/^\d{4}-\d{2}-\d{2}$/)) {
                // This is a Gregorian date, convert to Jalali
                const gregorianDate = input.value;
                // We'll handle this conversion via AJAX or leave it as is for now
                // For simplicity, we'll clear it and let user pick fresh date
                // input.value = ''; // Uncomment this if you want to clear existing Gregorian dates
            }
            
            // Initialize Persian datepicker
            if (!input.datepicker) { // Check if already initialized
                $(input).persianDatepicker({
                    format: 'YYYY/MM/DD',
                    altField: input,
                    altFormat: 'YYYY/MM/DD',
                    initialValue: false,
                    calendarType: 'persian',
                    observer: true,
                    minYear: 1300,
                    maxYear: 1500,
                    toolbox: {
                        calendarSwitch: {
                            enabled: true
                        }
                    },
                    text: {
                        btnSelect: 'انتخاب',
                        btnToday: 'امروز',
                        today: 'امروز'
                    },
                    onSelect: function (unix) {
                        const jalaliDate = new persianDate(unix);
                        const formattedDate = jalaliDate.format('YYYY/MM/DD');
                        input.value = formattedDate;
                    }
                });
            }
        });
        
    });
    // Ensure modal forms submit properly
    document.addEventListener('DOMContentLoaded', function() {
        const deleteModals = document.querySelectorAll('.modal');
        deleteModals.forEach(modal => {
            modal.addEventListener('submit', function(e) {
                if (e.target.tagName === 'FORM') {
                    // Let form submit normally
                    return true;
                }
            });
        });
    });
    </script>
</body>
</html>