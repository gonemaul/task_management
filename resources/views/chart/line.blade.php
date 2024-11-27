<div class="chart-container mt-3">
    <canvas id="line-chart"></canvas>
</div>

@push('script')
    <script>
        $(document).ready(function() {
            initChart();
            $('#year').on('change', function() {
                updateChart($(this).val());
            })
        })

        const chart = {
            type: 'line',
            options: {
                aspectRatio: 3,
                responsive: true,
                scales: {
                    x: {
                        grid: {
                            display: false,
                        },
                        ticks: {
                            color: '#878585',
                        }
                    },
                    y: {
                        grid: {
                            color: 'rgba(3, 2, 41, 0.1)',
                        },
                        ticks: {
                            color: '#878585',
                            callback: function(value) {
                                if (value == 0) return '0';
                                if (value >= 1000000000000) return (value / 1000000000000) + 'T';
                                if (value >= 1000000000) return (value / 1000000000) + 'B';
                                if (value >= 1000000) return (value / 1000000) + 'M';
                                if (value >= 1000) return (value / 1000) + 'K';
                                // return value || null;
                            },
                            beginAtZero: true,
                            min: 0,
                            max: 1000000,
                            stepSize: 200000,
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'nearest'
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        backgroundColor: '#F6D047',
                        titleColor: '#000',
                        bodyColor: '#000',
                        displayColors: false,
                        position: 'nearest', // Keep tooltip near the point
                        caretPadding: 12,
                        callbacks: {
                            title: function() {
                                return 'Completed'; // Menghilangkan judul (bulan tidak ditampilkan)
                            },
                            label: function(tooltipItem) {
                                // Formatkan data menjadi Rp. xxx.xxx
                                // let formattedValue = tooltipItem.raw.toString().replace(/\B(?=(\d{3})+(?!\d))/g,
                                //     '.');
                                // return 'Rp. ' + formattedValue;
                                return tooltipItem.raw + ' ' + 'Task'
                                // return ['Pengeluaran', 'Rp. ' + formattedValue];
                            }
                        },
                        padding: {
                            top: 10,
                            bottom: 10,
                            left: 15,
                            right: 15,
                        },
                        bodyAlign: 'center', // Teks di dalam tooltip berada di tengah
                        titleAlign: 'center', // Agar teks body tetap centered
                        bodyFont: {
                            size: 14, // Ukuran font teks di tooltip
                            weight: 'bold', // Berat font teks
                        },
                        displayColors: false,
                        yAlign: 'bottom', // Align tooltip above the point
                    },
                    // Plugin to draw dashed lines to the x-axis
                    dashedLine: {
                        active: false
                    }
                }
            },
            plugins: [{
                id: 'dashedLine',
                afterDraw: (chart) => {
                    if (chart.tooltip._active && chart.tooltip._active.length) {
                        const ctx = chart.ctx;
                        const activePoint = chart.tooltip._active[0];
                        const x = activePoint.element.x;
                        const y = activePoint.element.y;
                        const chartBottom = chart.chartArea.bottom;

                        // Draw dashed line
                        ctx.save();
                        ctx.beginPath();
                        ctx.setLineDash([5, 5]); // Set dashed line (5px dashes, 5px spaces)
                        ctx.moveTo(x, y);
                        ctx.lineTo(x, chartBottom); // Draw line to x-axis
                        ctx.lineWidth = 2;
                        ctx.strokeStyle = '#F6D047'; // Color of the dashed line
                        ctx.stroke();
                        ctx.restore();

                        // Draw custom SVG (ellipse) above the point
                        const svgX = x - 10; // Adjust SVG position (centered)
                        const svgY = y - 10; // Position SVG above the point

                        const svgString = `
                        <svg width="21" height="22" viewBox="0 0 21 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g filter="url(#filter0_d_8_1922)">
                        <ellipse cx="10.5882" cy="5.34049" rx="5.15412" ry="5.34049" fill="white"/>
                        <path d="M13.7833 5.34049C13.7833 7.27419 12.2878 8.72194 10.5882 8.72194C8.88862 8.72194 7.39313 7.27419 7.39313 5.34049C7.39313 3.4068 8.88862 1.95905 10.5882 1.95905C12.2878 1.95905 13.7833 3.4068 13.7833 5.34049Z" stroke="#FA8C1B" stroke-width="3.91809"/>
                        </g>
                        <defs>
                        <filter id="filter0_d_8_1922" x="0.209957" y="0" width="20.7565" height="21.1293" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                        <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                        <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                        <feOffset dy="5.22412"/>
                        <feGaussianBlur stdDeviation="2.61206"/>
                        <feColorMatrix type="matrix" values="0 0 0 0 0.827451 0 0 0 0 0.65098 0 0 0 0 0.498039 0 0 0 0.17 0"/>
                        <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_8_1922"/>
                        <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_8_1922" result="shape"/>
                        </filter>
                        </defs>
                        </svg>
                    `;

                        // Create Image object to draw SVG
                        const svgImage = new Image();
                        const svgBlob = new Blob([svgString], {
                            type: 'image/svg+xml'
                        });
                        const url = URL.createObjectURL(svgBlob);

                        svgImage.src = url;
                        svgImage.onload = function() {
                            ctx.drawImage(svgImage, svgX, svgY);
                            URL.revokeObjectURL(url); // Release object URL after drawing
                        };
                    }
                }
            }]
        }

        const ctx = document.getElementById('line-chart').getContext("2d");
        const my_chart = new Chart(ctx, chart);
        const initChart = async () => {
            $.ajax({
                url: '{{ route('dashboard') }}',
                type: "GET",
                data: {
                    year: $('#year').val(),
                },
                success: function(response) {
                    if (response.data) {
                        my_chart.data = response.data;
                        my_chart.options.scales.y.ticks.max = response.data.maxValue;
                        my_chart.options.scales.y.ticks.stepSize = response.data.maxValue / 5;
                        my_chart.update();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching chart data:", error);
                }
            })
        }

        function updateChart(year) {
            $.ajax({
                url: '{{ route('dashboard') }}',
                type: 'GET',
                data: {
                    year: year
                },
                success: function(response) {
                    if (response.data) {
                        my_chart.data = response.data;
                        my_chart.options.scales.y.ticks.max = response.data.maxValue;
                        my_chart.options.scales.y.ticks.stepSize = response.data.maxValue / 5;
                        my_chart.update();
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching chart data:", error);
                }
            })
        }
    </script>
@endpush
