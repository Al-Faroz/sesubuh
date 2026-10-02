document.addEventListener('DOMContentLoaded', function () {
  // Menu geser (layar kecil)
  var shell = document.querySelector('.neo-shell');
  if (shell) {
    var btn = document.querySelector('[data-neo-toggle]');
    var setOpen = function (o) {
      shell.classList.toggle('open', o);
      if (btn) btn.setAttribute('aria-expanded', o ? 'true' : 'false');
    };
    if (btn) btn.addEventListener('click', function () { setOpen(!shell.classList.contains('open')); });
    var scrim = document.querySelector('.neo-scrim');
    if (scrim) scrim.addEventListener('click', function () { setOpen(false); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
  }

  // Tutup alert
  document.querySelectorAll('[data-close]').forEach(function (b) {
    b.addEventListener('click', function () { b.closest('.alert').remove(); });
  });

  // Dialog
  document.querySelectorAll('[data-dialog]').forEach(function (b) {
    b.addEventListener('click', function () {
      var d = document.getElementById(b.dataset.dialog);
      if (b.dataset.action) { var f = d.querySelector('form'); if (f) f.action = b.dataset.action; }
      if (b.dataset.fill) {
        var v = JSON.parse(b.dataset.fill);
        Object.keys(v).forEach(function (k) { var el = d.querySelector('[name="' + k + '"]'); if (el) el.value = v[k]; });
      }
      d.showModal();
    });
  });
  document.querySelectorAll('[data-close-dialog]').forEach(function (b) {
    b.addEventListener('click', function () { b.closest('dialog').close(); });
  });
  document.querySelectorAll('dialog.neo-dialog').forEach(function (d) {
    d.addEventListener('click', function (e) { if (e.target === d) d.close(); });
  });

  // Konfirmasi sebelum kirim form
  document.querySelectorAll('form[data-confirm]').forEach(function (f) {
    f.addEventListener('submit', function (e) { if (!confirm(f.dataset.confirm)) e.preventDefault(); });
  });
});

// Mode gelap/terang
(function () {
  var root = document.documentElement;
  function apply(t, save) {
    root.setAttribute('data-theme', t);
    if (save) { try { localStorage.setItem('neo-theme', t); } catch (e) {} }
    var dark = t === 'dark';
    document.querySelectorAll('[data-theme-toggle]').forEach(function (b) {
      b.setAttribute('aria-pressed', dark ? 'true' : 'false');
      b.setAttribute('aria-label', dark ? 'Ganti ke mode terang' : 'Ganti ke mode gelap');
      var i = b.querySelector('i'); if (i) i.className = (dark ? 'fas fa-sun' : 'fas fa-moon') + (i.className.match(/ic-\w+/) ? ' ' + i.className.match(/ic-\w+/)[0] : '');
    });
    document.dispatchEvent(new CustomEvent('neo-theme', { detail: t }));
  }
  document.addEventListener('DOMContentLoaded', function () {
    apply(root.getAttribute('data-theme') || 'light', false);
    document.querySelectorAll('[data-theme-toggle]').forEach(function (b) {
      b.addEventListener('click', function () { apply(root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark', true); });
    });
  });
})();
