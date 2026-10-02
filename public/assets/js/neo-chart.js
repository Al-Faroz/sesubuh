(function () {
  var canvas = document.getElementById('chart-harian');
  if (!canvas || typeof Chart === 'undefined') return;
  var data = JSON.parse(document.getElementById('chart-data').textContent);
  var chart;

  function css(n) { return getComputedStyle(document.documentElement).getPropertyValue(n).trim(); }
  function rgba(hex, a) {
    var h = hex.replace('#', '');
    if (h.length === 3) h = h.replace(/(.)/g, '$1$1');
    var n = parseInt(h, 16);
    return 'rgba(' + (n >> 16) + ',' + ((n >> 8) & 255) + ',' + (n & 255) + ',' + a + ')';
  }
  function rupiah(n) { return 'Rp ' + Number(n).toLocaleString('id-ID'); }

  function build() {
    if (chart) chart.destroy();
    var accent = css('--accent'), text = css('--muted'), grid = css('--line');
    chart = new Chart(canvas.getContext('2d'), {
      type: 'line',
      data: {
        labels: data.labels,
        datasets: [{
          label: 'Pemasukan',
          data: data.values,
          borderColor: accent,
          backgroundColor: rgba(accent, 0.18),
          borderWidth: 3,
          lineTension: 0.35,
          fill: true,
          pointRadius: 5,
          pointHoverRadius: 7,
          pointBackgroundColor: accent,
          pointBorderColor: css('--bg'),
          pointBorderWidth: 2
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { display: false },
        layout: { padding: { top: 8, right: 8 } },
        tooltips: {
          displayColors: false,
          callbacks: {
            label: function (t) { return rupiah(t.yLabel); },
            afterLabel: function (t) { return data.kelas[t.index] + ' dari ' + data.total + ' kelas setor'; }
          }
        },
        scales: {
          yAxes: [{
            ticks: {
              beginAtZero: true,
              fontColor: text,
              maxTicksLimit: 6,
              callback: function (v) { return v >= 1000 ? (v / 1000) + 'rb' : v; }
            },
            gridLines: { color: grid, zeroLineColor: grid }
          }],
          xAxes: [{ ticks: { fontColor: text }, gridLines: { display: false } }]
        }
      }
    });
  }
  build();
  document.addEventListener('neo-theme', build);
})();
