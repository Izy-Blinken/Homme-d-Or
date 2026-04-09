// 1. TAB SWITCHING LOGIC
function activateTab(tabName) {
    document.querySelectorAll('.report-tab').forEach(t => {
        t.classList.toggle('active', t.dataset.tab === tabName);
    });
    document.querySelectorAll('.tab-panel').forEach(p => {
        p.classList.toggle('active', p.id === 'tab-' + tabName);
    });
}

// Add click listeners to all tab buttons
document.querySelectorAll('.report-tab').forEach(t => {
    t.addEventListener('click', () => activateTab(t.dataset.tab));
});

// Restore active tab after a form submit or page reload
const activeTab = new URLSearchParams(window.location.search).get('active_tab') || 'revenue';
activateTab(activeTab);


// 2. LUXURY CHART.JS LOGIC
function makeChart(canvasId, labels, data, label, color) {
    const canvas = document.getElementById(canvasId);
    if (!canvas) return; // Safety check
    const ctx = canvas.getContext('2d');

    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(201, 169, 97, 0.7)');
    gradient.addColorStop(1, 'rgba(201, 169, 97, 0.05)');

    Chart.defaults.font.family = "'League Spartan', sans-serif";
    Chart.defaults.color = "#aaa";

    const isBarChart = (canvasId === 'chart-products');

    new Chart(ctx, {
        type: isBarChart ? 'bar' : 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                borderColor: '#c9a961',
                backgroundColor: gradient,
                borderWidth: isBarChart ? 2 : 3,
                borderRadius: isBarChart ? 6 : 0,
                barPercentage: 0.5,
                tension: 0.4,
                fill: true,
                pointBackgroundColor: '#c9a961',
                pointBorderColor: '#0a1e36',
                pointBorderWidth: 2,
                pointRadius: labels.length === 1 ? 10 : 4,
                pointHoverRadius: labels.length === 1 ? 12 : 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: { size: 14, family: "'League Spartan', sans-serif", weight: '700' },
                    bodyFont: { size: 13, family: "'League Spartan', sans-serif" },
                    padding: 12,
                    borderColor: 'rgba(212, 175, 55, 0.3)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            let prefix = (label === 'Revenue' || label === 'Sales') ? '₱' : '';
                            return label + ': ' + prefix + context.parsed.y.toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    border: { display: false },
                    ticks: { color: '#888', padding: 10, font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(255, 255, 255, 0.05)' },
                    border: { display: false },
                    ticks: {
                        color: '#888',
                        padding: 10,
                        font: { size: 11 },
                        callback: function(value) {
                            if (value >= 1000) return (value / 1000) + 'k';
                            return value;
                        }
                    }
                }
            }
        }
    });
}


// 3. EXPORT PDF LOGIC
function exportPDF(tab) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    const titles = {
        revenue: 'Revenue Report',
        sales: 'Sales Report',
        orders: 'Orders Report',
        products: 'Products Report',
        customers: 'Customers Report'
    };

    // HEADER
    doc.setFontSize(16);
    doc.setFont('helvetica', 'bold');
    doc.text("Homme D'or", 14, 18);
    doc.setFontSize(12);
    doc.text(titles[tab], 14, 26);
    
    // Date range and generated date
    doc.setFontSize(9);
    doc.setFont('helvetica', 'normal');
    const dateRange = window.dateRanges ? window.dateRanges[tab] : 'N/A';
    doc.text('Report Period: ' + dateRange, 14, 32);
    doc.text('Generated: ' + new Date().toLocaleDateString('en-PH', {
        year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit'
    }), 14, 37);
    doc.line(14, 40, 196, 40);

    let y = 48;
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
        y += 6;
    }

    const tableEl = document.getElementById(tab + '-table');
    if (tableEl) {
        const headers = [...tableEl.querySelectorAll('th')].map(th => th.textContent.trim());
        const rows = [...tableEl.querySelectorAll('tbody tr')].map(tr =>
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