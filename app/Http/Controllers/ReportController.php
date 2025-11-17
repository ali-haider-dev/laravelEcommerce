<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Order; // IMPORTANT: Ensure this is the correct namespace for your tbl_orders model
use Illuminate\Support\Facades\DB; // Needed for raw expressions like YEAR() and MIN(created_at)

class ReportController extends Controller
{
    /**
     * Finds the available years by querying the minimum creation year from the orders table.
     */
    private function getAvailableYears(): array
    {
        $currentYear = (int) date('Y');

        // Find the earliest year in the orders table
        $minYear = Order::min(DB::raw('YEAR(created_at)'));

        // If no records, default to the current year
        $minYear = $minYear ? (int) $minYear : $currentYear;

        $availableYears = range($minYear, $currentYear);
        krsort($availableYears); // Sort descending (most recent first)
        return $availableYears;
    }

    /**
     * Fetches the monthly revenue, order count, and month name for the given year using Eloquent.
     */
    private function getMonthlySalesReportData(int $year): array
    {
        $reportData = Order::selectRaw('DATE_FORMAT(created_at, "%M") AS order_month')
            ->selectRaw('SUM(total_amount) AS total_revenue')
            ->selectRaw('COUNT(id) AS total_orders')
            // WHERE conditions: payment_status = 'paid' AND year = $year
            ->where('payment_status', 'paid')
            ->whereYear('created_at', $year)
            // GROUP BY month name
            ->groupBy('order_month')
            // ORDER BY chronological month order (using the earliest timestamp in that group)
            ->orderByRaw('MIN(created_at) ASC')
            ->get()
            ->toArray();

        return $reportData;
    }

