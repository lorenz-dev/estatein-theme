<header class="bg-grey-08 px-4 py-5 flex items-center justify-between border-b border-grey-15">
  <a href="{{ home_url('/') }}">
    <x-logo />
  </a>

  <button type="button" aria-label="Open menu" data-drawer-toggle class="text-white">
    <x-icons.hamburger class="size-7" />
  </button>
</header>

{{-- Mobile drawer --}}
@if (has_nav_menu('home_menu'))
  <div data-drawer class="fixed inset-0 z-50 hidden">
    {{-- Overlay --}}
    <div data-drawer-overlay class="absolute inset-0 bg-black/60"></div>

    {{-- Drawer panel --}}
    <nav
      data-drawer-panel
      class="absolute top-0 right-0 bottom-0 w-72 bg-grey-10 border-l border-grey-15 p-6 flex flex-col gap-6 translate-x-full transition-transform duration-300"
      aria-label="{{ wp_get_nav_menu_name('home_menu') }}"
    >
      <div class="flex items-center justify-end">
        <button type="button" aria-label="Close menu" data-drawer-close class="text-grey-60">
          <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 6L6 18M6 6l12 12" />
          </svg>
        </button>
      </div>

      {!! wp_nav_menu([
        'theme_location' => 'home_menu',
        'container' => false,
        'items_wrap' => '<ul class="flex flex-col gap-4">%3$s</ul>',
        'echo' => false,
      ]) !!}
    </nav>
  </div>
@endif
