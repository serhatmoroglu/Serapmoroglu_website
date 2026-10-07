@push('head')<link rel="stylesheet" href="/vendor/quill/quill.snow.css">@endpush
@push('scripts')
<script src="/vendor/quill/quill.js"></script>
<script>
document.querySelectorAll('.rich').forEach(function (box) {
  var id = box.querySelector('[id^=ed-]').id.slice(3), input = document.getElementById('in-' + id);
  var q = new Quill('#ed-' + id, {theme: 'snow', modules: {toolbar: {container: [
    [{header: [2, 3, false]}], ['bold', 'italic', 'underline'], [{list: 'ordered'}, {list: 'bullet'}], ['link', 'image'], ['clean']],
    handlers: {image: function () {
      var f = document.createElement('input'); f.type = 'file'; f.accept = 'image/*';
      f.onchange = function () {
        var fd = new FormData(); fd.append('file', f.files[0]); fd.append('_token', document.querySelector('meta[name=csrf]').content);
        fetch('{{ route('admin.upload') }}', {method: 'POST', body: fd, headers: {'Accept': 'application/json'}})
          .then(function (r) { return r.json(); }).then(function (j) { if (j.url) q.insertEmbed(q.getSelection(true).index, 'image', j.url); else alert('Görsel yüklenemedi.'); })
          .catch(function () { alert('Görsel yüklenemedi.'); });
      }; f.click();
    }}}}});
  box.closest('form').addEventListener('submit', function () { input.value = q.getSemanticHTML().replace(/&nbsp;/g, ' '); });
});
</script>
@endpush
