(function () {
  var b = document.getElementById('burger'), m = document.getElementById('mnav');
  if (b && m) b.addEventListener('click', function () {
    var open = m.classList.toggle('open');
    b.setAttribute('aria-expanded', String(open));
  });
  // Form gönderiminde çift tıklamayı engelle
  document.querySelectorAll('form[data-lead]').forEach(function (f) {
    f.addEventListener('submit', function () {
      var s = f.querySelector('button[type=submit]');
      if (s) { s.disabled = true; s.textContent = 'Gönderiliyor…'; }
    });
  });
})();
