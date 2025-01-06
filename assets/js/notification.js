// notification.js
let activeReminders = new Set();

// Deteksi apakah kita berada di subfolder
const isInSubfolder = window.location.pathname.split('/').length > 3;
const basePath = isInSubfolder ? '../' : '';

function checkReminders() {
    fetch(`${basePath}pengingat/get_reminders.php`)
        .then(response => response.json())
        .then(reminders => {
            reminders.forEach(reminder => {
                const reminderTime = new Date(reminder.waktuPengingat).getTime();
                const now = new Date().getTime();
                
                if (reminder.status === 'active' && 
                    reminderTime <= now && 
                    !activeReminders.has(reminder.id)) {
                    
                    showNotification(reminder);
                    activeReminders.add(reminder.id);
                }
            });
        })
        .catch(error => console.error('Error:', error));
}

function showNotification(reminder) {
    document.getElementById('popupMessage').innerHTML = `
        <strong>Pengingat untuk ${reminder.namaAnak}</strong><br>
        <strong>Waktu: ${new Date(reminder.waktuPengingat).toLocaleString()}</strong><br>
        <p>${reminder.instruksi_arahan_dokter || 'Tidak ada instruksi'}</p>
    `;
    
    document.getElementById('popupButtons').innerHTML = `
        <button onclick="markAsDone(${reminder.id})" class="btn btn-success">Selesai</button>
        <button onclick="closePopup()" class="btn btn-secondary">Tutup</button>
    `;

    document.getElementById('popupOverlay').style.display = 'block';
    document.getElementById('popupNotification').style.display = 'block';

    playNotificationSound();
}

function markAsDone(id) {
    fetch(`${basePath}pengingat/done_reminder.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closePopup();
            activeReminders.delete(id);
            location.reload();
        }
    });
}

function snoozeReminder(id) {
    fetch(`${basePath}pengingat/snooze_reminder.php`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closePopup();
            activeReminders.delete(id);
        }
    });
}

function playNotificationSound() {
    const audio = new Audio(`${basePath}assets/notification.mp3`);
    audio.play().catch(err => console.log('Tidak dapat memainkan suara notifikasi'));
}

function closePopup() {
    document.getElementById('popupOverlay').style.display = 'none';
    document.getElementById('popupNotification').style.display = 'none';
}

// Cek setiap 30 detik
setInterval(checkReminders, 30000);

// Cek pertama kali saat halaman dimuat
document.addEventListener('DOMContentLoaded', checkReminders);