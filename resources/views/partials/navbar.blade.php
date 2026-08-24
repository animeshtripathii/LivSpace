<header class="site-header" id="site-header">
    <div class="container nav-bar">
        <a class="logo" href="{{ route('home') }}">
            <img class="logo-mark" src="{{ asset('logoo.svg') }}" alt="{{ __('app.footer.brand_title') }}">
            <span class="logo-text">{{ __('app.footer.brand_title') }}</span>
        </a>

        <div class="nav-menu" id="nav-menu">
            <nav class="nav-links">
                <a href="{{ route('home') }}">{{ __('app.nav.home') }}</a>
                <a href="{{ route('projects.index') }}">{{ __('app.nav.projects') }}</a>
                <a href="{{ route('contact') }}">{{ __('app.nav.contact') }}</a>
                @auth
                    <a href="{{ auth()->user()->isDesigner() ? route('dashboard.index') : route('client.dashboard') }}">
                        {{ __('app.nav.dashboard') }}
                    </a>
                @else
                    <a href="{{ route('auth.login') }}">{{ __('app.nav.dashboard') }}</a>
                @endauth
            </nav>
            <div class="nav-actions">
                @php
                    $locale = app()->getLocale();
                    $localeLabel = __('app.languages.' . $locale);
                @endphp
                @guest
                    <a class="btn btn-ghost" href="{{ route('auth.login') }}">{{ __('app.nav.login') }}</a>
                    <a class="btn btn-primary" href="{{ route('auth.register') }}">{{ __('app.nav.register') }}</a>
                @else
                    <span class="chip">{{ auth()->user()->name }}</span>
                    <form method="post" action="{{ route('auth.logout') }}">
                        @csrf
                        <button class="btn btn-ghost" type="submit">{{ __('app.nav.logout') }}</button>
                    </form>
                @endguest
                <details class="nav-dropdown">
                    <summary class="btn btn-ghost nav-dropdown-toggle" title="{{ __('app.nav.language') }}" aria-label="{{ __('app.nav.language') }}">
                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="2" y1="12" x2="22" y2="12"></line>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                        </svg>
                        <span class="nav-dropdown-current">{{ $localeLabel }}</span>
                        <span class="nav-dropdown-caret" aria-hidden="true">
                            <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M5 7l5 6 5-6"></path>
                            </svg>
                        </span>
                    </summary>
                    <div class="nav-dropdown-menu" role="menu">
                        <a class="nav-dropdown-item @if ($locale === 'en') active @endif" role="menuitem" href="{{ route('locale.switch', 'en') }}">{{ __('app.languages.en') }}</a>
                        <a class="nav-dropdown-item @if ($locale === 'hi') active @endif" role="menuitem" href="{{ route('locale.switch', 'hi') }}">{{ __('app.languages.hi') }}</a>
                        <a class="nav-dropdown-item @if ($locale === 'ta') active @endif" role="menuitem" href="{{ route('locale.switch', 'ta') }}">{{ __('app.languages.ta') }}</a>
                    </div>
                </details>
            </div>
        </div>

        <div class="nav-controls">
            <button
                class="theme-toggle"
                id="theme-toggle"
                type="button"
                data-light="{{ __('app.theme.light') }}"
                data-dark="{{ __('app.theme.dark') }}"
                aria-pressed="false"
                aria-label="{{ __('app.theme.toggle') }}"
                title="{{ __('app.theme.toggle') }}"
            >
                <svg class="sun-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="4"></circle>
                    <path d="M12 2v2"></path>
                    <path d="M12 20v2"></path>
                    <path d="M4.93 4.93l1.41 1.41"></path>
                    <path d="M17.66 17.66l1.41 1.41"></path>
                    <path d="M2 12h2"></path>
                    <path d="M20 12h2"></path>
                    <path d="M4.93 19.07l1.41-1.41"></path>
                    <path d="M17.66 6.34l1.41-1.41"></path>
                </svg>
                <svg class="moon-icon" viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"></path>
                </svg>
            </button>
            <button class="nav-toggle" id="nav-toggle" type="button" aria-label="Toggle navigation" aria-expanded="false">
                <svg class="hamburger-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="3" y1="12" x2="21" y2="12"></line>
                    <line x1="3" y1="6" x2="21" y2="6"></line>
                    <line x1="3" y1="18" x2="21" y2="18"></line>
                </svg>
                <svg class="close-icon" viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="6" x2="6" y2="18"></line>
                    <line x1="6" y1="6" x2="18" y2="18"></line>
                </svg>
            </button>
        </div>
    </div>
</header>
