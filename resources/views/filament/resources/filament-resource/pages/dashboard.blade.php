<x-filament::page>

    <x-filament::widgets
        :widgets="$this->getHeaderWidgets()"
        class="mb-6"
    />

    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
        @foreach ($this->getFooterWidgets() as $widget)
            <x-filament::widget :widget="$this->makeWidget($widget)" />
        @endforeach
    </div>

</x-filament::page>


{{-- <link rel="icon" type="image/png" href="./images/Logo.png"> --}}
