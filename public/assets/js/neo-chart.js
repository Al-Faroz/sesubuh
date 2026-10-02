        (function() {
    var canvas = document.getElementById('chart-harian');
    if (!canvas) return;
    var data = JSON.parse(document.getElementById('chart-data').textContent);
    var chart;

    function css(name) {
        return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
    }

    function rupiah(n) {
        return 'Rp ' + Number(n).toLocaleString('id-ID');
    }

    function build() {
        if (chart) chart.destroy();
        var text = css('--muted'),
            grid = css('--line');
        chart = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Pemasukan',
                    data: data.values,
                    backgroundColor: css('--accent'),
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function(t) {
                            return rupiah(t.yLabel);
                        }
                    }
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            fontColor: text,
                            callback: function(v) {
                                return v >= 1000 ? (v / 1000) + 'rb' : v;
                            }
                        },
                        gridLines: {
                            color: grid
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            fontColor: text
                        },
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
    }
    build();
    document.addEventListener('neo-theme', build);
})();
    