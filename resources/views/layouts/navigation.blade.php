<nav class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-100 dark:border-gray-700 transition-colors duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- لوگو -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="{{ asset('images/Artiman-logo.svg') }}" alt="ArtimanLeads" class="h-9 w-auto brightness-0 invert dark:brightness-100 dark:invert-0 transition-all">
                </a>
            </div>

            <!-- منوی اصلی -->
            <div class="hidden md:flex items-center space-x-4 space-x-reverse">
                <a href="{{ route('dashboard') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                    داشبورد
                </a>
                
                @if(auth()->user()->role === 'marketer')
                    <a href="{{ route('projects.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.*') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                        📋 لیدهای من
                    </a>
                    <a href="{{ route('projects.select-type') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.select-type') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                        ثبت لید جدید
                    </a>
                @elseif(auth()->user()->role === 'sales_expert')
                    <a href="{{ route('projects.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.index') ? 'text-purple-600 dark:text-purple-400 font-bold' : '' }}">
                         لیدهای من
                    </a>
                    <a href="{{ route('projects.assigned-to-me') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.assigned-to-me') ? 'text-purple-600 dark:text-purple-400 font-bold' : '' }}">
                        📋 لیدهای ارجاع داده شده به من
                    </a>
                    <a href="{{ route('projects.select-type') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.select-type') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                        ثبت لید جدید
                    </a>
                    <a href="{{ route('call-logs.select-project') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium">
                         ثبت تماس جدید
                    </a>
                    <a href="{{ route('call-logs.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-purple-600 dark:hover:text-purple-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('call-logs.*') ? 'text-purple-600 dark:text-purple-400 font-bold' : '' }}">
                        📋 تماس‌ها
                    </a>
                @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                    <a href="{{ route('projects.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.*') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                         لیست لیدها
                    </a>
                    <a href="{{ route('projects.select-type') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.select-type') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                        ثبت لید جدید
                    </a>
                    <a href="{{ route('assignments.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('assignments.*') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                        🔄 ارجاع‌ها
                    </a>
                    <a href="{{ route('call-logs.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('call-logs.*') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                        📋 تماس‌ها
                    </a>
                @endif
                
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('backup.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('backup.*') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                        💾 بکاپ
                    </a>
                    <a href="{{ route('admin.users') }}" class="text-gray-700 dark:text-gray-300 hover:text-[#04BA07] dark:hover:text-green-400 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('admin.users*') ? 'text-[#04BA07] dark:text-green-400 font-bold' : '' }}">
                         مدیریت کاربران
                    </a>
                @endif
            </div>

            <!-- منوی کاربر و نوتیفیکیشن -->
            <div class="flex items-center space-x-3 space-x-reverse">
                <!-- دکمه تغییر تم -->
                <button onclick="toggleDarkMode()" class="p-2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-yellow-400 focus:outline-none transition-colors" aria-label="تغییر حالت شب و روز">
                    <svg id="sunIconNav" class="w-6 h-6 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <svg id="moonIconNav" class="w-6 h-6 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                </button>

                <!-- نوتیفیکیشن -->
                <div class="relative">
                    <button onclick="toggleNotifications()" class="relative p-2 text-gray-400 dark:text-gray-500 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @php
                            $unreadCount = App\Models\Notification::where('user_id', auth()->id())->where('is_read', false)->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">
                                {{ $unreadCount }}
                            </span>
                        @endif
                    </button>
                    
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border dark:border-gray-700 max-h-96 overflow-y-auto">
                        <div class="px-4 py-2 border-b dark:border-gray-700 font-bold dark:text-white">نوتیفیکیشن‌ها</div>
                        @php
                            $notifications = App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get();
                        @endphp
                        @forelse($notifications as $notification)
                            <a href="{{ $notification->link ?? '#' }}" class="block px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 border-b dark:border-gray-700 {{ $notification->is_read ? '' : 'bg-blue-50 dark:bg-blue-900/20' }}">
                                <div class="flex justify-between">
                                    <span class="font-medium text-sm dark:text-gray-200">{{ $notification->title }}</span>
                                    <span class="text-xs text-gray-400 dark:text-gray-500">{{ \Morilog\Jalali\Jalalian::fromCarbon($notification->created_at)->format('Y/m/d H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $notification->message }}</p>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-gray-500 dark:text-gray-400 text-sm">هیچ نوتیفیکیشنی وجود ندارد.</div>
                        @endforelse
                        <div class="px-4 py-2 border-t dark:border-gray-700 text-center">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">مشاهده همه</a>
                        </div>
                    </div>
                </div>

                <!-- منوی کاربر -->
                <div class="relative">
                    <button onclick="toggleDropdown()" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium flex items-center">
                        {{ Auth::user()->name }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div id="dropdownMenu" class="hidden absolute left-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50 border dark:border-gray-700">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">پروفایل</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('backup.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">💾 بکاپ</a>
                            <a href="{{ route('admin.users') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">👥 مدیریت کاربران</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">خروج</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- دکمه همبرگر برای موبایل -->
            <div class="md:hidden">
                <button onclick="toggleMobileMenu()" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- منوی موبایل -->
    <div id="mobileMenu" class="hidden md:hidden bg-white dark:bg-gray-800 border-t dark:border-gray-700">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                داشبورد
            </a>
            
            @if(auth()->user()->role === 'marketer')
                <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('projects.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    لیدهای من
                </a>
                <a href="{{ route('projects.select-type') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('projects.select-type') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    ثبت لید جدید
                </a>
            @elseif(auth()->user()->role === 'sales_expert')
                <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('projects.index') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    لیدهای من
                </a>
                <a href="{{ route('projects.assigned-to-me') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('projects.assigned-to-me') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    لیدهای ارجاع داده شده به من
                </a>
                <a href="{{ route('projects.select-type') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('projects.select-type') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    ثبت لید جدید
                </a>
                <a href="{{ route('call-logs.select-project') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    ثبت تماس جدید
                </a>
                <a href="{{ route('call-logs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('call-logs.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    تماس‌ها
                </a>
            @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('projects.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    لیست لیدها
                </a>
                <a href="{{ route('projects.select-type') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('projects.select-type') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    ثبت لید جدید
                </a>
                <a href="{{ route('assignments.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('assignments.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    ارجاع‌ها
                </a>
                <a href="{{ route('call-logs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 {{ request()->routeIs('call-logs.*') ? 'bg-gray-100 dark:bg-gray-700' : '' }}">
                    تماس‌ها
                </a>
            @endif
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('backup.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                    💾 بکاپ
                </a>
                <a href="{{ route('admin.users') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                     مدیریت کاربران
                </a>
            @endif
            
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">پروفایل</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-right px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">خروج</button>
            </form>
        </div>
    </div>
</nav>

<script>
    function toggleDropdown() {
        const menu = document.getElementById('dropdownMenu');
        menu.classList.toggle('hidden');
    }

    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('hidden');
    }

    function toggleNotifications() {
        const dropdown = document.getElementById('notificationDropdown');
        dropdown.classList.toggle('hidden');
        
        if (!dropdown.classList.contains('hidden')) {
            fetch('{{ route("notifications.mark-read") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            });
        }
    }

    document.addEventListener('click', function(event) {
        const menu = document.getElementById('dropdownMenu');
        if (!menu.classList.contains('hidden')) {
            if (!event.target.closest('.relative') && !event.target.closest('button')) {
                menu.classList.add('hidden');
            }
        }
        
        const notifMenu = document.getElementById('notificationDropdown');
        if (!notifMenu.classList.contains('hidden')) {
            if (!event.target.closest('.relative')) {
                notifMenu.classList.add('hidden');
            }
        }
    });
</script>