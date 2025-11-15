<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SocialApp - Connect, Share, Inspire</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-gray-50 dark:bg-gray-900">
    {{-- Navigation --}}
    <nav class="fixed w-full z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">SocialApp</span>
                </div>

                @if (Route::has('login'))
                    <div class="flex items-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-lg shadow-indigo-500/50">
                                    Get Started
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h1 class="text-5xl md:text-7xl font-bold text-gray-900 dark:text-white mb-6 leading-tight">
                    Connect with people<br>
                    <span class="bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">who inspire you</span>
                </h1>
                <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-400 mb-10 max-w-3xl mx-auto">
                    Share your thoughts, discover amazing content, and build meaningful connections in a vibrant community designed for authentic social interaction.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="px-8 py-4 text-lg font-semibold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition shadow-xl shadow-indigo-500/50 transform hover:scale-105">
                            Join SocialApp Free
                        </a>
                    @endif
                    <a href="#features" class="px-8 py-4 text-lg font-semibold text-gray-900 dark:text-white bg-white dark:bg-gray-800 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition border-2 border-gray-200 dark:border-gray-700">
                        Explore Features
                    </a>
                </div>

                {{-- Stats --}}
                <div class="mt-16 grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">1M+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Active Users</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">50M+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Posts Shared</div>
                    </div>
                    <div>
                        <div class="text-4xl font-bold text-indigo-600 dark:text-indigo-400">100M+</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">Connections Made</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">
                    Everything you need to stay connected
                </h2>
                <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                    Powerful features designed to make your social experience seamless and enjoyable
                </p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Share Your Story</h3>
                    <p class="text-gray-600 dark:text-gray-400">Create rich posts with images and text. Express yourself freely and share your moments with the world.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Build Your Network</h3>
                    <p class="text-gray-600 dark:text-gray-400">Follow friends, discover new connections, and grow your community with people who share your interests.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-14 h-14 bg-pink-100 dark:bg-pink-900 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-pink-600 dark:text-pink-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Engage & React</h3>
                    <p class="text-gray-600 dark:text-gray-400">Like, comment, and interact with content that resonates with you. Every interaction matters.</p>
                </div>

                {{-- Feature 4 --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Direct Messaging</h3>
                    <p class="text-gray-600 dark:text-gray-400">Have private conversations with your connections. Stay in touch with what matters most.</p>
                </div>

                {{-- Feature 5 --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-14 h-14 bg-green-100 dark:bg-green-900 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Discover Content</h3>
                    <p class="text-gray-600 dark:text-gray-400">Explore trending posts, find new people to follow, and discover content tailored to your interests.</p>
                </div>

                {{-- Feature 6 --}}
                <div class="p-8 bg-gray-50 dark:bg-gray-900 rounded-2xl hover:shadow-xl transition transform hover:-translate-y-1">
                    <div class="w-14 h-14 bg-yellow-100 dark:bg-yellow-900 rounded-xl flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Get Verified</h3>
                    <p class="text-gray-600 dark:text-gray-400">Stand out with verified or purple badges. Show your authenticity and gain trust within the community.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Benefits Section --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-6">
                        Why choose SocialApp?
                    </h2>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">100% Free to Join</h3>
                                <p class="text-gray-600 dark:text-gray-400">Create your account and start connecting immediately. No hidden fees or charges required.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Privacy First</h3>
                                <p class="text-gray-600 dark:text-gray-400">Your data is yours. We respect your privacy and give you full control over your content.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Modern & Fast</h3>
                                <p class="text-gray-600 dark:text-gray-400">Built with cutting-edge technology for a smooth, responsive experience on any device.</p>
                            </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="flex items-center justify-center w-12 h-12 rounded-xl bg-indigo-100 dark:bg-indigo-900">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">Active Community</h3>
                                <p class="text-gray-600 dark:text-gray-400">Join millions of users already sharing, connecting, and building meaningful relationships.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div class="aspect-square rounded-2xl bg-gradient-to-br from-indigo-500 to-purple-600 p-8 shadow-2xl">
                        <div class="h-full flex flex-col justify-center items-center text-white text-center">
                            <svg class="w-24 h-24 mb-6" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                            </svg>
                            <h3 class="text-3xl font-bold mb-4">Start Your Journey Today</h3>
                            <p class="text-indigo-100 mb-6">Join thousands of users sharing their stories and making connections every day.</p>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-8 py-3 bg-white text-indigo-600 rounded-lg font-semibold hover:bg-gray-100 transition transform hover:scale-105">
                                    Create Free Account
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-r from-indigo-600 to-purple-600">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to get started?
            </h2>
            <p class="text-xl text-indigo-100 mb-10">
                Join SocialApp today and be part of a vibrant community where connections matter.
            </p>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="inline-block px-10 py-4 text-lg font-semibold text-indigo-600 bg-white rounded-lg hover:bg-gray-100 transition shadow-2xl transform hover:scale-105">
                    Sign Up - It's Free
                </a>
            @endif
            <p class="mt-6 text-indigo-200 text-sm">
                Already have an account?
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="text-white font-semibold underline hover:text-indigo-100">Log in here</a>
                @endif
            </p>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="py-12 px-4 sm:px-6 lg:px-8 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center mb-4 md:mb-0">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                    </svg>
                    <span class="ml-2 text-xl font-bold text-gray-900 dark:text-white">SocialApp</span>
                </div>
                <div class="text-gray-600 dark:text-gray-400 text-sm">
                    &copy; {{ date('Y') }} SocialApp. Built with Laravel. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</body>
</html>