    /**
     * Fetches monthly revenue data structured by year for multi-year report (Last 5 years).
     */
    private function getYearlySalesReportData(): array
    {
        $endYear = (int) date('Y');
        $startYear = $endYear - 4; // Last 5 years including current year

        $startDate = Carbon::createFromDate($startYear, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate($endYear, 12, 31)->endOfDay();

        // 1. Eloquent Query to fetch raw data
        $resultData = Order::selectRaw('YEAR(created_at) AS report_year')
            ->selectRaw('MONTH(created_at) AS report_month')
            ->selectRaw('SUM(total_amount) AS monthly_revenue')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->where('total_amount', '>', 0)
            ->groupBy('report_year', 'report_month')
            ->orderBy('report_year', 'asc')
            ->orderBy('report_month', 'asc')
            ->get()
            ->toArray();

        // 2. Define the month headers
        $months = [
            1 => 'Jan',
            2 => 'Feb',
            3 => 'Mar',
            4 => 'Apr',
            5 => 'May',
            6 => 'Jun',
            7 => 'Jul',
            8 => 'Aug',
            9 => 'Sep',
            10 => 'Oct',
            11 => 'Nov',
            12 => 'Dec'
        ];
        $allYears = range($startYear, $endYear);
        $revenueData = [];

        // 3. Process results into the structured array
        foreach ($resultData as $row) {
            $year = (int) $row['report_year'];
            $month = (int) $row['report_month'];
            $revenue = (float) $row['monthly_revenue'];

            // Initialize the year row with 0.00 for all months if not already done
            if (!isset($revenueData[$year])) {
                $revenueData[$year] = array_fill_keys(array_keys($months), 0.00);
            }

            $revenueData[$year][$month] = $revenue;
        }

        // 4. Ensure all 5 years are in the data structure, even if empty (for complete table rows)
        foreach ($allYears as $year) {
            if (!isset($revenueData[$year])) {
                $revenueData[$year] = array_fill_keys(array_keys($months), 0.00);
            }
        }


        return [
            'revenueData' => $revenueData,
            'months' => $months,
            'startYear' => $startYear,
            'endYear' => $endYear,
        ];
    }

    /**
     * Helper to get total orders for the yearly period (used for CTAs).
     */
    protected function getYearlyOrderCountData(int $startYear, int $endYear): int
    {
        $startDate = Carbon::createFromDate($startYear, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate($endYear, 12, 31)->endOfDay();

        // Actual database query to count paid orders in the period
        return Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->count();
    }


    private function getCategoryReportData(int $year): array
    {
        // Define the date range
        $startDate = Carbon::createFromDate($year, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate($year, 12, 31)->endOfDay();


        $reportData = DB::table('tbl_orders as o')
            ->join('tbl_order_items as oi', 'o.id', '=', 'oi.order_id')
            ->join('tbl_products as p', 'oi.product_id', '=', 'p.id')
            ->join('tbl_categories as c', 'p.category_id', '=', 'c.id')
            ->selectRaw('c.category_name')
            ->selectRaw('SUM(oi.subtotal) AS total_revenue')
            ->selectRaw('SUM(oi.quantity) AS total_items_sold')
            ->where('o.payment_status', 'paid')
            ->whereBetween('o.created_at', [$startDate, $endDate])
            ->groupBy('c.category_name')
            ->orderBy('total_revenue', 'desc')
            ->get()
            ->toArray();

        // Convert to standard PHP array for processing
        $reportData = array_map(function ($item) {
            return (array) $item;
        }, $reportData);

        // --- Summary Calculation ---
        $grandTotalRevenue = 0;
        $grandTotalItemsSold = 0;
        $topCategory = 'N/A';
        $maxRevenue = 0;

        foreach ($reportData as $row) {
            $revenue = (float) $row['total_revenue'];
            $itemsSold = (int) $row['total_items_sold'];

            $grandTotalRevenue += $revenue;
            $grandTotalItemsSold += $itemsSold;

            if ($revenue > $maxRevenue) {
                $maxRevenue = $revenue;
                $topCategory = $row['category_name'];
            }
        }

        $totalCategories = count($reportData);

        // --- Calculate Market Share (%) and integrate it into reportData ---
        // This is necessary to pass the percentage to the view
        $finalReportData = [];
        if ($grandTotalRevenue > 0) {
            foreach ($reportData as $row) {
                $categoryRevenue = (float) $row['total_revenue'];
                $marketShare = ($categoryRevenue / $grandTotalRevenue) * 100;
                $row['market_share_percent'] = $marketShare;
                $finalReportData[] = $row;
            }
        } else {
            $finalReportData = $reportData;
        }
        // --- End Market Share Calculation ---


        return [
            'reportData' => $finalReportData,
            'summaryMetrics' => [
                'grandTotalRevenue' => $grandTotalRevenue,
                'totalCategories' => $totalCategories,
                'grandTotalItemsSold' => $grandTotalItemsSold,
                'topCategory' => $topCategory,
                'year' => $year,
            ]
        ];
    }
    private function getCategoryYearlyReportData(int $startYear, int $endYear): array
    {

        if ($startYear >= $endYear) {

            $maxYear = Order::max(DB::raw('YEAR(created_at)'));
            $maxYear = $maxYear ? (int) $maxYear : (int) date('Y');

            $endYear = $maxYear;
            $startYear = $endYear - 4;
        }

        $startDate = Carbon::createFromDate($startYear, 1, 1)->startOfDay();
        $endDate = Carbon::createFromDate($endYear, 12, 31)->endOfDay();

        // 1. Fetch raw data: Year, Category Name, Revenue, Items Sold
        $rawReportData = DB::table('tbl_orders as o')
            ->join('tbl_order_items as oi', 'o.id', '=', 'oi.order_id')
            ->join('tbl_products as p', 'oi.product_id', '=', 'p.id')
            ->join('tbl_categories as c', 'p.category_id', '=', 'c.id')
            ->selectRaw('YEAR(o.created_at) AS report_year')
            ->selectRaw('c.category_name')
            ->selectRaw('SUM(oi.subtotal) AS total_revenue')
            ->selectRaw('SUM(oi.quantity) AS total_items_sold')
            ->where('o.payment_status', 'paid')
            ->whereBetween('o.created_at', [$startDate, $endDate])
            ->groupBy('report_year', 'c.category_name')
            ->orderBy('report_year', 'asc')
            ->orderBy('total_revenue', 'desc')
            ->get()
            ->toArray();

        $rawReportData = array_map(function ($item) {
            return (array) $item;
        }, $rawReportData);

        $yearlyTotals = [];
        $grandTotalRevenue = 0;
        $grandTotalItemsSold = 0;

        // 2. Calculate Yearly Totals and Grand Totals
        foreach ($rawReportData as $row) {
            $year = $row['report_year'];
            $revenue = (float) $row['total_revenue'];
            $items = (int) $row['total_items_sold'];

            $yearlyTotals[$year] = ($yearlyTotals[$year] ?? 0) + $revenue;
            $grandTotalRevenue += $revenue;
            $grandTotalItemsSold += $items;
        }

        // 3. Calculate Market Share per Category within its Year
        $finalReportData = [];
        foreach ($rawReportData as $row) {
            $year = $row['report_year'];
            $revenue = (float) $row['total_revenue'];
            $annualTotal = $yearlyTotals[$year] ?? 0;

            $row['market_share'] = ($annualTotal > 0) ? ($revenue / $annualTotal) * 100 : 0.00;
            $finalReportData[] = $row;
        }

        return [
            'reportData' => $finalReportData,
            'yearlyTotals' => $yearlyTotals, // Passed for rendering annual sub-totals
            'summaryMetrics' => [
                'grandTotalRevenue' => $grandTotalRevenue,
                'grandTotalItemsSold' => $grandTotalItemsSold,
                'startYear' => $startYear, // These are the calculated or provided start/end years
                'endYear' => $endYear,
            ]
        ];
    }

    /**
     * Shows a specific report based on the 'type' query parameter.
     */
    public function showReport(Request $request)
    {

        $reportType = $request->query('type', 'monthly');
        $selectedYear = (int) $request->query('year', date('Y'));

        $data = [];
        $view = 'reports.index';


        $data['availableYears'] = $this->getAvailableYears();
        $data['selectedYear'] = $selectedYear;


        switch ($reportType) {
            case 'monthly':
                $data['title'] = "Monthly Revenue Report for Year {$selectedYear}";
                $reportData = $this->getMonthlySalesReportData($selectedYear);

                // --- MONTHLY SUMMARY METRICS (Kept as is) ---
                $totalRevenue = 0;
                $totalOrders = 0;
                $bestMonth = [
                    'month' => 'N/A',
                    'revenue' => 0.00
                ];

                foreach ($reportData as $row) {
                    $revenue = (float) $row['total_revenue'];
                    $orders = (int) $row['total_orders'];

                    $totalRevenue += $revenue;
                    $totalOrders += $orders;

                    if ($revenue > $bestMonth['revenue']) {
                        $bestMonth['revenue'] = $revenue;
                        $bestMonth['month'] = $row['order_month'];
                    }
                }

                $averageOrderAmount = ($totalOrders > 0) ? ($totalRevenue / $totalOrders) : 0.00;

                // Pass all results and metrics to the view
                $data['reportData'] = $reportData;
                $data['summaryMetrics'] = [
                    'totalRevenue' => $totalRevenue,
                    'totalOrders' => $totalOrders,
                    'averageOrderAmount' => $averageOrderAmount,
                    'bestMonth' => $bestMonth,
                ];
                // --- END MONTHLY LOGIC ---
                break;

            case 'yearly':

                $data['title'] = 'Revenue by Year (Last 5 Years)';

                // Fetch the structured report data
                $yearlyData = $this->getYearlySalesReportData();
                $data = array_merge($data, $yearlyData); // Includes revenueData, months, startYear, endYear

                $revenueData = $yearlyData['revenueData'];
                $startYear = $yearlyData['startYear'];
                $endYear = $yearlyData['endYear'];

                // --- NEW YEARLY SUMMARY METRICS CALCULATION ---
                $grandTotalRevenue = 0;
                $bestYearRevenue = 0;
                $bestYear = 'N/A';

                foreach ($revenueData as $year => $monthly_revenues) {
                    $annual_total = 0;
                    foreach ($monthly_revenues as $revenue) {
                        $annual_total += (float) $revenue;
                    }

                    $grandTotalRevenue += $annual_total;

                    if ($annual_total > $bestYearRevenue) {
                        $bestYearRevenue = $annual_total;
                        $bestYear = $year;
                    }
                }

                // Get Total Orders for the entire period
                $totalOrdersInPeriod = $this->getYearlyOrderCountData($startYear, $endYear);

                $data['summaryMetrics'] = [
                    'grandTotalRevenue' => $grandTotalRevenue,
                    'totalOrdersInPeriod' => $totalOrdersInPeriod,
                    'bestYear' => $bestYear,
                    'bestYearRevenue' => $bestYearRevenue,
                ];
                // --- END NEW YEARLY LOGIC ---

                break;

            case 'category':

                $data['title'] = "Revenue by Category for Year {$selectedYear}";

                $categoryReport = $this->getCategoryReportData($selectedYear);

                // Merge data directly from the helper function
                $data['reportData'] = $categoryReport['reportData'];
                $data['summaryMetrics'] = $categoryReport['summaryMetrics'];

                break;

            case 'category_yearly':

                $yearlyCategoryReport = $this->getCategoryYearlyReportData($selectedYear, $selectedYear);

                $data = array_merge($data, $yearlyCategoryReport);

                $startYear = $yearlyCategoryReport['summaryMetrics']['startYear'];
                $endYear = $yearlyCategoryReport['summaryMetrics']['endYear'];

                $data['title'] = "Category Performance Historical Report ({$startYear} - {$endYear})";

                $data['selectedStartYear'] = $startYear;
                $data['selectedEndYear'] = $endYear;
                $data['reportYearsText'] = "from {$startYear} to {$endYear}";

                break;

            default:
                return redirect()->route('reports.show', ['type' => 'monthly']);
        }

        // 3. Return the single reports view with the correct data
        return view($view, array_merge($data, ['currentReportType' => $reportType]));
    }

}