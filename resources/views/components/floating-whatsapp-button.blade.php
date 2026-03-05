<!-- Floating Social Buttons - Right Side -->
<div class="fixed top-1/2 right-0 z-50 transform -translate-y-1/2 flex flex-col items-end gap-2">

  <!-- WhatsApp Button -->
  <button id="toggleFormBtn" aria-label="Open WhatsApp contact form"
    class="group flex items-center justify-end">
    <div class="bg-green-500 w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                group-hover:w-12 group-hover:brightness-110 transition-all duration-300">
      <i class="fab fa-whatsapp text-white text-lg"></i>
    </div>
  </button>

  <!-- Instagram Button -->
  <a href="https://www.instagram.com/surveycenterindonesia/" target="_blank"
     aria-label="Follow on Instagram" class="group flex items-center justify-end">
    <div class="w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400
                group-hover:w-12 group-hover:brightness-110 transition-all duration-300">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
        <path d="M7.75 2h8.5A5.75 5.75 0 0 1 22 7.75v8.5A5.75 5.75 0 0 1 16.25 22h-8.5A5.75 5.75 0 0 1 2 16.25v-8.5A5.75 5.75 0 0 1 7.75 2zm0 1.5A4.25 4.25 0 0 0 3.5 7.75v8.5A4.25 4.25 0 0 0 7.75 20.5h8.5a4.25 4.25 0 0 0 4.25-4.25v-8.5A4.25 4.25 0 0 0 16.25 3.5h-8.5zm8.75 2.75a.75.75 0 1 1 0 1.5.75.75 0 0 1 0-1.5zm-4.75 1a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm0 1.5a3.5 3.5 0 1 0 0 7 3.5 3.5 0 0 0 0-7z"/>
      </svg>
    </div>
  </a>

  <!-- TikTok Button -->
  <a href="https://www.tiktok.com/@surveycenter.indonesia" target="_blank"
     aria-label="Follow on TikTok" class="group flex items-center justify-end">
    <div class="w-10 h-10 rounded-l-xl rounded-r-none flex items-center justify-center shadow-lg
                bg-gray-900
                group-hover:w-12 group-hover:brightness-125 transition-all duration-300">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256" class="w-5 h-5" fill="white">
        <path d="M176.35,24H152a8,8,0,0,0-8,8V156a20,20,0,1,1-20-20,8,8,0,0,0,8-8V104a8,8,0,0,0-8-8A52,52,0,1,0,184,148V98.22a91.12,91.12,0,0,0,40,9.47,8,8,0,0,0,8-8V76.32a8,8,0,0,0-7.12-8A56,56,0,0,1,176.35,24Z"/>
      </svg>
    </div>
  </a>

</div>


<!-- CRM Form Modal -->
<div id="crmFormModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
  <div class="bg-white w-full max-w-lg mx-4 rounded-xl shadow-lg p-8 relative">

    <!-- Close Button -->
    <button id="closeFormBtn" class="absolute top-3 right-3 text-gray-600 hover:text-gray-800 text-xl font-bold" aria-label="Close modal">
      &times;
    </button>

    <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Hubungi Admin</h2>

    <!-- Success Message -->
    @if (session('success'))
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
      </div>
    @endif

    <!-- Error Messages -->
    @if ($errors->any())
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <!-- CRM Form -->
    <form action="{{ route('crm.customers.store.user') }}" method="POST" class="space-y-6">
      @csrf
      <div>
        <label for="full_name" class="block text-sm font-medium text-gray-700">Full Name</label>
        <input type="text" name="full_name" id="full_name" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm px-3 py-2">
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" name="email" id="email" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm px-3 py-2">
      </div>

      <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
        <input type="text" name="phone" id="phone" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm px-3 py-2">
      </div>

      <div>
        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
        <textarea name="notes" id="notes" rows="4" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring-orange-500 focus:border-orange-500 sm:text-sm px-3 py-2"></textarea>
      </div>

      <div>
        <button type="submit" class="w-full bg-orange-500 hover:bg-orange-600 text-white py-2 px-4 rounded-md font-semibold shadow-md transition">
          Submit
        </button>
      </div>
    </form>
  </div>
</div>

<!-- Font Awesome CSS untuk WhatsApp icon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- JavaScript untuk toggle modal -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const toggleBtn = document.getElementById('toggleFormBtn');
    const modal = document.getElementById('crmFormModal');
    const closeBtn = document.getElementById('closeFormBtn');

    if (toggleBtn && modal && closeBtn) {
      toggleBtn.addEventListener('click', () => {
        modal.classList.remove('hidden');
      });

      closeBtn.addEventListener('click', () => {
        modal.classList.add('hidden');
      });

      modal.addEventListener('click', (e) => {
        if (e.target === modal) {
          modal.classList.add('hidden');
        }
      });
    }
  });
</script>
