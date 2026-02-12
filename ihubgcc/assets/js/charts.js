// Charts and Data Visualization

// Funding Chart for Homepage
function initFundingChart() {
    const chartContainer = document.getElementById('fundingChart');
    if (!chartContainer) return;

    const data = [
        { company: 'ساري', amount: 400, color: '#00d4aa' },
        { company: 'الموارد', amount: 200, color: '#0891b2' },
        { company: 'تمارا', amount: 175, color: '#f59e0b' },
        { company: 'سيلفي', amount: 130, color: '#ec4899' },
        { company: 'أكورد', amount: 105, color: '#8b5cf6' },
        { company: 'نيو', amount: 100, color: '#22c55e' },
        { company: 'لمسة', amount: 95, color: '#f97316' },
        { company: 'بيتك', amount: 85, color: '#06b6d4' },
        { company: 'فوديكس', amount: 75, color: '#84cc16' },
        { company: 'ديل', amount: 70, color: '#a855f7' }
    ];

    const maxAmount = Math.max(...data.map(d => d.amount));

    chartContainer.innerHTML = data.map((item, index) => `
        <div class="chart-bar" style="--value: ${item.amount}; --color: ${item.color}; --index: ${index}">
            <div class="bar-rank">${index + 1}</div>
            <div class="bar-fill" style="width: ${(item.amount / maxAmount) * 100}%"></div>
            <div class="bar-content">
                <span class="bar-label">${item.company}</span>
                <span class="bar-value">$${item.amount}M</span>
            </div>
        </div>
    `).join('');
}

// Pie Chart for Sector Distribution
function initSectorChart(containerId, data) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const total = data.reduce((sum, item) => sum + item.value, 0);
    let currentAngle = 0;

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('viewBox', '0 0 100 100');
    svg.classList.add('pie-chart');

    data.forEach((item, index) => {
        const angle = (item.value / total) * 360;
        const x1 = 50 + 40 * Math.cos((currentAngle * Math.PI) / 180);
        const y1 = 50 + 40 * Math.sin((currentAngle * Math.PI) / 180);
        const x2 = 50 + 40 * Math.cos(((currentAngle + angle) * Math.PI) / 180);
        const y2 = 50 + 40 * Math.sin(((currentAngle + angle) * Math.PI) / 180);

        const largeArcFlag = angle > 180 ? 1 : 0;

        const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
        path.setAttribute('d', `M 50 50 L ${x1} ${y1} A 40 40 0 ${largeArcFlag} 1 ${x2} ${y2} Z`);
        path.setAttribute('fill', item.color);
        path.classList.add('pie-slice');
        
        // Tooltip
        const title = document.createElementNS('http://www.w3.org/2000/svg', 'title');
        title.textContent = `${item.label}: ${item.value} (${Math.round((item.value/total)*100)}%)`;
        path.appendChild(title);

        svg.appendChild(path);
        currentAngle += angle;
    });

    // Add center circle for donut effect
    const centerCircle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
    centerCircle.setAttribute('cx', '50');
    centerCircle.setAttribute('cy', '50');
    centerCircle.setAttribute('r', '25');
    centerCircle.setAttribute('fill', '#020617');
    svg.appendChild(centerCircle);

    // Add total in center
    const text = document.createElementNS('http://www.w3.org/2000/svg', 'text');
    text.setAttribute('x', '50');
    text.setAttribute('y', '50');
    text.setAttribute('text-anchor', 'middle');
    text.setAttribute('dominant-baseline', 'middle');
    text.setAttribute('fill', '#fff');
    text.setAttribute('font-size', '8');
    text.setAttribute('font-weight', 'bold');
    text.textContent = total;
    svg.appendChild(text);

    container.appendChild(svg);

    // Add legend
    const legend = document.createElement('div');
    legend.className = 'chart-legend';
    legend.innerHTML = data.map(item => `
        <div class="legend-item">
            <span class="legend-color" style="background: ${item.color}"></span>
            <span class="legend-label">${item.label}</span>
            <span class="legend-value">${item.value}</span>
        </div>
    `).join('');
    container.appendChild(legend);
}

