document.addEventListener('DOMContentLoaded', function () {
  var shell = document.querySelector('.neo-shell');
  if (!shell) return;
  var btn = document.querySelector('[data-neo-toggle]');
  function setOpen(o) {
    shell.classList.toggle('open', o);
    if (btn) btn.setAttribute('aria-expanded', o ? 'true' : 'false');
  }
  if (btn) btn.addEventListener('click', function () { setOpen(!shell.classList.contains('open')); });
  var scrim = document.querySelector('.neo-scrim');
  if (scrim) scrim.addEventListener('click', function () { setOpen(false); });
  document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setOpen(false); });
});
