<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Loan;
use Carbon\Carbon;
use App\Exports\LoanSummaryExport;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpWord\IOFactory;

class LoanSummaryController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Gate::allows('loan_access'), 403);

        $dateRange = '';
        if ($request->date) {
            $dateRange = $request->input('date', Carbon::now()->format('m/d/Y') . ' - ' . Carbon::now()->format('m/d/Y'));

            // Extract start and end dates from the range
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('M j, Y', trim($dates[0]))->format('m/d/Y');
            $endDate = Carbon::createFromFormat('M j, Y', trim($dates[1]))->format('m/d/Y');
            
            $loans = Loan::with(['customer', 'details'])
                ->whereBetween('date_of_loan', [
                    $startDate,
                    $endDate
                ])
                ->paginate(20);
        } else {
            $loans = Loan::with(['customer', 'details'])
                ->paginate(20);
        }

        return view('pages.loan-summary.index', compact('loans', 'dateRange'));
    }

    public function export(Request $request)
    {
        abort_unless(Gate::allows('loan_access'), 403);

        $dateRange = $request->input('date');
        
        if ($dateRange) {
            // Extract start and end dates from the range
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('M j, Y', trim($dates[0]))->format('m/d/Y');
            $endDate = Carbon::createFromFormat('M j, Y', trim($dates[1]))->format('m/d/Y');

            $export = new LoanSummaryExport($startDate, $endDate);
            $phpWord = $export->generate();

            $filename = 'loan_summary_' . Carbon::now()->format('Y-m-d_His') . '.docx';
            
            $tempFile = sys_get_temp_dir() . '/' . $filename;
            $phpWord->save($tempFile);
            
            return response()->download($tempFile)->deleteFileAfterSend(true);
        }

        return back()->with('error', 'Please select a date range');
    }

    public function print(Request $request)
    {
        abort_unless(Gate::allows('loan_access'), 403);

        $dateRange = $request->input('date');
        
        if ($dateRange) {
            // Extract start and end dates from the range
            $dates = explode(' - ', $dateRange);
            $startDate = Carbon::createFromFormat('M j, Y', trim($dates[0]))->format('m/d/Y');
            $endDate = Carbon::createFromFormat('M j, Y', trim($dates[1]))->format('m/d/Y');

            $loans = Loan::with(['customer', 'customer.customerType'])
                ->whereBetween('date_of_loan', [
                    $startDate,
                    $endDate
                ])
                ->get();

            return view('pages.loan-summary.print', compact('loans', 'startDate', 'endDate'));
        }

        return back()->with('error', 'Please select a date range');
    }
}
