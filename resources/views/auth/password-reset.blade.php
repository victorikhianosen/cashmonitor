<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Login | Konrix - Responsive Tailwind Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="A fully featured admin theme which can be used to build CRM, CMS, etc." name="description">
    <meta content="coderthemes" name="author">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Icons css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css">

    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

<body class="bg-gradient-to-r from-rose-100 to-teal-100 dark:from-gray-700 dark:via-gray-900 dark:to-black">

    <!-- Start Page Content -->
    <div class="h-screen w-screen flex justify-center items-center">
        <div class="2xl:w-1/4 lg:w-1/3 md:w-1/2 w-full">
            <div class="card overflow-hidden sm:rounded-md rounded-none">
                <div class="p-6">
                    <a href="{{ url('/') }}" class="block mb-8">
                        <img class="h-6 block dark:hidden" src="{{ asset('assets/images/logo-dark.png') }}"
                            alt="Logo Dark">
                        <img class="h-6 hidden dark:block" src="{{ asset('assets/images/logo-light.png') }}"
                            alt="Logo Light">
                    </a>

                    <form method="POST" action="{{ route('resetpassword.store') }}">
                        @csrf
                        <input id="" name="email" type="hidden" value="{{ $email }}">


                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                for="password">Password</label>
                            <input id="password" name="password" type="password" class="form-input"
                                placeholder="Enter your password" required>
                            @error('password')
                                <span class="text-red-600">{{ $message }} </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-200 mb-2"
                                for="password">Password Confirmation</label>
                            <input id="password" name="password_confirmation" type="password" class="form-input"
                                placeholder="Confirm your password" required>
                            @error('password_confirmation')
                                <span class="text-red-600">{{ $message }} </span>
                            @enderror
                        </div>



                        <div class="flex justify-center mb-6">
                            <button type="submit" class="btn w-full text-white bg-primary">Submit</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- End Page Content -->

</body>

</html>
