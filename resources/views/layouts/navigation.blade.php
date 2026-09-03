<nav class="bg-white shadow-md">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- لوگو -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="flex items-center">
                    <img src="{{ asset('images/Artiman-logo.svg') }}" alt="ArtimanLeads" class="h-9 w-auto">
                </a>
            </div>

            <!-- منوی اصلی -->
            <div class="hidden md:flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('dashboard') ? 'text-green-600 font-bold' : '' }}">
                    داشبورد
                </a>
                
                @if(auth()->user()->role === 'marketer')
                    <a href="{{ route('projects.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.*') ? 'text-green-600 font-bold' : '' }}">
                        📋 لیدهای من
                    </a>
                    <a href="{{ route('projects.select-type') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.select-type') ? 'text-green-600 font-bold' : '' }}">
                        ثبت لید جدید
                    </a>
                @elseif(auth()->user()->role === 'sales_expert')
                    <a href="{{ route('projects.index') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.index') ? 'text-purple-600 font-bold' : '' }}">
                        📋 لیدهای من
                    </a>
                    <a href="{{ route('projects.assigned-to-me') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.assigned-to-me') ? 'text-purple-600 font-bold' : '' }}">
                        📋 لیدهای ارجاع داده شده به من
                    </a>
                    <a href="{{ route('projects.select-type') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.select-type') ? 'text-green-600 font-bold' : '' }}">
                        ثبت لید جدید
                    </a>
                    <a href="{{ route('call-logs.select-project') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium">
                        📞 ثبت تماس جدید
                    </a>
                    <a href="{{ route('call-logs.index') }}" class="text-gray-700 hover:text-purple-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('call-logs.*') ? 'text-purple-600 font-bold' : '' }}">
                        📋 تماس‌ها
                    </a>
                @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                    <a href="{{ route('projects.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.*') ? 'text-green-600 font-bold' : '' }}">
                        📋 لیست لیدها
                    </a>
                    <a href="{{ route('projects.select-type') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('projects.select-type') ? 'text-green-600 font-bold' : '' }}">
                        ثبت لید جدید
                    </a>
                    <a href="{{ route('assignments.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('assignments.*') ? 'text-green-600 font-bold' : '' }}">
                        🔄 ارجاع‌ها
                    </a>
                    <a href="{{ route('call-logs.index') }}" class="text-gray-700 hover:text-green-600 px-3 py-2 rounded-md text-sm font-medium {{ request()->routeIs('call-logs.*') ? 'text-green-600 font-bold' : '' }}">
                        📋 تماس‌ها
                    </a>
                @endif
                
                @if(auth()->user()->role === 'admin')
                    <x-nav-link :href="route('backup.index')" :active="request()->routeIs('backup.*')">
    {{ __('💾 بکاپ') }}
</x-nav-link>
                @endif
            </div>

            <!-- منوی کاربر و نوتیفیکیشن -->
            <div class="flex items-center space-x-3">
                <!-- نوتیفیکیشن -->
                <div class="relative">
                    <button onclick="toggleNotifications()" class="relative p-2 text-gray-400 hover:text-gray-600 focus:outline-none">
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
                    
                    <div id="notificationDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50 border max-h-96 overflow-y-auto">
                        <div class="px-4 py-2 border-b font-bold">نوتیفیکیشن‌ها</div>
                        @php
                            $notifications = App\Models\Notification::where('user_id', auth()->id())->latest()->take(10)->get();
                        @endphp
                        @forelse($notifications as $notification)
                            <a href="{{ $notification->link ?? '#' }}" class="block px-4 py-3 hover:bg-gray-50 border-b {{ $notification->is_read ? '' : 'bg-blue-50' }}">
                                <div class="flex justify-between">
                                    <span class="font-medium text-sm">{{ $notification->title }}</span>
                                    <span class="text-xs text-gray-400">{{ \Morilog\Jalali\Jalalian::fromCarbon($notification->created_at)->format('Y/m/d H:i') }}</span>
                                </div>
                                <p class="text-sm text-gray-600 mt-1">{{ $notification->message }}</p>
                            </a>
                        @empty
                            <div class="px-4 py-6 text-center text-gray-500 text-sm">هیچ نوتیفیکیشنی وجود ندارد.</div>
                        @endforelse
                        <div class="px-4 py-2 border-t text-center">
                            <a href="{{ route('notifications.index') }}" class="text-sm text-blue-600 hover:underline">مشاهده همه</a>
                        </div>
                    </div>
                </div>

                <!-- منوی کاربر -->
                <div class="relative">
                    <button onclick="toggleDropdown()" class="text-gray-700 hover:text-gray-900 px-3 py-2 rounded-md text-sm font-medium flex items-center">
                        {{ Auth::user()->name }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    <div id="dropdownMenu" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 border">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">پروفایل</a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('backup.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">💾 بکاپ</a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="block w-full text-right px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">خروج</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- دکمه همبرگر برای موبایل -->
            <div class="md:hidden">
                <button onclick="toggleMobileMenu()" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- منوی موبایل -->
    <div id="mobileMenu" class="hidden md:hidden bg-white border-t">
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="{{ route('dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-gray-100' : '' }}">
                داشبورد
            </a>
            
            @if(auth()->user()->role === 'marketer')
                <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('projects.*') ? 'bg-gray-100' : '' }}">
                    لیدهای من
                </a>
                <a href="{{ route('projects.select-type') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('projects.select-type') ? 'bg-gray-100' : '' }}">
                    ثبت لید جدید
                </a>
            @elseif(auth()->user()->role === 'sales_expert')
                <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('projects.index') ? 'bg-gray-100' : '' }}">
                    لیدهای من
                </a>
                <a href="{{ route('projects.assigned-to-me') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('projects.assigned-to-me') ? 'bg-gray-100' : '' }}">
                    لیدهای ارجاع داده شده به من
                </a>
                <a href="{{ route('projects.select-type') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('projects.select-type') ? 'bg-gray-100' : '' }}">
                    ثبت لید جدید
                </a>
                <a href="{{ route('call-logs.select-project') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    ثبت تماس جدید
                </a>
                <a href="{{ route('call-logs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('call-logs.*') ? 'bg-gray-100' : '' }}">
                    تماس‌ها
                </a>
            @elseif(auth()->user()->role === 'admin' || auth()->user()->role === 'sales_manager')
                <a href="{{ route('projects.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('projects.*') ? 'bg-gray-100' : '' }}">
                    لیست لیدها
                </a>
                <a href="{{ route('projects.select-type') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('projects.select-type') ? 'bg-gray-100' : '' }}">
                    ثبت لید جدید
                </a>
                <a href="{{ route('assignments.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('assignments.*') ? 'bg-gray-100' : '' }}">
                    ارجاع‌ها
                </a>
                <a href="{{ route('call-logs.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100 {{ request()->routeIs('call-logs.*') ? 'bg-gray-100' : '' }}">
                    تماس‌ها
                </a>
            @endif
            
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('backup.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">
                    💾 بکاپ
                </a>
            @endif
            
            <a href="{{ route('profile.edit') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">پروفایل</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="block w-full text-right px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-gray-100">خروج</button>
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