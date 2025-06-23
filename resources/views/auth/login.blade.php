<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center px-4 py-">
        <!-- Background Overlay -->
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(99,102,241,0.2)_0%,_rgba(126,34,206,0)_70%)] z-0"></div>

        <!-- Card Container -->
        <div class="relative z-10 bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl p-8 w-full  transform transition-all duration-300 hover:scale-105">
            <!-- Header with Logo and Title -->
            <div class="text-center mb-8">
                <div class="text-blue-600 text-5xl mb-4">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-900 bg-gradient-to-r from-blue-600 to-sky-600 bg-clip-text text-transparent">Masuk ke Exsys</h2>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4 text-center text-sm text-gray-600" :status="session('status')" />

            <!-- Form Login -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="__('Email')" class="block text-gray-700 mb-2" />
                    <div class="relative">
                        <x-text-input id="email" class="block w-full rounded-lg border-2 border-blue-200 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all duration-300 px-4 py-2 bg-white/90 hover:bg-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-envelope"></i></span>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="__('Password')" class="block text-gray-700 mb-2" />
                    <div class="relative">
                        <x-text-input id="password" class="block w-full rounded-lg border-2 border-blue-200 focus:border-sky-500 focus:ring-2 focus:ring-sky-100 transition-all duration-300 px-4 py-2 bg-white/90 hover:bg-white" type="password" name="password" required autocomplete="current-password" />
                        <span class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400"><i class="fas fa-lock"></i></span>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-500" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-sky-600 focus:ring-2 focus:ring-sky-200 transition duration-200 h-5 w-5" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between mt-6">
                    @if (Route::has('password.request'))
                        <a class="text-sm text-gray-600 hover:text-sky-600 transition-colors duration-200" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif

                    <x-primary-button class="bg-gradient-to-r from-blue-600 to-sky-600 hover:from-blue-700 hover:to-sky-700 text-white px-6 py-2 rounded-lg font-medium transition-all duration-300 shadow-md hover:shadow-lg">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

                <!-- Tautan Daftar -->
                @if (Route::has('register'))
                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Belum punya akun? 
                            <a href="{{ route('register') }}" class="text-sky-600 hover:text-sky-700 transition-colors duration-200 font-medium">
                                Daftar sekarang
                            </a>
                        </p>
                    </div>
                @endif
            </form>
        </div>

        <!-- Font Awesome untuk ikon -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/js/all.min.js"></script>
    </div>
</x-guest-layout>