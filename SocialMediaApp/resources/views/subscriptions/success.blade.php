<x-layouts.app>
    <div class="max-w-2xl mx-auto px-4 py-12">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8 text-center">
            {{-- Success Icon --}}
            <div class="mb-6">
                <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-green-100">
                    <svg class="h-12 w-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>

            {{-- Success Message --}}
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                Subscription Successful!
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 mb-8">
                Thank you for subscribing! Your badge is now active.
            </p>

            {{-- Badge Preview --}}
            @if(request()->query('type') === 'verified' || auth()->user()->badge_type === 'verified')
                <div class="mb-8 inline-flex items-center px-6 py-3 bg-blue-100 dark:bg-blue-900 rounded-lg">
                    <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-2 text-lg font-semibold text-blue-900 dark:text-blue-100">Verified Badge Activated</span>
                </div>
            @elseif(request()->query('type') === 'purple' || auth()->user()->badge_type === 'purple')
                <div class="mb-8 inline-flex items-center px-6 py-3 bg-purple-100 dark:bg-purple-900 rounded-lg">
                    <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span class="ml-2 text-lg font-semibold text-purple-900 dark:text-purple-100">Purple Badge Activated</span>
                </div>
            @endif

            {{-- What's Next --}}
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">What's Next?</h2>
                <ul class="text-left space-y-2 max-w-md mx-auto mb-6">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Your badge is now visible on your profile and posts</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Your subscription will automatically renew each month</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 text-green-500 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">You can cancel anytime from your subscription settings</span>
                    </li>
                </ul>

                {{-- Action Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('dashboard') }}" class="px-6 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Go to Dashboard
                    </a>
                    <a href="{{ route('feed') }}" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        View Feed
                    </a>
                </div>
            </div>

            {{-- Receipt Info --}}
            <div class="mt-8 text-sm text-gray-500 dark:text-gray-400">
                <p>A receipt has been sent to your email address.</p>
                <p class="mt-1">Need help? <a href="#" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400">Contact Support</a></p>
            </div>
        </div>
    </div>
</x-layouts.app>
