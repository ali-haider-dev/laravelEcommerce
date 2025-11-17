<div class="bg-white shadow-2xl rounded-xl p-8">

    {{-- REPORT SUMMARY METRICS (CTAs) - Moved to the Top --}}
    @if (!empty($summaryMetrics))
        
        <h2 class="text-2xl font-extrabold text-gray-800 mb-6 border-b pb-2">Monthly Performance Insights </h2>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            
            {{-- Best Month --}}
            <div class="p-5 bg-indigo-50 border-l-4 border-indigo-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-indigo-700 uppercase">Best Revenue Month</p>
                        <p class="text-2xl font-extrabold text-indigo-900 mt-1">{{ $summaryMetrics['bestMonth']['month'] }}</p>
                    </div>
                    {{-- Trophy Icon --}}
                    <svg class="w-8 h-8 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15.3l3.3-3.3M12 12v6m6 0a3 3 0 00-6 0m6 0h-6M12 4a3 3 0 00-3 3v2a3 3 0 006 0V7a3 3 0 00-3-3z"></path></svg>
                </div>
                <p class="text-xs text-indigo-500 mt-2">Revenue: ${{ number_format($summaryMetrics['bestMonth']['revenue'], 2) }}</p>
            </div>
            
            {{-- Total Revenue --}}
            <div class="p-5 bg-green-50 border-l-4 border-green-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-green-700 uppercase">Total Revenue (Year)</p>
                        <p class="text-2xl font-extrabold text-green-900 mt-1">${{ number_format($summaryMetrics['totalRevenue'], 2) }}</p>
                    </div>
                    {{-- Wallet Icon --}}
                    <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m0 0l2 2m-2-2v2a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-2m-4-4a2 2 0 100 4 2 2 0 000-4z"></path></svg>
                </div>
            </div>
            
            {{-- Total Orders --}}
            <div class="p-5 bg-blue-50 border-l-4 border-blue-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-blue-700 uppercase">Total Orders (Year)</p>
                        <p class="text-2xl font-extrabold text-blue-900 mt-1">{{ number_format($summaryMetrics['totalOrders']) }}</p>
                    </div>
                    {{-- Shopping Cart Icon --}}
                    <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
            </div>
            
            {{-- Avg Order Amount --}}
            <div class="p-5 bg-purple-50 border-l-4 border-purple-600 rounded-lg shadow-md hover:shadow-lg transition duration-300 transform hover:scale-[1.02]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-semibold text-purple-700 uppercase">Avg. Order Amount</p>
                        <p class="text-2xl font-extrabold text-purple-900 mt-1">${{ number_format($summaryMetrics['averageOrderAmount'], 2) }}</p>
                    </div>
                    {{-- Trending Up Icon --}}
                    <svg class="w-8 h-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </div>
            </div>
        </div>
    @endif
    
    {{-- Year Selection Form (Handles filtering via GET query parameters) --}}
    <form method="GET" action="{{ route('reports.show') }}" class="mb-10 p-6 bg-gray-50 rounded-lg border border-gray-200">
        {{-- Preserve the report type --}}
        <input type="hidden" name="type" value="monthly"> 
        
        <div class="flex flex-col md:flex-row items-end md:space-x-4 space-y-4 md:space-y-0">
            
            <div class="flex-grow w-full md:w-auto">
                <label for="report_year" class="block text-sm font-semibold text-gray-700 mb-1">Filter by Year:</label>
                <select class="form-select block w-full rounded-md border-gray-300 shadow-sm p-2 focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                    id="report_year" name="year" required>
                    
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}" {{ ((int)$selectedYear == $year) ? 'selected' : '' }}>
                            {{ $year }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="w-full md:w-48">
                <button type="submit" class="inline-flex justify-center w-full py-2 px-4 border border-transparent shadow-sm text-sm font-bold rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Apply Filter
                </button>
            </div>
        </div>
    </form>

    {{-- MONTHLY REPORT TABLE --}}
    @if (!empty($reportData))
        <div class="overflow-x-auto relative shadow-xl rounded-lg border border-gray-100">
            <table class="w-full text-sm text-left text-gray-600">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="py-3 px-6 text-center">Month</th>
                        <th scope="col" class="py-3 px-6 text-right">Total Orders</th>
                        <th scope="col" class="py-3 px-6 text-right">Total Revenue</th>
                        <th scope="col" class="py-3 px-6 text-right">Avg. Order Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        // Using totals passed from the controller
                        $grandTotalRevenue = $summaryMetrics['totalRevenue'];
                        $grandTotalOrders = $summaryMetrics['totalOrders'];
                    @endphp
                    @foreach ($reportData as $row)
                        @php
                            $monthlyRevenue = (float)($row['total_revenue'] ?? 0);
                            $monthlyOrders = (int)($row['total_orders'] ?? 0);
                            // Calculate monthly average
                            $monthlyAvg = ($monthlyOrders > 0) ? ($monthlyRevenue / $monthlyOrders) : 0.00;
                            // Highlight the best month
                            $isBestMonth = ($row['order_month'] == $summaryMetrics['bestMonth']['month']) ? 'bg-yellow-50 font-bold' : '';
                        @endphp
                        <tr class="bg-white border-b hover:bg-gray-50 {{ $isBestMonth }}">
                            <td class="py-4 px-6 text-center text-gray-900">{{ htmlspecialchars($row['order_month']) }}</td>
                            <td class="py-4 px-6 text-right">{{ number_format($monthlyOrders) }}</td>
                            <td class="py-4 px-6 text-right font-bold text-green-700">${{ number_format($monthlyRevenue, 2) }}</td>
                            {{-- Monthly Average --}}
                            <td class="py-4 px-6 text-right text-gray-800">${{ number_format($monthlyAvg, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-bold text-white bg-gray-800">
                        <td class="py-3 px-6 text-center uppercase">GRAND TOTAL</td>
                        <td class="py-3 px-6 text-right">{{ number_format($grandTotalOrders) }}</td>
                        <td class="py-3 px-6 text-right">${{ number_format($grandTotalRevenue, 2) }}</td>
                        {{-- Yearly Average --}}
                        <td class="py-3 px-6 text-right">${{ number_format($summaryMetrics['averageOrderAmount'], 2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    @else
        <div class="p-4 mb-4 text-sm text-yellow-700 bg-yellow-100 rounded-lg" role="alert">
            <span class="font-medium">Heads up!</span> No paid orders found in the selected year ({{ htmlspecialchars($selectedYear) }}).
        </div>
    @endif


</div>