<footer class="bg-grey-08 px-4 pt-12 pb-5">
  {{-- Logo --}}
  <x-logo />

  {{-- Email subscription --}}
  <div class="flex items-center border border-grey-15 rounded-lg mt-6">
    <span class="pl-5 shrink-0 text-grey-60">
      <x-icons.envelop class="size-5" />
    </span>
    <input
      type="email"
      placeholder="Enter Your Email"
      class="bg-transparent py-4 px-3 text-sm text-white placeholder-grey-40 outline-none flex-1 min-w-0"
    />
    <button class="pr-5 pl-2 shrink-0 text-white">
      <x-icons.envelop-send class="size-5" />
    </button>
  </div>

  {{-- Navigation columns --}}
  <div class="footer-nav columns-2 mt-10">
    @foreach (['footer_home', 'footer_properties', 'footer_contact_us', 'footer_about_us', 'footer_services'] as $location)
      @if (has_nav_menu($location))
        <div>
          <h3 class="text-base font-medium text-grey-60 mb-5">
            {{ wp_get_nav_menu_name($location) }}
          </h3>
          {!! wp_nav_menu([
            'theme_location' => $location,
            'container' => false,
            'items_wrap' => '<ul class="flex flex-col gap-3">%3$s</ul>',
            'echo' => false,
          ]) !!}
        </div>
      @endif
    @endforeach
  </div>

  {{-- Bottom bar --}}
  <div class="border-t border-grey-15 mt-10 pt-5 flex flex-col items-center gap-4">
    {{-- Social icons --}}
    <div class="flex gap-3">
      @foreach ([
        ['component' => 'icons.facebook', 'label' => 'Facebook'],
        ['component' => 'icons.instagram', 'label' => 'LinkedIn'],
        ['component' => 'icons.twitter', 'label' => 'Twitter'],
        ['component' => 'icons.youtube', 'label' => 'YouTube'],
      ] as $social)
        <a
          href="#"
          aria-label="{{ $social['label'] }}"
          class="flex items-center justify-center w-11 h-11 rounded-full border border-grey-15 text-white"
        >
          <x-dynamic-component :component="$social['component']" class="size-5" />
        </a>
      @endforeach
    </div>

    {{-- Copyright --}}
    <p class="text-sm text-white">&copy;2023 Estatein. All Rights Reserved.</p>

    {{-- Terms --}}
    <a href="#" class="text-sm text-white">Terms &amp; Conditions</a>
  </div>
</footer>
