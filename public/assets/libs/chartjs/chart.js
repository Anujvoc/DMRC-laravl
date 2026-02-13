/**
 * Chart.js - Minimal implementation for dashboard charts
 * Basic chart functionality for admin dashboard
 */

window.Chart = window.Chart || {};

Chart.defaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'bottom'
        }
    }
};

Chart.Chart = Chart;

Chart.create = function(ctx, type, data, options = {}) {
    const canvas = ctx.canvas;
    const mergedOptions = { ...Chart.defaults, ...options };
    
    return {
        canvas: canvas,
        ctx: ctx,
        type: type,
        data: data,
        options: mergedOptions,
        
        update: function() {
            // Simple chart rendering
            this.render();
        },
        
        render: function() {
            const width = canvas.width;
            const height = canvas.height;
            
            // Clear canvas
            ctx.clearRect(0, 0, width, height);
            
            // Simple bar chart implementation
            if (type === 'bar') {
                const barWidth = width / (data.labels.length * 2);
                const maxValue = Math.max(...data.datasets[0].data);
                
                data.datasets.forEach((dataset, datasetIndex) => {
                    dataset.data.forEach((value, index) => {
                        const barHeight = (value / maxValue) * (height - 40);
                        const x = index * barWidth * 2 + barWidth / 2 + (datasetIndex * barWidth);
                        const y = height - barHeight - 20;
                        
                        ctx.fillStyle = dataset.backgroundColor || '#007bff';
                        ctx.fillRect(x, y, barWidth, barHeight);
                        
                        // Draw value label
                        ctx.fillStyle = '#333';
                        ctx.font = '12px Arial';
                        ctx.textAlign = 'center';
                        ctx.fillText(value, x + barWidth / 2, y - 5);
                    });
                });
                
                // Draw labels
                ctx.fillStyle = '#333';
                ctx.font = '12px Arial';
                ctx.textAlign = 'center';
                data.labels.forEach((label, index) => {
                    const x = index * barWidth * 2 + barWidth;
                    ctx.fillText(label, x, height - 5);
                });
            }
        }
    };
};

// Chart types
Chart.Bar = function(ctx, data, options) {
    return Chart.create(ctx, 'bar', data, options);
};

Chart.Line = function(ctx, data, options) {
    return Chart.create(ctx, 'line', data, options);
};

Chart.Doughnut = function(ctx, data, options) {
    return Chart.create(ctx, 'doughnut', data, options);
};
