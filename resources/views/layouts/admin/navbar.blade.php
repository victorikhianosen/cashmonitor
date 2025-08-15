       <header class="app-header flex justify-between items-center px-4 gap-3">
                <!-- Sidenav Menu Toggle Button -->
                <button id="button-toggle-menu" class="nav-link p-2">
                    <span class="sr-only">Menu Toggle Button</span>
                    <span class="flex items-center justify-center h-6 w-6">
                        <i class="mgc_menu_line text-xl"></i>
                    </span>
                </button>

                <div class="relative">
                    <button data-fc-type="dropdown" data-fc-placement="bottom-end" type="button" class="nav-link">
                        <img src="assets/images/logo-icon.png" alt="user-image" class="rounded-full h-10 w-10 border-4 border-blue-600">
                    </button>
                    <div
                        class="fc-dropdown fc-dropdown-open:opacity-100 hidden opacity-0 w-44 z-50 transition-[margin,opacity] duration-300 mt-2 bg-white shadow-lg border rounded-lg p-2 border-gray-200 dark:border-gray-700 dark:bg-gray-800">

                        <hr class="my-2 -mx-2 border-gray-200 dark:border-gray-700">
                        <a class="flex items-center py-2 px-3 rounded-md text-sm text-gray-800 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-gray-300"
                            href="{{ route('admin.logout') }}">
                            <i class="mgc_exit_line  me-2"></i>
                            <span>Log Out</span>
                        </a>
                    </div>
                </div>
            </header>


