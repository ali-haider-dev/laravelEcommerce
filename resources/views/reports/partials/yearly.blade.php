<div class="bg-white shadow-2xl rounded-xl p-8">

    {{-- --- SUMMARY METRICS (CTAs) --- --}}
    @if (!empty($summaryMetrics))
        
        <h2 class="text-2xl font-extrabold text-gray-800 mb-6 border-b pb-2">
            Multi-Year Performance Insights ({{ $startYear }} - {{ $endYear }})
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            
            {{-- Best Year --}}
            <div class="p-5 bg-yellow-50 border-l-4 border-yellow-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-yellow-700 uppercase">Best Revenue Year</p>
                        <p class="text-2xl font-extrabold text-yellow-900 mt-1">{{ $summaryMetrics['bestYear'] }}</p>
                    </div>
                    {{-- Bar Chart/Trophy Icon --}}
                    <svg class="w-8 h-8 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0h2a2 2 0 002-2v-6a2 2 0 00-2-2h-2a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                </div>
                <p class="text-xs text-yellow-600 mt-2">Revenue: ${{ number_format($summaryMetrics['bestYearRevenue'], 2) }}</p>
            </div>
            
            {{-- Total Revenue Generated --}}
            <div class="p-5 bg-green-50 border-l-4 border-green-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-700 uppercase">Total Revenue Generated</p>
                        <p class="text-2xl font-extrabold text-green-900 mt-1">${{ number_format($summaryMetrics['grandTotalRevenue'], 2) }}</p>
                    </div>
                    {{-- Money Icon --}}
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-3.693-.722M4.995 7.15c-.244.382-.41.79-.56 1.258M3 12c0-4.418 4.03-8 9-8s9 3.582 9 8"></path></svg>
                </div>
            </div>
            
            {{-- Total Orders --}}
            <div class="p-5 bg-blue-50 border-l-4 border-blue-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-700 uppercase">Total Orders In Period</p>
                        <p class="text-2xl font-extrabold text-blue-900 mt-1">{{ number_format($summaryMetrics['totalOrdersInPeriod']) }}</p>
                    </div>
                    {{-- Shopping Cart Icon --}}
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
        </div>
    @endif
    {{-- --- END SUMMARY METRICS --- --}}

    <div class="overflow-x-auto shadow-xl rounded-lg border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-3 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider">Year</th>
                    @php
                        // Initialize column totals for months (Still needed for the footer calculation)
                        $total_cols = array_fill(1, 12, 0); 
                    @endphp
                    @foreach ($months as $monthName)
                        <th class="px-3 py-3 text-right text-xs font-medium text-gray-700 uppercase tracking-wider">{{ $monthName }}</th>
                    @endforeach
                    <th class="px-3 py-3 text-right text-xs font-bold text-white uppercase tracking-wider bg-indigo-600">Annual Total</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @php
                    // Use the grand total from the controller, but recalculate the column totals here
                    $grand_total_revenue = $summaryMetrics['grandTotalRevenue'] ?? 0;
                    ksort($revenueData); // Ensure years are sorted ascending
                @endphp
                
                @foreach ($revenueData as $year => $monthly_revenues)
                    @php
                        $annual_total = 0;
                        // Highlight best year row
                        $isBestYear = ($year == $summaryMetrics['bestYear']) ? 'bg-yellow-50 font-semibold' : '';
                    @endphp
                    <tr class="{{ $isBestYear }} hover:bg-gray-50">
                        <td class="px-3 py-3 whitespace-nowrap font-bold text-gray-900">{{ $year }}</td>
                        
                        @for ($i = 1; $i <= 12; $i++)
                            @php
                                $revenue = $monthly_revenues[$i] ?? 0.00;
                                $annual_total += $revenue;
                                $total_cols[$i] += $revenue;
                            @endphp
                            <td class="px-3 py-3 whitespace-nowrap text-right">${{ number_format($revenue, 2) }}</td>
                        @endfor
                        
                        {{-- Annual Total Cell --}}
                        <td class="px-3 py-3 whitespace-nowrap text-right font-bold text-indigo-700 bg-indigo-100">
                            ${{ number_format($annual_total, 2) }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
            
            <tfoot>
                <tr class="bg-gray-800 text-white font-bold">
                    <th class="px-3 py-3 text-left uppercase">Monthly Total</th>
                    @for ($i = 1; $i <= 12; $i++)
                        <th class="px-3 py-3 text-right">${{ number_format($total_cols[$i], 2) }}</th>
                    @endfor
                    {{-- Grand Total Cell (Using the controller's calculated value) --}}
                    <th class="px-3 py-3 text-right bg-green-700 text-lg">
                        ${{ number_format($grand_total_revenue, 2) }}
                    </th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>