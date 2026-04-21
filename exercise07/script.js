// Exercise07 script: store entries in localStorage, render latest entry, table, copy and CSV download
let entries = JSON.parse(localStorage.getItem('entries')) || [];

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('entryForm');
    if (form) {
        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const name = document.getElementById('name').value.trim();
            const email = document.getElementById('email').value.trim();
            const age = document.getElementById('age').value.trim();

            if (!name || !email || !age) return;

            const entry = { name, email, age };
            entries.push(entry);
            localStorage.setItem('entries', JSON.stringify(entries));

            form.reset();
            renderLatest();
            renderTable();
        });
    }

    renderLatest();
    renderTable();
});

function renderLatest() {
    const area = document.getElementById('printArea');
    if (!area) return;
    if (entries.length === 0) {
        area.innerHTML = '<em>No entries yet.</em>';
        return;
    }
    const last = entries[entries.length - 1];
    area.innerHTML = `<div style="padding:12px; border-radius:8px; background:#f3f6ff;">
        <strong>Latest Entry</strong><br>
        Name: ${escapeHtml(last.name)}<br>
        Email: ${escapeHtml(last.email)}<br>
        Age: ${escapeHtml(last.age)}
    </div>`;
}

function renderTable() {
    const tbody = document.querySelector('#dataTable tbody');
    if (!tbody) return;
    tbody.innerHTML = '';
    entries.forEach(e => {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td>${escapeHtml(e.name)}</td><td>${escapeHtml(e.email)}</td><td>${escapeHtml(e.age)}</td>`;
        tbody.appendChild(tr);
    });
}

function copyData() {
    if (entries.length === 0) { alert('No data to copy'); return; }
    const csv = buildCSV();
    navigator.clipboard.writeText(csv).then(() => {
        alert('CSV copied to clipboard');
    }, () => { alert('Failed to copy'); });
}

function downloadCSV() {
    if (entries.length === 0) { alert('No data to download'); return; }
    const csv = buildCSV();
    const blob = new Blob([csv], { type: 'text/csv' });
    const url = URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = 'entries.csv';
    document.body.appendChild(a);
    a.click();
    a.remove();
    URL.revokeObjectURL(url);
}

function buildCSV() {
    const header = ['Name','Email','Age'];
    let csv = header.join(',') + '\n';
    entries.forEach(r => {
        csv += `${escapeCsv(r.name)},${escapeCsv(r.email)},${escapeCsv(r.age)}\n`;
    });
    return csv;
}

function clearAll() {
    if (!confirm('Clear all entries?')) return;
    entries = [];
    localStorage.removeItem('entries');
    renderLatest();
    renderTable();
}

function escapeCsv(value) {
    if (value == null) return '';
    const s = String(value);
    if (s.includes(',') || s.includes('"') || s.includes('\n')) {
        return '"' + s.replace(/"/g, '""') + '"';
    }
    return s;
}

function escapeHtml(unsafe) {
    return unsafe
         .replace(/&/g, '&amp;')
         .replace(/</g, '&lt;')
         .replace(/>/g, '&gt;')
         .replace(/"/g, '&quot;')
         .replace(/'/g, '&#039;');
}