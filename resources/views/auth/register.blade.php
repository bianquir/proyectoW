<x-guest-layout>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <form method="POST" action="{{ route('register') }}" class="w-100" style="max-width: 400px;">
            @csrf
            <div class="text-center">
                <a href="/">
                    <img src="{{ asset('img/logo.webp') }}" alt="Logo" class="w-20 h-20 rounded-full" style="height: 150px">
                </a>
            </div>
            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">{{ __('Name') }}</label>
                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" maxlength="25">
                @error('name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">{{ __('Email') }}</label>
                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="username" maxlength="30">
                @error('email')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">{{ __('Password') }}</label>
                <input id="password" type="password" class="form-control" name="password" required autocomplete="new-password">
                @error('password')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-3">
                <label for="password_confirmation" class="form-label">{{ __('Confirm Password') }}</label>
                <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <!-- Register and Login Link -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <a href="{{ route('login') }}" class="text-decoration-none text-secondary">{{ __('Already registered?') }}</a>
                <button type="submit" class="btn btn-primary">{{ __('Register') }}</button>
            </div>
        </form>
    </div>
</x-guest-layout>


