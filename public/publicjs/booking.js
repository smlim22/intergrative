document.addEventListener('DOMContentLoaded', function() {
    /*
    Author: Adrean
    */
    if (!window.bookingData || !window.bookingData.facilityID) {
        console.error('bookingData or facilityID is undefined!');
        return;
    }

    const form = document.getElementById('bookingForm');
    const dateInput = form.querySelector('[name="reservation_date"]');
    const startInput = form.querySelector('[name="start_time"]');
    const endInput = form.querySelector('[name="end_time"]');
    const submitBtn = form.querySelector('button');
    const availabilityMsg = document.getElementById('availability-message');
    const facilityID = window.bookingData.facilityID;

    // Set default min/max for date and time
    dateInput.min = new Date().toISOString().split('T')[0];
    startInput.min = "08:00";
    startInput.max = "17:00"; // because end time should be at least +1hr
    endInput.min = "09:00";
    endInput.max = "18:00";

    function padZero(num) {
        return String(num).padStart(2, '0');
    }

    function updateTimeConstraints() {
        if (startInput.value) {
            let [sh, sm] = startInput.value.split(':').map(Number);
            let eh = sh + 1;
            if (eh > 18) eh = 18;
            endInput.min = padZero(eh) + ":00";
        } else {
            endInput.min = "09:00";
        }

        if (endInput.value) {
            let [eh, em] = endInput.value.split(':').map(Number);
            let sh = eh - 1;
            if (sh < 8) sh = 8;
            startInput.max = padZero(sh) + ":00";
        } else {
            startInput.max = "17:00";
        }
    }

    function checkAvailability() {
        updateTimeConstraints();

        const data = {
            facilityID,
            reservation_date: dateInput.value,
            start_time: startInput.value,
            end_time: endInput.value
        };

        if (!data.reservation_date || !data.start_time || !data.end_time) {
            availabilityMsg.innerHTML = '';
            submitBtn.disabled = true;
            return;
        }

        fetch('/api/booking/check-availability', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.available) {
                availabilityMsg.innerHTML = `<div class="alert alert-success">${resp.message}</div>`;
                submitBtn.disabled = false;
            } else {
                availabilityMsg.innerHTML = `<div class="alert alert-danger">${resp.message}</div>`;
                submitBtn.disabled = true;
            }
        })
        .catch(() => {
            availabilityMsg.innerHTML = '<div class="alert alert-warning">Error checking availability.</div>';
            submitBtn.disabled = true;
        });
    }

    [dateInput, startInput, endInput].forEach(input => input.addEventListener('change', checkAvailability));

    // Initial check
    checkAvailability();
});
