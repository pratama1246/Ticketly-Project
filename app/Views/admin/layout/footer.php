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

<div id="custom-confirm-modal" class="relative z-[60] hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
  
  <div id="modal-backdrop" class="fixed inset-0 bg-gray-900/75 transition-opacity duration-300 opacity-0"></div>

  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      
      <div id="modal-panel" class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all duration-300 opacity-0 scale-95 sm:my-8 sm:w-full sm:max-w-lg border border-gray-200">
        
        <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
          <div class="sm:flex sm:items-start">
            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
              </svg>
            </div>
            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
              <h3 class="text-xl font-bold leading-6 text-gray-900" id="modal-title">Title</h3>
              <div class="mt-2">
                <p class="text-sm text-gray-500" id="modal-message">Message</p>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
          <button type="button" id="btn-confirm" class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:w-auto transition-all">Ya</button>
          <button type="button" id="btn-cancel" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-all">Batal</button>
        </div>

      </div>
    </div>
  </div>
</div>

</body>
</html>