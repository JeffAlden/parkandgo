@extends('layouts.app')

@section('content')

<style>
    body {
        overflow: hidden; /* Disable scrolling */
    }
</style> 

    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                @include('layouts.navbars.guest.navbar')
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
                            <div class="card card-plain">
                                <div class="card-header pb-0 text-start">
                                    <h4 class="font-weight-bolder">Park and Go</h4>
                                    <p class="mb-0">Enter your email and password to sign in</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="{{ route('login.perform') }}">
                                        @csrf
                                        @method('post')
                                        <div class="flex flex-col mb-3">
                                            <label for="email" class="mb-1">Email:</label>
                                            <input type="email" id="email" name="email"
                                                class="form-control form-control-lg"
                                                value="" aria-label="Email">
                                            @error('email')
                                                <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                                            @enderror
                                        </div>
                                        <div class="flex flex-col mb-3">
                                            <label for="password" class="mb-1">Password:</label>
                                            <input type="password" id="password" name="password"
                                                class="form-control form-control-lg" aria-label="Password" value="">
                                            @error('password')
                                                <p class="text-danger text-xs pt-1"> {{ $message }} </p>
                                            @enderror
                                        </div>

                                        <div class="form-check form-switch">
                                            <input class="form-check-input" name="remember" type="checkbox" id="rememberMe">
                                            <label class="form-check-label" for="rememberMe">Remember me</label>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit"
                                                class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Sign in</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-1 text-sm mx-auto">
                                        Forgot you password? Reset your password
                                        <a href="{{ route('reset-password') }}"
                                            class="text-primary text-gradient font-weight-bold">here</a>
                                    </p>
                                </div>
                                {{-- <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-sm mx-auto">
                                        Don't have an account?
                                        <a href="{{ route('register') }}"
                                            class="text-primary text-gradient font-weight-bold">Sign up</a>
                                    </p>
                                </div> --}}
                            </div>
                        </div>
                        <div class="col-6 d-lg-flex d-none my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column"
     style="height: 100vh;"> <!-- Set the height to 100% of the viewport height -->
    <div class="position-relative h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
         style="background-image: url('{{ asset('img/parking1.jpg') }}');
                background-size: cover; /* Adjust background size */
                background-position: center; /* Center the background image */
                background-repeat: no-repeat;"> <!-- Do not repeat the background image -->
        <!-- Removed the orange mask span -->
    </div>
</div>
                        </div>
                                <h4 class="mt-5 text-white font-weight-bolder position-relative">"Attention is the new
                                    currency"</h4>
                                <p class="text-white position-relative">The more effortless the writing looks, the more
                                    effort the writer actually put into the process.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script>
        // Get the input elements by their ID
        var emailInput = document.getElementById('email');
        var passwordInput = document.getElementById('password');
        var rememberMeCheckbox = document.getElementById('rememberMe');

        // Check if the 'rememberMe' item is in localStorage and set the checkbox and input fields state accordingly
        if (localStorage.getItem('rememberMe') === 'true') {
            rememberMeCheckbox.checked = true;
            emailInput.value = localStorage.getItem('email') || '';
            passwordInput.value = localStorage.getItem('password') || '';
        }

        // Add an event listener to update the 'rememberMe', 'email', and 'password' items in localStorage when the checkbox state changes
        rememberMeCheckbox.addEventListener('change', function() {
            localStorage.setItem('rememberMe', rememberMeCheckbox.checked);
            if (rememberMeCheckbox.checked) {
                localStorage.setItem('email', emailInput.value);
                localStorage.setItem('password', passwordInput.value);
            } else {
                localStorage.removeItem('email');
                localStorage.removeItem('password');
            }
        });

        // Update email and password in localStorage when they are changed
        emailInput.addEventListener('input', function() {
            if (rememberMeCheckbox.checked) {
                localStorage.setItem('email', emailInput.value);
            }
        });

        passwordInput.addEventListener('input', function() {
            if (rememberMeCheckbox.checked) {
                localStorage.setItem('password', passwordInput.value);
            }
        });
    </script>

@endsection
