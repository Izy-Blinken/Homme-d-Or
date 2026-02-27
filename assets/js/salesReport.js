// Tab switching
function activateTab(tabName) {
    document.querySelectorAll('.report-tab').forEach(t => {
        t.classList.toggle('active', t.dataset.tab === tabName);
    });
    document.querySelectorAll('.tab-panel').forEach(p => {
        p.classList.toggle('active', p.id === 'tab-' + tabName);
    });
}

document.querySelectorAll('.report-tab').forEach(t => {
    t.addEventListener('click', () => activateTab(t.dataset.tab));
});

// Restore active tab after masubmit ng form
const activeTab = new URLSearchParams(window.location.search).get('active_tab') || 'revenue';
activateTab(activeTab);


// bar chart
function makeChart(canvasId, labels, values, label, color) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return;

    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: values,
                backgroundColor: color + 'cc',
                borderColor: color,
                borderWidth: 1,
                borderRadius: 2
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f0f0f0' } },
                x: { grid: { display: false } }
            }
        }
    });
}


// Export kung ano lng visible na tab as PDF
function exportPDF(tab) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const titles = {
        revenue:   'Revenue Report',
        sales:     'Sales Report',
        orders:    'Orders Report',
        products:  'Products Report',
        customers: 'Customers Report'
    };

    // Header
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.text("Homme D'or", 14, 18);
    doc.setFontSize(12);
    doc.text(titles[tab], 14, 26);
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    doc.text('Generated: ' + new Date().toLocaleDateString('en-PH', {
        year: 'numeric', month: 'long', day: 'numeric'
    }), 14, 33);
    doc.line(14, 36, 196, 36);

    // Stats summary
    let y = 44;
    const statsEl = document.getElementById(tab + '-stats');
    if (statsEl) {
        statsEl.querySelectorAll('.stat-card').forEach(card => {
            const label = card.querySelector('.stat-label')?.textContent?.trim() || '';
            const value = card.querySelector('.stat-value')?.textContent?.trim() || '';
            doc.setFontSize(9);
            doc.setFont('helvetica', 'bold');
            doc.text(label + ':', 14, y);
            doc.setFont('helvetica', 'normal');
            doc.text(value, 80, y);
            y += 7;
        });
        y += 4;
    }

    // Table
    const tableEl = document.getElementById(tab + '-table');
    if (tableEl) {
        const headers = [...tableEl.querySelectorAll('th')].map(th => th.textContent.trim());
        const rows    = [...tableEl.querySelectorAll('tbody tr')].map(tr =>
            [...tr.querySelectorAll('td')].map(td => td.textContent.trim())
        );

        doc.autoTable({
            startY: y,
            head: [headers],
            body: rows,
            styles: { fontSize: 8, cellPadding: 3 },
            headStyles: { fillColor: [26, 36, 51], textColor: 255, fontStyle: 'bold' },
            alternateRowStyles: { fillColor: [248, 248, 248] },
            margin: { left: 14, right: 14 }
        });
    }

    doc.save(`homme_dor_${tab}_report_${new Date().toISOString().slice(0, 10)}.pdf`);
}