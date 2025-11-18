</div> </div> <script src="<?= base_url('js/app.js') ?>"></script> 
<script>
  tinymce.init({
    selector: 'textarea#description', // <-- Ini menargetkan <textarea> dengan id="description"
    plugins: 'lists link code table autoresize', // 'autoresize' agar tingginya pas
    toolbar: 'undo redo | blocks | bold italic | bullist numlist | link | table | code',
    height: 300, // Tinggi awal
    skin: 'oxide', // Skin default
    content_css: 'default' 
    // Catatan: Kamu bisa dapat API key gratis di tiny.cloud untuk menghilangkan pesan "no-api-key"
  });
</script>
</body>
</html>