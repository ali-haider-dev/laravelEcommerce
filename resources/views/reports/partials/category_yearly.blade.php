<div class="bg-white shadow-2xl rounded-xl p-8">

    @if (!empty($summaryMetrics))
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mb-10">
            
            {{-- Total Categories (Not calculated in controller, so we'll estimate from data) --}}
            @php
                $uniqueCategories = count(array_unique(array_column($reportData, 'category_name')));
            @endphp
            <div class="p-5 bg-purple-50 border-l-4 border-purple-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-purple-700 uppercase">Total Categories Tracked</p>
                        <p class="text-2xl font-extrabold text-purple-900 mt-1">{{ number_format($uniqueCategories) }}</p>
                    </div>
                    {{-- Category Icon --}}
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2m-6 6h6"></path></svg>
                </div>
                <p class="text-xs text-purple-600 mt-2">Period: {{ $summaryMetrics['startYear'] }} - {{ $summaryMetrics['endYear'] }}</p>
            </div>
            
            {{-- Grand Total Revenue --}}
            <div class="p-5 bg-green-50 border-l-4 border-green-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-700 uppercase">Grand Total Revenue</p>
                        <p class="text-2xl font-extrabold text-green-900 mt-1">${{ number_format($summaryMetrics['grandTotalRevenue'], 2) }}</p>
                    </div>
                    {{-- Money Icon --}}
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2-3-.895-3-2 1.343-2 3-2z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-3.693-.722M4.995 7.15c-.244.382-.41.79-.56 1.258M3 12c0-4.418 4.03-8 9-8s9 3.582 9 8"></path></svg>
                </div>
                <p class="text-xs text-green-600 mt-2">Revenue from all categories.</p>
            </div>
            
            {{-- Total Items Sold --}}
            <div class="p-5 bg-blue-50 border-l-4 border-blue-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-700 uppercase">Total Items Sold</p>
                        <p class="text-2xl font-extrabold text-blue-900 mt-1">{{ number_format($summaryMetrics['grandTotalItemsSold']) }}</p>
                    </div>
                    {{-- Item Box Icon --}}
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10m-8 0h16"></path></svg>
                </div>
                <p class="text-xs text-blue-600 mt-2">Total quantity sold across all categories.</p>
            </div>
        </div>
    @endif
    {{-- --- END SUMMARY METRICS --- --}}

    @php
        // 1. Pivot the data: Transform the flat array into a structure keyed by Category and then Year
        $pivotData = [];
        $allYears = range($summaryMetrics['startYear'], $summaryMetrics['endYear']);
        
        foreach ($reportData as $row) {
            $category = $row['category_name'];
            $year = $row['report_year'];
            
            // Store Revenue and Market Share
            $pivotData[$category][$year] = [
                'revenue' => (float) $row['total_revenue'],
                'market_share' => (float) $row['market_share'],
            ];
        }

        // 2. Sort categories alphabetically (or by grand total revenue if you prefer)
        ksort($pivotData);
        
        // 3. Define the column headers (Year, then a pair for Revenue & Share)
        $columnHeaders = ['Category'];
        foreach ($allYears as $year) {
            $columnHeaders[] = "{$year} Revenue";
            $columnHeaders[] = "{$year} Share";
        }

    @endphp

    <div class="overflow-x-auto shadow-xl rounded-lg border border-gray-100">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-gray-100">
                <tr>
                    {{-- Category Header --}}
                    <th rowspan="2" class="sticky left-0 bg-gray-200 px-3 py-3 text-left text-xs font-medium text-gray-700 uppercase tracking-wider border-r border-gray-300">Category</th>
                    
                    {{-- Year Headers (Spanning two columns) --}}
                    @foreach ($allYears as $year)
                        <th colspan="2" class="px-3 py-2 text-center text-xs font-bold text-white uppercase tracking-wider {{ ($year == date('Y')) ? 'bg-indigo-700' : 'bg-gray-700' }} border-l border-r border-gray-500">
                            {{ $year }}
                        </th>
                    @endforeach
                </tr>
                <tr>
                    {{-- Sub-Headers for Revenue and Share --}}
                    @foreach ($allYears as $year)
                        <th class="px-2 py-2 text-right text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50">Revenue</th>
                        <th class="px-2 py-2 text-right text-xs font-medium text-gray-700 uppercase tracking-wider bg-gray-50 border-r border-gray-200">Share (%)</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                
                @foreach ($pivotData as $categoryName => $yearData)
                    <tr class="hover:bg-gray-50">
                        {{-- Category Name Cell --}}
                        <td class="sticky left-0 bg-white px-3 py-3 whitespace-nowrap font-bold text-gray-900 border-r border-gray-200">{{ $categoryName }}</td>
                        
                        @foreach ($allYears as $year)
                            @php
                                $data = $yearData[$year] ?? ['revenue' => 0.00, 'market_share' => 0.00];
                                $revenue = $data['revenue'];
                                $marketShare = $data['market_share'];
                            @endphp
                            
                            {{-- Revenue Cell --}}
                            <td class="px-2 py-3 whitespace-nowrap text-right font-medium text-gray-800">
                                ${{ number_format($revenue, 2) }}
                            </td>
                            
                            {{-- Market Share Cell --}}
                            <td class="px-2 py-3 whitespace-nowrap text-right {{ ($marketShare > 0) ? 'text-green-600' : 'text-gray-500' }} border-r border-gray-200">
                                {{ number_format($marketShare, 2) }}%
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                
            </tbody>
            
            {{-- FOOTER: Annual Totals by Year --}}
            <tfoot>
                <tr class="bg-gray-800 text-white font-bold">
                    <th class="sticky left-0 bg-gray-900 px-3 py-3 text-left uppercase border-r border-gray-700">Annual Revenue Total</th>
                    @foreach ($allYears as $year)
                        @php
                            $annualTotal = $yearlyTotals[$year] ?? 0.00; // Use the annual totals from the controller
                        @endphp
                        <th colspan="2" class="px-3 py-3 text-right bg-indigo-700">
                            ${{ number_format($annualTotal, 2) }}
                        </th>
                    @endforeach
                </tr>
            </tfoot>
        </table>
    </div>
</div>