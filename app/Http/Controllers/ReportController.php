<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function showReport(Request $request)
    {
        
        $reportType = $request->query('type', 'sales'); 
        
        $data = []; 
        $view = 'reports.index'; 

        // 2. Determine which report logic to run
        switch ($reportType) {
            case 'monthly':
                // Logic to fetch sales data (e.g., from DB, aggregated)
                $data['title'] = 'Monthly Sales Performance';
                $data['reportData'] = $this->getSalesReportData();
                break;

            case 'yearly':
                // Logic to fetch inventory data
                $data['title'] = 'Yearly Sales Performance';
                $data['reportData'] = $this->getInventoryReportData();
                break;
                
            case 'category':
                // Logic to fetch user activity
                $data['title'] = 'Categorised Sales Performance';
                $data['reportData'] = $this->getUserActivityReportData();
                break;
                
            case 'category_yearly':
                // Logic for a custom/general report page
                $data['title'] = 'Yearly Categorised Sales Performance ';
              $data['reportData'] = $this->getUserActivityReportData();
                break;

            default:
                // Handle invalid or unknown types (optional)
                return redirect()->route('reports.show', ['type' => 'monthly']);
        }

        // 3. Return the single reports view with the correct data
        return view($view, array_merge($data, ['currentReportType' => $reportType]));
    }
    
    // --- Dummy/Placeholder Methods (You would replace these with actual logic) ---
    private function getSalesReportData() { return ['Revenue' => 50000, 'Units Sold' => 1200]; }
    private function getInventoryReportData() { return ['Low Stock Items' => 15, 'Total SKUs' => 500]; }
    private function getUserActivityReportData() { return ['New Users' => 5, 'Logins Today' => 80]; }
}