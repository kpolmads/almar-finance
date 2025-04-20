<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Branch;
use App\Models\Breakdown;
use App\Models\CashBill;
use App\Models\Collection;
use App\Models\ComputeCashOnHand;
use App\Models\Customer;
use App\Models\DailyWorkOrder;
use App\Models\Expenses;
use App\Models\Loan;
use App\Models\SavingsDeposit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Cache;

class BMController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(Gate::allows('branch_access'), 404);
        $branch = auth()->user()->branch_id;
        $lists = Customer::where('branch_id', $branch)->orderByDesc('id')->paginate(10);
        $totalCustomer = Customer::where('branch_id', $branch)->count();

        $logs = ActivityLog::orderByDesc('id')->paginate(20);

        return view('pages.branch.index', compact('lists', 'totalCustomer', 'logs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function employeeEvaluation(Request $request)
    {
        return view('pages.employeeevaluation.index');
    }

    public function cashReqForm(Request $request)
    {
        return view('pages.cashadvreqform.index');
    }


    public function badAccount(Request $request)
    {
        $branch = auth()->user()->branch_id;

        // Initialize the base query
        $query = Loan::with('customer')
            ->where('branch_id', $branch)
            ->where('transaction_customer_status', 'BA')
            ->where('status', 'UNPD');

        // Apply the search filter
        if ($request->search) {
            $query->whereHas('customer', function ($query) use ($request) {
                $query->where('first_name', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Execute the query with pagination
        $lists = $query->orderBy('created_at', 'asc')->paginate(20);

        return view('pages.badacc.index', compact('lists'));
    }

    public function regAccount(Request $request)
    {
        $lists = Loan::where('transaction_customer_status', null)->paginate(20);
        return view('pages.regacc.index', compact('lists'));
    }

    public function todaysPayer(Request $request)
    {
        return view('pages.todaypayer.index');
    }

    public function latePayer(Request $request)
    {
        return view('pages.latepayer.index');
    }
    public function paymentHistory(Request $request)
    {
        $branch = auth()->user()->branch_id;
        $lists = Collection::where('branch_id', $branch)->paginate(20);
        return view('pages.payhistory.index', compact('lists'));
    }

    public function pendingLoandApproval(Request $request)
    {
        $branch = auth()->user()->branch_id;
        $lists = Loan::with('customer')->where('principal_amount', '<', '50000')
            ->where('branch_id', $branch)
            ->where('status', null)
            ->paginate(10);

        return view('pages.pendingloanapp.index', compact('lists'));
    }

    public function approvedLoan(Request $request)
    {
        $branch = auth()->user()->branch_id;
        $lists = Loan::where('principal_amount', '<', '50000')
            ->where('branch_id', $branch)
            ->where('status', '!=', null)
            ->where('status', '!=', 'CNCLD')->paginate(10);

        return view('pages.pendingloanapp.approvedloans.index', compact('lists'));
    }

    public function rejectedLoan(Request $request)
    {

        $lists = Loan::where('principal_amount', '<', '50000')
            ->where('status', '=', 'CNCLD')->paginate(10);

        return view('pages.pendingloanapp.rejectedloans.index', compact('lists'));
    }

    public function loanRenewal(Request $request)
    {
        return view('pages.loanrenewal.index');
    }

    public function overdueAcc(Request $request)
    {

        $lists = Loan::with([
            'details' => function ($query) {
                $query->where('loan_due_date', '<', now()->toDateString());
            }
        ])
            ->where('transaction_customer_status', '')->paginate(20);
        return view('pages.overdueacc.index', compact('lists'));

    }

    public function biometricsAttendance()
    {
        return view('pages.attendancebm.index');
    }

    public function csor(Request $request)
    {
        $branch = auth()->user()->branch_id;
        
        // Validate request dates
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $query = Expenses::where('branch_id', $branch);

        if ($request->filled('start_date')) {
            if ($request->filled('end_date')) {
                $query->whereBetween('exp_date', [$request->start_date, $request->end_date]);
            } else {
                $query->whereDate('exp_date', $request->start_date);
            }
        }

        $expenses = $query->orderBy('exp_date', 'asc')->paginate(10);

        // Cache breakdowns for better performance
        $breakdowns = Cache::remember('branch_breakdowns_' . $branch, now()->addHours(1), function () use ($branch) {
            return Breakdown::where('branch_id', $branch)->get();
        });

        $denominations = [
            '1000.00' => 'pbil',
            '500.00' => 'pbil',
            '200.00' => 'pbil',
            '100.00' => 'pbil',
            '50.00' => 'pbil',
            '20.00' => 'pbil',
            '10.00' => 'coin',
            '5.00' => 'coin',
            '1.00' => 'coin',
            '0.25' => 'coin',
        ];

        $cashBillData = [];

        foreach ($breakdowns as $breakdown) {
            foreach ($denominations as $denomination => $type) {
                $count = Cache::remember('cash_bill_count_' . $branch . '_' . $breakdown->id . '_' . $denomination, now()->addHours(1), function () use ($branch, $breakdown, $denomination) {
                    return CashBill::where('branch_id', $branch)
                        ->where('breakdown_id', $breakdown->id)
                        ->where('denomination', $denomination)
                        ->count();
                });

                $sum = Cache::remember('cash_bill_sum_' . $branch . '_' . $breakdown->id . '_' . $denomination, now()->addHours(1), function () use ($branch, $breakdown, $denomination) {
                    return CashBill::where('branch_id', $branch)
                        ->where('breakdown_id', $breakdown->id)
                        ->where('denomination', $denomination)
                        ->sum('amount');
                });

                $cashBillData[] = [
                    'breakdown_id' => $breakdown->id,
                    'denomination' => $denomination,
                    'type' => $type,
                    'count' => $count,
                    'sum' => $sum
                ];
            }
        }

        $comps = ComputeCashOnHand::where('branch_id', $branch)->paginate(10);

        // Cache loan and collection metrics
        $metrics = Cache::remember('branch_metrics_' . $branch, now()->addHours(1), function () use ($branch) {
            return [
                'customerCountRegular' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', null)
                    ->where(function ($query) {
                        $query->where('transaction_type', 'NEW')
                            ->orWhere('transaction_type', 'RENEW');
                    })
                    ->count(),

                'receivableAmountRegular' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', null)
                    ->where(function ($query) {
                        $query->where('transaction_type', 'NEW')
                            ->orWhere('transaction_type', 'RENEW');
                    })
                    ->sum('payable_amount'),

                'collectionAmountRegular' => Collection::where('branch_id', $branch)
                    ->whereHas('loanDetails.loan', function ($query) {
                        $query->where('transaction_customer_status', null)
                            ->where(function ($subquery) {
                                $subquery->where('transaction_type', 'NEW')
                                    ->orWhere('transaction_type', 'RENEW');
                            });
                    })
                    ->sum('paid_amount'),

                'customerCountCA' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', null)
                    ->where('transaction_type', 'CA')
                    ->count(),

                'receivableAmountCA' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', null)
                    ->where('transaction_type', 'CA')
                    ->sum('payable_amount'),

                'collectionAmountCA' => Collection::where('branch_id', $branch)
                    ->whereHas('loanDetails.loan', function ($query) {
                        $query->where('transaction_customer_status', null)
                            ->where('transaction_type', 'CA');
                    })
                    ->sum('paid_amount'),

                'customerCountBad' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', 'BA')
                    ->count(),

                'receivableAmountBA' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', 'BA')
                    ->sum('payable_amount'),

                'collectionAmountBA' => Collection::where('branch_id', $branch)
                    ->whereHas('loanDetails.loan', function ($query) {
                        $query->where('transaction_customer_status', 'BA');
                    })
                    ->sum('paid_amount'),

                'customerCountCABad' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', 'BA')
                    ->where('transaction_type', 'CA')
                    ->count(),

                'receivableAmountCABA' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', 'BA')
                    ->where('transaction_type', 'CA')
                    ->sum('payable_amount'),

                'collectionAmountCABA' => Collection::where('branch_id', $branch)
                    ->whereHas('loanDetails.loan', function ($query) {
                        $query->where('transaction_customer_status', 'BA')
                            ->where('transaction_type', 'CA');
                    })
                    ->sum('paid_amount'),
            ];
        });

        return view('pages.csor.index', array_merge(
            compact('expenses', 'breakdowns', 'cashBillData', 'comps'),
            $metrics
        ));
    }

    public function csorPrint(Request $request)
    {
        $branch = auth()->user()->branch_id;
        $branchlocation = Branch::find($branch);
        
        // Validate request dates
        $validated = $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $query = Expenses::where('branch_id', $branch);

        if ($request->filled('start_date')) {
            if ($request->filled('end_date')) {
                $query->whereBetween('exp_date', [$request->start_date, $request->end_date]);
            } else {
                $query->whereDate('exp_date', $request->start_date);
            }
        }

        $expenses = $query->orderBy('exp_date', 'asc')->paginate(10);

        // Cache breakdowns for better performance
        $breakdowns = Cache::remember('branch_breakdowns_' . $branch, now()->addHours(1), function () use ($branch) {
            return Breakdown::where('branch_id', $branch)->get();
        });

        $denominations = [
            '1000.00' => 'pbil',
            '500.00' => 'pbil',
            '200.00' => 'pbil',
            '100.00' => 'pbil',
            '50.00' => 'pbil',
            '20.00' => 'pbil',
            '10.00' => 'coin',
            '5.00' => 'coin',
            '1.00' => 'coin',
            '0.25' => 'coin',
        ];

        $cashBillData = [];

        foreach ($breakdowns as $breakdown) {
            foreach ($denominations as $denomination => $type) {
                $count = Cache::remember('cash_bill_count_' . $branch . '_' . $breakdown->id . '_' . $denomination, now()->addHours(1), function () use ($branch, $breakdown, $denomination) {
                    return CashBill::where('branch_id', $branch)
                        ->where('breakdown_id', $breakdown->id)
                        ->where('denomination', $denomination)
                        ->count();
                });

                $sum = Cache::remember('cash_bill_sum_' . $branch . '_' . $breakdown->id . '_' . $denomination, now()->addHours(1), function () use ($branch, $breakdown, $denomination) {
                    return CashBill::where('branch_id', $branch)
                        ->where('breakdown_id', $breakdown->id)
                        ->where('denomination', $denomination)
                        ->sum('amount');
                });

                $cashBillData[] = [
                    'breakdown_id' => $breakdown->id,
                    'denomination' => $denomination,
                    'type' => $type,
                    'count' => $count,
                    'sum' => $sum
                ];
            }
        }

        $comps = ComputeCashOnHand::find($request->coh_id);

        // Cache loan and collection metrics
        $metrics = Cache::remember('branch_metrics_' . $branch, now()->addHours(1), function () use ($branch) {
            return [
                'customerCountRegular' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', null)
                    ->where(function ($query) {
                        $query->where('transaction_type', 'NEW')
                            ->orWhere('transaction_type', 'RENEW');
                    })
                    ->count(),

                'receivableAmountRegular' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', null)
                    ->where(function ($query) {
                        $query->where('transaction_type', 'NEW')
                            ->orWhere('transaction_type', 'RENEW');
                    })
                    ->sum('payable_amount'),

                'collectionAmountRegular' => Collection::where('branch_id', $branch)
                    ->whereHas('loanDetails.loan', function ($query) {
                        $query->where('transaction_customer_status', null)
                            ->where(function ($subquery) {
                                $subquery->where('transaction_type', 'NEW')
                                    ->orWhere('transaction_type', 'RENEW');
                            });
                    })
                    ->sum('paid_amount'),

                'customerCountCA' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', null)
                    ->where('transaction_type', 'CA')
                    ->count(),

                'receivableAmountCA' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', null)
                    ->where('transaction_type', 'CA')
                    ->sum('payable_amount'),

                'collectionAmountCA' => Collection::where('branch_id', $branch)
                    ->whereHas('loanDetails.loan', function ($query) {
                        $query->where('transaction_customer_status', null)
                            ->where('transaction_type', 'CA');
                    })
                    ->sum('paid_amount'),

                'customerCountBad' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', 'BA')
                    ->count(),

                'receivableAmountBA' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', 'BA')
                    ->sum('payable_amount'),

                'collectionAmountBA' => Collection::where('branch_id', $branch)
                    ->whereHas('loanDetails.loan', function ($query) {
                        $query->where('transaction_customer_status', 'BA');
                    })
                    ->sum('paid_amount'),

                'customerCountCABad' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', 'BA')
                    ->where('transaction_type', 'CA')
                    ->count(),

                'receivableAmountCABA' => Loan::where('branch_id', $branch)
                    ->where('transaction_customer_status', 'BA')
                    ->where('transaction_type', 'CA')
                    ->sum('payable_amount'),

                'collectionAmountCABA' => Collection::where('branch_id', $branch)
                    ->whereHas('loanDetails.loan', function ($query) {
                        $query->where('transaction_customer_status', 'BA')
                            ->where('transaction_type', 'CA');
                    })
                    ->sum('paid_amount'),
            ];
        });

        return view('pages.csor.print.index', array_merge(
            compact('expenses', 'breakdowns', 'cashBillData', 'comps', 'branchlocation'),
            $metrics
        ));
    }



    public function leaveRequest()
    {
        return view('pages.requestform.leave.index');
    }

    public function loanInformation()
    {
        return view('pages.loaninfo.index');
    }


    public function undertimeRequest()
    {
        return view('pages.requestform.undertime.index');
    }

    public function idRequest()
    {
        return view('pages.requestform.id.index');
    }

    public function clearanceRequest()
    {
        return view('pages.requestform.clearance.index');
    }

    public function cashadvanceRequest()
    {
        return view('pages.requestform.cashadvance.index');
    }

    public function cashBond(Request $request)
    {
        return view('pages.requestform.cashbond.index');
    }

    public function printStatement(Request $request, $id)
    {
        $loan = Loan::find($id);

        return view('pages.pendingloanapp.printStatement.index', compact('loan'));

    }

    public function cashBondPrint(Request $request)
    {

        return view('pages.requestform.cashbond.cashBondPrint.index');
    }

    public function reminderPay(Request $request)
    {

        return view('pages.reminder.index');
    }

    public function dailyWorklist(Request $request)
    {
        $lists = DailyWorkOrder::paginate(20);

        return view('pages.requestform.dailyworkorder.index', compact('lists'));
    }

    public function dailyWorkAdd(Request $request)
    {

        return view('pages.requestform.dailyworkorder.add.index');
    }

    public function dailyWorkStore(Request $request)
    {
        $workorder = new DailyWorkOrder();
        $workorder->type_of_holiday = $request->type_of_holiday;
        $workorder->date = $request->date;
        $workorder->user_id = auth()->user()->id;
        $workorder->save();

        return redirect()->route('dailyworkorder.index')->with('success', 'Request Daily Work Order Submitted');
    }

    public function performanceRecord()
    {
        $branch = auth()->user()->branch_id;

        $branch_managers = User::with([
            'branch',
            'loans' => function ($query) {
                $query->whereRaw("STR_TO_DATE(date_of_loan, '%m/%d/%Y') >= ?", [Carbon::now()->startOfMonth()]);
            },
            'customers'
        ])
            ->where('branch_id', auth()->user()->branch_id)
            ->whereHas('roles', function ($query) {
                $query->where('title', 'Branch Manager');
            })
            ->where('id', auth()->user()->id)
            ->get();


        return view('pages.branch.performance.index', compact('branch_managers'));

    }


    public function performanceRecordAPI()
    {

        $branch_managers = User::with([
            'branch',
            'loans' => function ($query) {
                $query->whereRaw("STR_TO_DATE(date_of_loan, '%m/%d/%Y') >= ?", [Carbon::now()->startOfMonth()]);
            },
            'customers'
        ])
            ->whereHas('roles', function ($query) {
                $query->where('title', 'Branch Manager');
            })
            ->get();

        return response()->json($branch_managers, 200);

    }

}
