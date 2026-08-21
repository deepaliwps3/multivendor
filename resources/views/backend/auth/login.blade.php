<x-guest-layout>
    <div class="auth-box row">
        <div class="col-lg-7 col-md-5 modal-bg-img"
            style="background-image: url({{ asset('assets/extra-assets/images/big/3.jpg') }});">
        </div>
        <div class="col-lg-5 col-md-7 bg-white">
            <div class="p-3">
                <div class="text-center">
                    <img src="{{ asset('assets/extra-assets/images/big/icon.png') }}" alt="wrapkit">
                </div>
                <h2 class="mt-3 text-center">Sign In</h2>
                <p class="text-center">Enter your email address and password to access admin panel.</p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4 text-center text-success" :status="session('status')" />

                <form class="mt-4" method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label text-dark" for="email">Email Address</label>
                                <input class="form-control @error('email') is-invalid @enderror" id="email"
                                    type="email" name="email" value="{{ old('email') }}" required autofocus
                                    autocomplete="username" placeholder="Enter your email address">
                                @error('email')
                                    <span class="invalid-feedback d-block mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group mb-3">
                                <label class="form-label text-dark" for="pwd">Password</label>
                                <input class="form-control @error('password') is-invalid @enderror" id="pwd"
                                    type="password" name="password" required autocomplete="current-password"
                                    placeholder="Enter your password">
                                @error('password')
                                    <span class="invalid-feedback d-block mt-1" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3 d-flex justify-content-between align-items-center">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                                <label class="form-check-label text-dark" for="remember_me">
                                    Remember me
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-secondary small">Forgot
                                    Password?</a>
                            @endif
                        </div>
                        <div class="col-lg-12 text-center">
                            <button type="submit" class="btn w-100 btn-dark">Sign In</button>
                        </div>
                        @if (Route::has('register'))
                            <div class="col-lg-12 text-center mt-4">
                                Don't have an account? <a href="{{ route('register') }}" class="text-danger">Sign Up</a>
                            </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
