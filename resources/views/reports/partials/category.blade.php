<div class="bg-white shadow-xl rounded-lg p-6">

    {{-- Year Selection Form (Uses GET for easier report sharing/bookmarking) --}}
    <form method="GET" action="{{ route('reports.show') }}" class="mb-8 p-6 bg-gray-50 rounded-lg border border-gray-200">
        {{-- Preserve the report type --}}
        <input type="hidden" name="type" value="category"> 
        
        <div class="flex flex-col md:flex-row items-end md:space-x-4 space-y-4 md:space-y-0">
            
            <div class="flex-grow w-full md:w-auto">
                <label for="report_year" class="block text-sm font-semibold text-gray-700 mb-1">Select Year:</label>
                <select class="form-select block w-full rounded-md border-gray-300 shadow-sm p-2 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                    id="report_year" name="year" required>
                    
                    {{-- The $availableYears and $selectedYear variables are passed from the controller --}}
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}" {{ ((int)$selectedYear == $year) ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-48">
                <button type="submit" class="inline-flex justify-center w-full py-2 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Generate Report
                </button>
            </div>
        </div>
    </form>

    {{-- --- SUMMARY METRICS (CTAs) --- --}}
    @if (!empty($summaryMetrics))
        <h2 class="text-xl font-bold text-gray-800 mb-4 border-b pb-2">Category Performance for {{ $summaryMetrics['year'] }}</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
            
            {{-- Total Revenue --}}
            <div class="p-4 bg-green-50 border-l-4 border-green-600 rounded-lg shadow-sm">
                <p class="text-sm font-semibold text-green-700 uppercase">Total Revenue</p>
                <p class="text-xl font-extrabold text-green-900">${{ number_format($summaryMetrics['grandTotalRevenue'], 2) }}</p>
            </div>
            
            {{-- Top Category --}}
            <div class="p-4 bg-yellow-50 border-l-4 border-yellow-600 rounded-lg shadow-sm">
                <p class="text-sm font-semibold text-yellow-700 uppercase">Top Category</p>
                <p class="text-xl font-extrabold text-yellow-900">{{ $summaryMetrics['topCategory'] }}</p>
            </div>

            {{-- Total Categories --}}
            <div class="p-4 bg-blue-50 border-l-4 border-blue-600 rounded-lg shadow-sm">
                <p class="text-sm font-semibold text-blue-700 uppercase">Total Categories</p>
                <p class="text-xl font-extrabold text-blue-900">{{ $summaryMetrics['totalCategories'] }}</p>
            </div>

            {{-- Total Items Sold --}}
            <div class="p-4 bg-indigo-50 border-l-4 border-indigo-600 rounded-lg shadow-sm">
                <p class="text-sm font-semibold text-indigo-700 uppercase">Total Items Sold</p>
                <p class="text-xl font-extrabold text-indigo-900">{{ number_format($summaryMetrics['grandTotalItemsSold']) }}</p>
            </div>
        </div>
    @endif
    {{-- --- END SUMMARY METRICS --- --}}


    {{-- Report Display Area --}}
    @if (!empty($reportData))
        <div class="overflow-x-auto relative shadow-md sm:rounded-lg">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-100 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-left">Category</th>
                        <th scope="col" class="py-3 px-6 text-right">Total Items Sold</th>
                        <th scope="col" class="py-3 px-6 text-right">Market Share</th> {{-- New Column Header --}}
                        <th scope="col" class="py-3 px-6 text-right">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Use controller values for footers, but keep temporary variables for calculation fallbacks
                        $grandTotalRevenue = $summaryMetrics['grandTotalRevenue'] ?? 0;
                        $grandTotalItemsSold = $summaryMetrics['grandTotalItemsSold'] ?? 0;
                    @endphp
                    @foreach ($reportData as $row)
                        @php
                            $categoryRevenue = (float)($row['total_revenue'] ?? 0);
                            $itemsSold = (int)($row['total_items_sold'] ?? 0);
                            $marketShare = (float)($row['market_share_percent'] ?? 0);
                            $isTopCategory = ($row['category_name'] == $summaryMetrics['topCategory']) ? 'bg-yellow-50' : 'bg-white';
                        @endphp
                        <tr class="{{ $isTopCategory }} border-b hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-600">
                            <td class="py-4 px-6 font-bold text-gray-900">{{($row['category_name']) }}</td>
                            <td class="py-4 px-6 text-right">{{ number_format($itemsSold) }}</td>
                            
                            {{-- Market Share Column --}}
                            <td class="py-4 px-6">
                                <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                    <div class="h-2.5 rounded-full" 
                                         style="width: {{ round($marketShare) }}%; background-color: #3b82f6;">{{-- Tailwind blue-500 --}}
                                    </div>
                                </div>
                                <span class="text-xs text-gray-500 mt-1 block text-right">{{ number_format($marketShare, 1) }}%</span>
                            </td>

                            <td class="py-4 px-6 text-right font-bold text-green-600 dark:text-green-400">${{ number_format($categoryRevenue, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold text-white bg-indigo-600 dark:bg-indigo-700">
                        <td class="py-3 px-6 text-left uppercase">GRAND TOTAL</td>
                        <td class="py-3 px-6 text-right">{{ number_format($grandTotalItemsSold) }}</td>
                        <td class="py-3 px-6 text-right">100.0%</td>
                        <td class="py-3 px-6 text-right">${{ number_format($grandTotalRevenue, 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg dark:bg-yellow-200 dark:text-yellow-800" role="alert">
            No paid orders found for any category in the selected year ({{ htmlspecialchars($selectedYear) }}).
        </div>
    @endif

</div>