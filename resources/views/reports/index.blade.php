<x-app-layout>
    <x-slot name="header">
        <h1 class="font-bold text-3xl leading-relaxed text-gray-600">Sales Dashboard</h1>
        <p class="text-sm font-semibold text-gray-600">A comprehensive sales analysis </p>
    </x-slot>
    <div class="container mx-auto p-6">



        <div class="flex bg-white shadow-xl rounded-lg overflow-hidden">





            <div class="w-full p-8">


                <h1 class="text-3xl font-extrabold text-gray-900 mb-6 border-b pb-3">
                    {{ $title ?? 'Select a Report' }}
                </h1>


                @switch($currentReportType)
                    @case('monthly')
                        @include('reports.partials.monthly')
                    @break

                    @case('yearly')
                        @include('reports.partials.yearly')
                    @break

                    @case('category')
                        @include('reports.partials.category')
                    @break

                    @case('category_yearly')
                        @include('reports.partials.category_yearly')
                    @break

                    @default
                        <div class="mt-8 text-center p-10 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0h2a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2z" />
                            </svg>
                            <h3 class="mt-2 text-xl font-medium text-gray-900">Reports Dashboard</h3>
                            <p class="mt-1 text-gray-500">Please select a specific sales report type from the left sidebar to
                                view the data.</p>
                        </div>
                @endswitch

            </div>
        </div>
    </div>


</x-app-layout>
