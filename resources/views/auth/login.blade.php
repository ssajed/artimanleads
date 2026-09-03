<x-guest-layout>

    <form method="POST" action="{{ route('login') }}" id="loginForm">
        @csrf

        @if ($errors->any())
            <div class="error-box">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="field">
            <label for="email">ایمیل</label>

            <div class="input-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-4.5 7.794"/>
                </svg>

                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    class="login-input"
                    placeholder="ایمیل خود را وارد کنید"
                    required
                    autofocus
                    autocomplete="username"
                >
            </div>
        </div>

        <div class="field">
            <label for="password">رمز عبور</label>

            <div class="input-wrap">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v2h8z"/>
                </svg>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="login-input"
                    placeholder="رمز عبور خود را وارد کنید"
                    required
                    autocomplete="current-password"
                >

                <button
                    type="button"
                    class="password-toggle"
                    onclick="togglePassword()"
                    aria-label="نمایش رمز عبور"
                >
                    <svg id="eyeIcon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="form-options">

            <label class="remember">
                <input
                    id="remember"
                    type="checkbox"
                    name="remember"
                    value="1"
                >
                <span>مرا به خاطر بسپار</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="forgot-link">
                    فراموشی رمز عبور؟
                </a>
            @endif

        </div>

        <button type="submit" class="login-button" id="loginButton">
            ورود به پنل
        </button>
    </form>

    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');

            if (password.type === 'password') {
                password.type = 'text';

                icon.innerHTML = `
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a9.97 9.97 0 012.216-3.592M6.223 6.223A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.97 9.97 0 01-3.027 4.25M6.223 6.223L3 3m3.223 3.223l12.554 12.554M9.88 9.88a3 3 0 104.24 4.24"/>
                `;
            } else {
                password.type = 'password';

                icon.innerHTML = `
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                `;
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function () {
            const button = document.getElementById('loginButton');

            button.disabled = true;
            button.style.opacity = '0.75';
            button.innerHTML = 'در حال ورود...';
        });
    </script>

</x-guest-layout>
