<nav class="bg-gray-800" x-data="{ isMobileMenuOpen: false, isProfileOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="shrink-0 text-indigo-500">
                    <svg class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
                    </svg>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <x-my-nav-link href="/" :current="request()->is('/')">Home</x-my-nav-link>
                        <x-my-nav-link href="/posts" :current="request()->is('posts')">Blog</x-my-nav-link>
                        <x-my-nav-link href="/about" :current="request()->is('about')">About</x-my-nav-link>
                        <x-my-nav-link href="/contact" :current="request()->is('contact')">Contact</x-my-nav-link>
                    </div>
                </div>
            </div>

            <!-- Sisi Kanan: Avatar & Profile Dropdown (Desktop) -->
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6">
                    <div class="relative ml-3" @click.outside="isProfileOpen = false">
                        <div>
                            @if (Auth::check())
                                <div>
                                    <button type="button" @click="isProfileOpen = !isProfileOpen"
                                        class="relative flex max-w-xs items-center rounded-full bg-gray-800 text-sm 
                                focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2
                                 focus:ring-offset-gray-800 cursor-pointer"
                                        id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                        <span class="sr-only">Open user menu</span>
                                        <img class="h-8 w-8 rounded-full"
                                            src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('/img/avatar.png') }}"
                                            alt="{{ Auth::user()->name }}">

                                        <div class="text-gray-300 text-sm ml-3">{{ Auth::user()->name }}</div>
                                        <div class="ms-1 text-gray-300 text-sm">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            @else
                                <a href="/login" class="text-gray-300 text-sm font-medium">Login</a>
                                <span class="text-gray-300 text-sm"> | </span>
                                <a href="/register" class="text-gray-300 text-sm font-medium">Register</a>
                            @endif
                        </div>

                        <!-- Dropdown Menu -->
                        <div x-show="isProfileOpen" x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            x-cloak>
                            <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Your
                                Profile</a>
                            <a href="/dashboard"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                            <form method="POST" action="/logout">
                                @csrf
                                <button type="submit"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                                    Log out</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Mobile Hamburger Menu -->
            <div class="-mr-2 flex md:hidden">
                <button type="button" @click="isMobileMenuOpen = !isMobileMenuOpen"
                    class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none">
                    <svg x-show="!isMobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg x-show="isMobileMenuOpen" class="block h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor" x-cloak>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>

    <!-- Mobile Menu Content -->
    <div x-show="isMobileMenuOpen" class="md:hidden" x-cloak>
        <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
            <x-my-nav-link class="block" href="/" :current="request()->is('/')">Home</x-my-nav-link>
            <x-my-nav-link class="block" href="/posts" :current="request()->is('posts')">Blog</x-my-nav-link>
            <x-my-nav-link class="block" href="/about" :current="request()->is('about')">About</x-my-nav-link>
            <x-my-nav-link class="block" href="/contact" :current="request()->is('contact')">Contact</x-my-nav-link>
        </div>
        <div class="border-t border-gray-700 pt-4 pb-3">
            @if (Auth::check())
                <div class="flex items-center px-5">
                    <div class="shrink-0">
                        <img class="h-10 w-10 rounded-full"
                            src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : asset('/img/avatar.png') }}"
                            alt="{{ Auth::user()->name }}">
                    </div>

                    <div class="ml-3">
                        <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>

                    </div>
                </div>

                <div class="mt-3 space-y-1 px-2">
                    <a href="/profile"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Your
                        Profile</a>
                    <a href="/dashboard"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Settings</a>
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit"
                            class="w-full  text-start block px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white cursor-pointer">
                            Log out</button>
                    </form>
                </div>
            @else
                <div class="mt-3 space-y-1 px-2">
                    <a href="/login"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white ">Log
                        in</a>
                    <a href="/register"
                        class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white ">Register</a>
                </div>
            @endif
        </div>
    </div>
</nav>
