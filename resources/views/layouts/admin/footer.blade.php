        <footer class="footer h-16 flex items-center px-6 bg-white shadow dark:bg-gray-800">
                <div class="flex justify-center w-full gap-4">
                    <div>

                        <a href="">CashMatrix</a>
                    </div>
                </div>
            </footer>
            <!-- Footer End -->

        </div>


    </div>

    <!-- Back to Top Button -->
    <button data-toggle="back-to-top"
        class="fixed hidden h-10 w-10 items-center justify-center rounded-full z-10 bottom-20 end-14 p-2.5 bg-primary cursor-pointer shadow-lg text-white">
        <i class="mgc_arrow_up_line text-lg"></i>
    </button>



    <!-- Plugin Js -->
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('assets/libs/@frostui/tailwindcss/frostui.js') }}"></script>

    <!-- App Js -->
    <script src="{{ asset('assets/js/app.js') }}"></script>

    <!-- Gridjs Plugin js -->
    <script src="{{ asset('assets/libs/gridjs/gridjs.umd.js') }}"></script>

    <!-- Gridjs Demo js -->
    <script src="{{ asset('assets/js/pages/table-gridjs.js') }}"></script>



    @include('layouts.sweet-alert')

</body>

</html>
