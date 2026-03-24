@extends('layouts.app')

@section('content')
  {{-- Hero --}}
  <section class="border border-grey-15 px-4 py-12.5 flex flex-col gap-2.5" style="background: linear-gradient(to right, var(--color-grey-15), var(--color-grey-08))">
    <h1 class="text-[1.75rem] font-semibold text-white leading-normal">
      Find Your Dream Property
    </h1>
    <p class="text-sm font-medium text-grey-60 leading-normal">
      Welcome to Estatein, where property awaits in every corner of our beautiful world. Explore our curated selection of properties, each offering a unique story and a chance to redefine your life. With categories to suit every dreamer, your journey
    </p>
  </section>

  {{-- Search & Filters --}}
  <section class="bg-grey-08 px-4 pt-10 pb-12 flex flex-col gap-5">
    {{-- Search bar --}}
    <div class="bg-grey-08 border border-grey-15 rounded-xl flex items-center gap-5 pl-4.5 pt-2.5 pr-2.5 pb-2.5">
      <input
        type="text"
        placeholder="Search For A Property"
        class="bg-transparent text-base font-medium text-white placeholder-grey-40 leading-normal outline-none flex-1 min-w-0"
      />
      <button class="bg-purple-60 rounded-xl w-15 h-12 shrink-0 flex items-center justify-center text-white">
        <x-icons.search class="size-5" />
      </button>
    </div>

    {{-- Filters --}}
    <div class="bg-grey-10 border border-grey-15 rounded-xl p-5 flex flex-col gap-5">
      @foreach ([
        ['label' => 'Location', 'icon' => 'map-pin'],
        ['label' => 'Property Type', 'icon' => 'home-modern'],
        ['label' => 'Pricing Range', 'icon' => 'banknotes-solid'],
        ['label' => 'Property Size', 'icon' => 'box'],
        ['label' => 'Build Year', 'icon' => 'calendar-solid'],
      ] as $filter)
        <button class="flex items-center gap-5 w-full border border-grey-15 rounded-lg px-3.5 py-3">
          <span class="text-grey-60 shrink-0">
            <x-dynamic-component :component="'icons.' . $filter['icon']" class="size-5" />
          </span>
          <span class="text-sm text-grey-60 flex-1 text-left">{{ $filter['label'] }}</span>
          <x-icons.chevron-drown class="size-5 text-grey-60 shrink-0" />
        </button>
      @endforeach
    </div>
  </section>

  <x-section-container
    title="Discover a World of Possibilities"
    description="Our portfolio of properties is as diverse as your dreams. Explore the following categories to find the perfect property that resonates with your vision of home"
  >
    {{-- Property cards will go here --}}

    {{-- Pagination --}}
    @if (isset($pagination) && $pagination)
        <div class="flex items-center justify-between mt-8">
        <button class="w-11 h-11 rounded-full border border-grey-15 flex items-center justify-center text-white">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        </button>

        <span class="text-sm text-grey-60">
            <span class="text-white font-medium">01</span> of 10
        </span>

        <button class="w-11 h-11 rounded-full border border-grey-15 flex items-center justify-center text-white">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </button>
        </div>
    @endif
  </x-section-container>
@endsection
