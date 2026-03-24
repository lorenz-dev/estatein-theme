@props(['title', 'description' => null])

<section class="bg-grey-08 px-4 py-10">
  <x-stars class="-left-0.5" />

  {{-- Heading --}}
  <h2 class="text-[1.75rem] font-semibold text-white leading-normal">
    {{ $title }}
  </h2>

  {{-- Description --}}
  @if ($description)
    <p class="mt-2.5 text-sm font-medium text-grey-60 leading-normal">
      {{ $description }}
    </p>
  @endif

  {{-- Content slot --}}
  <div class="mt-10">
    {{ $slot }}
  </div>
</section>
