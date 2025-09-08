<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - CMS Sekolah</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>
<body class="h-full">
    <div class="min-h-full flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 login-bg">
        <div class="max-w-md w-full space-y-8">
            <!-- Header -->
            <div class="text-center">
                <div class="mx-auto h-20 w-20 glass-effect rounded-full flex items-center justify-center mb-6">
                    <i class="fas fa-graduation-cap text-3xl text-white"></i>
                </div>
                <h2 class="text-3xl font-bold text-white mb-2">
                    Admin CMS
                </h2>
                <p class="text-white/80 text-sm">
                    Silakan masuk untuk mengelola website sekolah
                </p>
            </div>

            <!-- Login Form -->
            <div class="glass-effect rounded-xl p-8 shadow-2xl">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg text-sm">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-white mb-2">
                            <i class="fas fa-envelope mr-2"></i>Email
                        </label>
                        <div class="relative">
                            <input id="email"
                                type="email"
                                name="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="username"
                                placeholder="admin@sekolah.id"
                                class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200" />
                        </div>
                        @if ($errors->has('email'))
                            <p class="mt-2 text-sm text-red-200">{{ $errors->first('email') }}</p>
                        @endif
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-white mb-2">
                            <i class="fas fa-lock mr-2"></i>Password
                        </label>
                        <div class="relative">
                            <input id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="w-full px-4 py-3 bg-white/20 border border-white/30 rounded-lg text-white placeholder-white/60 focus:outline-none focus:ring-2 focus:ring-white/50 focus:border-transparent transition-all duration-200" />
                        </div>
                        @if ($errors->has('password'))
                            <p class="mt-2 text-sm text-red-200">{{ $errors->first('password') }}</p>
                        @endif
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center">
                        <input id="remember_me"
                            type="checkbox"
                            name="remember"
                            class="h-4 w-4 bg-white/20 border-white/30 rounded focus:ring-white/50 focus:ring-2">
                        <label for="remember_me" class="ml-3 text-sm text-white">
                            Ingat saya
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                            class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-gray-800 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white/50 transition-all duration-200 transform hover:scale-105">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Masuk ke Admin
                        </button>
                    </div>
                </form>

                <!-- Footer -->
                <div class="mt-8 pt-6 border-t border-white/20">
                    <p class="text-center text-xs text-white/60">
                        © {{ date('Y') }} CMS Sekolah. Hanya untuk administrator.
                    </p>
                </div>
            </div>

            <!-- Back to Website -->
            <div class="text-center">
                <a href="{{ route('home') }}"
                   class="inline-flex items-center text-white/80 hover:text-white text-sm transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali ke Website
                </a>
            </div>
        </div>
    </div>
</body>
</html>
