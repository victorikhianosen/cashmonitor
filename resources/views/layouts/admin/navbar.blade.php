<header class="app-header flex justify-between items-center px-4 gap-3 bg-white dark:bg-gray-900 shadow-sm">
  <!-- Sidenav Menu Toggle Button -->
  <button id="button-toggle-menu" class="nav-link p-2">
    <span class="sr-only">Menu Toggle Button</span>
    <span class="flex items-center justify-center h-6 w-6">
      <i class="mgc_menu_line text-xl"></i>
    </span>
  </button>

  <!-- Profile Dropdown -->
  <div class="relative">
    <button data-fc-type="dropdown" data-fc-placement="bottom-end" type="button" class="nav-link flex items-center gap-2">
      <img src="{{ asset('assets/images/logo-icon.png') }}"
           alt="user-image"
           class="rounded-full h-12 w-12 border-2 border-blue-600">
    </button>

    <div
      class="fc-dropdown fc-dropdown-open:opacity-100 hidden opacity-0
             w-[22rem] max-w-[calc(100vw-2rem)]  <!-- fixed/safe width -->
             z-50 transition-all duration-300 mt-3
             bg-white shadow-2xl border rounded-2xl p-5 overflow-hidden
             border-gray-200 dark:border-gray-700 dark:bg-gray-800">

      <!-- User Info -->
      <div class="flex items-center gap-4 mb-4">
        <img src="https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff"
             alt="profile"
             class="h-14 w-14 rounded-full border border-gray-300 shrink-0">
        <!-- let text shrink inside flex and ellipsize -->
        <div class="min-w-0">
          <p class="text-base font-semibold text-gray-900 dark:text-gray-100 truncate" title="John Doe">
           {{ Session::get('user.name') }}
          </p>
          <p class="text-sm text-gray-500 dark:text-gray-400 truncate"
             title="johndoe@example.comlorem88575858458">
           {{ Session::get('user.email') }}
          </p>
        </div>
      </div>

      <hr class="my-3 border-gray-200 dark:border-gray-700">

      <a class="flex items-center py-3 px-4 rounded-lg text-base text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-700 dark:hover:text-red-200"
         href="{{ route('admin.logout') }}">
        <i class="mgc_exit_line me-2 text-lg"></i>
        <span>Log Out</span>
      </a>
    </div>
  </div>
</header>