// Line Chart for Monthly Trends
function initTrendChart(containerId, labels, data) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const maxValue = Math.max(...data);
    const minValue = Math.min(...data);
    const range = maxValue - minValue || 1;

    const points = data.map((value, index) => {
        const x = (index / (data.length - 1)) * 100;
        const y = 100 - ((value - minValue) / range) * 80 - 10;
        return `${x},${y}`;
    }).join(' ');

    const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('viewBox', '0 0 100 100');
    svg.classList.add('line-chart');

    // Grid lines
    for (let i = 0; i <= 4; i++) {
        const line = document.createElementNS('http://www.w3.org/2000/svg', 'line');
        line.setAttribute('x1', '0');
        line.setAttribute('y1', i * 25);
        line.setAttribute('x2', '100');
        line.setAttribute('y2', i * 25);
        line.setAttribute('stroke', 'rgba(255,255,255,0.1)');
        line.setAttribute('stroke-width', '0.5');
        svg.appendChild(line);
    }

    // Area under line
    const area = document.createElementNS('http://www.w3.org/2000/svg', 'polygon');
    area.setAttribute('points', `0,100 ${points} 100,100`);
    area.setAttribute('fill', 'url(#gradient)');
    svg.appendChild(area);

    // Define gradient
    const defs = document.createElementNS('http://www.w3.org/2000/svg', 'defs');
    const gradient = document.createElementNS('http://www.w3.org/2000/svg', 'linearGradient');
    gradient.setAttribute('id', 'gradient');
    gradient.setAttribute('x1', '0%');
    gradient.setAttribute('y1', '0%');
    gradient.setAttribute('x2', '0%');
    gradient.setAttribute('y2', '100%');
    
    const stop1 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
    stop1.setAttribute('offset', '0%');
    stop1.setAttribute('stop-color', '#00d4aa');
    stop1.setAttribute('stop-opacity', '0.3');
    
    const stop2 = document.createElementNS('http://www.w3.org/2000/svg', 'stop');
    stop2.setAttribute('offset', '100%');
    stop2.setAttribute('stop-color', '#00d4aa');
    stop2.setAttribute('stop-opacity', '0');
    
    gradient.appendChild(stop1);
    gradient.appendChild(stop2);
    defs.appendChild(gradient);
    svg.appendChild(defs);

    // Line
    const polyline = document.createElementNS('http://www.w3.org/2000/svg', 'polyline');
    polyline.setAttribute('points', points);
    polyline.setAttribute('fill', 'none');
    polyline.setAttribute('stroke', '#00d4aa');
    polyline.setAttribute('stroke-width', '2');
    polyline.setAttribute('stroke-linecap', 'round');
    polyline.setAttribute('stroke-linejoin', 'round');
    svg.appendChild(polyline);

    // Data points
    data.forEach((value, index) => {
        const x = (index / (data.length - 1)) * 100;
        const y = 100 - ((value - minValue) / range) * 80 - 10;
        
        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('cx', x);
        circle.setAttribute('cy', y);
        circle.setAttribute('r', '2');
        circle.setAttribute('fill', '#00d4aa');
        circle.setAttribute('stroke', '#020617');
        circle.setAttribute('stroke-width', '1');
        
        const title = document.createElementNS('http://www.w3.org/2000/svg', 'title');
        title.textContent = `${labels[index]}: $${value}M`;
        circle.appendChild(title);
        
        svg.appendChild(circle);
    });

    container.appendChild(svg);

    // X-axis labels
    const xLabels = document.createElement('div');
    xLabels.className = 'chart-labels';
    xLabels.innerHTML = labels.map(label => `<span>${label}</span>`).join('');
    container.appendChild(xLabels);
}

// Bar Chart for Country Comparison
function initCountryChart(containerId, data) {
    const container = document.getElementById(containerId);
    if (!container) return;

    const maxValue = Math.max(...data.map(d => d.value));

    const chart = document.createElement('div');
    chart.className = 'country-chart';

    chart.innerHTML = data.map((item, index) => `
        <div class="country-bar-item" style="--index: ${index}">
            <div class="country-info">
                <span class="country-flag">${item.flag}</span>
                <span class="country-name">${item.country}</span>
            </div>
            <div class="country-bar-wrapper">
                <div class="country-bar" style="width: ${(item.value / maxValue) * 100}%; --color: ${item.color}">
                    <span class="country-value">$${item.value}M</span>
                </div>
            </div>
        </div>
    `).join('');

    container.appendChild(chart);
}

// Initialize all charts when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    initFundingChart();

    // Example sector data
    const sectorData = [
        { label: 'التقنية المالية', value: 45, color: '#00d4aa' },
        { label: 'التجارة الإلكترونية', value: 25, color: '#0891b2' },
        { label: 'الصحة', value: 15, color: '#f59e0b' },
        { label: 'التعليم', value: 10, color: '#ec4899' },
        { label: 'أخرى', value: 5, color: '#8b5cf6' }
    ];
    initSectorChart('sectorChart', sectorData);

    // Example trend data
    const months = ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'];
    const trendData = [120, 150, 180, 140, 200, 276];
    initTrendChart('trendChart', months, trendData);

    // Example country data
    const countryData = [
        { country: 'السعودية', value: 400, flag: '🇸🇦', color: '#00d4aa' },
        { country: 'الإمارات', value: 180, flag: '🇦🇪', color: '#0891b2' },
        { country: 'مصر', value: 120, flag: '🇪🇬', color: '#f59e0b' },
        { country: 'الأردن', value: 45, flag: '🇯🇴', color: '#ec4899' },
        { country: 'لبنان', value: 30, flag: '🇱🇧', color: '#8b5cf6' }
    ];
    initCountryChart('countryChart', countryData);
});

// Animated Counter for Statistics
function animateValue(element, start, end, duration, suffix = '') {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = value.toLocaleString() + suffix;
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Export functions for use in other scripts
window.ChartUtils = {
    initFundingChart,
    initSectorChart,
    initTrendChart,
    initCountryChart,
    animateValue
};