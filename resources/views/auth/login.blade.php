@extends('layouts.guest.app')

@section('guest-section')
    <div class="bg-gradient-to-r from-rose-100 to-teal-100 dark:from-gray-700 dark:via-gray-900 dark:to-black">

        <div class="h-screen w-screen flex justify-center items-center">

            <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
                <div class="card overflow-hidden sm:rounded-md rounded-none">
                    <div class="p-6">
                        <a href="{{ route('home') }}" class="block mb-8">
                            <img class="h-6 block dark:hidden" src="assets/images/logo.png" alt="">
                            <img class="h-6 hidden dark:block" src="assets/images/logo.png" alt="">
                        </a>

                        <form action="{{ route('login.store') }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                    for="LoggingEmailAddress">Email Address</label>
                                <input name='email' autocomplete="off" id="LoggingEmailAddress" class="form-input" type="email" value="{{ old('email') }}"
                                    placeholder="Enter your email">
                                @error('email')
                                    <span class="text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                    for="loggingPassword">Password</label>
                                <input  autocomplete="off" name='password' id="loggingPassword" class="form-input" type="password"
                                    placeholder="Enter your password">
                                @error('password')
                                    <span class="text-red-600">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="flex items-end justify-end mb-4">

                                {{-- <a href="{{ route('forgetpassword') }}"
                                    class="text-sm text-primary border-b border-dashed border-primary">Forget Password ?</a> --}}
                            </div>


                            <div class="flex justify-center">
                                <button id="loginButton" type="submit"
                                    class="btn w-full text-white bg-primary flex items-center justify-center gap-2 py-3 rounded-lg transition-all duration-200 hover:opacity-90">
                                    <span id="loginText">Log In</span>
                                    <span id="loginLoading" class="hidden">
                                        <i class="fa-solid fa-spinner animate-spin"></i> Loading...
                                    </span>
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form'); // adjust if your form needs a specific selector
            const loginButton = document.getElementById('loginButton');
            const loginText = document.getElementById('loginText');
            const loginLoading = document.getElementById('loginLoading');

            form.addEventListener('submit', function() {
                loginButton.disabled = true;
                loginText.classList.add('hidden');
                loginLoading.classList.remove('hidden');
            });
        });
    </script>
@endsection
