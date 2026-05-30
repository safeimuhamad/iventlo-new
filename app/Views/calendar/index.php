<div class="card bg-white rounded-10 border border-white p-20 mb-4">
    <h3 class="mb-1">Kalender Jadwal Rental</h3>
    <p class="mb-0 text-body">
        Lihat jadwal rental berdasarkan tanggal kirim dan tanggal selesai.
    </p>
</div>

<div class="card bg-white rounded-10 border border-white p-20">
    <div id="rental-calendar"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const calendarEl = document.getElementById('rental-calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 750,
        events: <?= $eventsJson ?>,
        eventClick: function(info) {
            if (info.event.url) {
                info.jsEvent.preventDefault();
                window.location.href = info.event.url;
            }
        }
    });

    calendar.render();
});
</script>