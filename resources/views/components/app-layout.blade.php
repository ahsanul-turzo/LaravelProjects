<x-layouts.app {{ $attributes }}>
    <div class="flex">
        {{-- Include Sidebar --}}
        <x-sidebar />

        {{-- Main Content Area --}}
        <div class="flex-1 lg:ml-64">
            {{-- Mobile Header with Menu Toggle --}}
            <div class="lg:hidden bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-4 py-3 flex items-center justify-between sticky top-0 z-30">
                <button data-drawer-toggle="sidebar" type="button"
                        class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path clip-rule="evenodd" fill-rule="evenodd" d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                    </svg>
                </button>
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"></path>
                        <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"></path>
                    </svg>
                    <span class="ml-2 text-xl font-semibold text-gray-900 dark:text-white">SocialApp</span>
                </div>
                <div class="w-10"></div> {{-- Spacer for centering --}}
            </div>

            {{-- Page Content --}}
            <main class="min-h-screen bg-gray-50 dark:bg-gray-900">
                {{ $slot }}
            </main>
        </div>
    </div>

    @stack('scripts')
</x-layouts.app>
