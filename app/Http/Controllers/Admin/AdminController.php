<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Lib\CurlRequest;
use App\Models\AdminNotification;
use App\Models\ApiProvider;
use App\Models\Order;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    /**
     * Show the bug report request form for admins
     */
    public function requestReport()
    {
        $pageTitle = 'Request Bug Report';
        return view('admin.request_report', compact('pageTitle'));
    }
    public function dashboard()
    {
        $pageTitle = 'Dashboard';
        $providers = ApiProvider::active()->orderBy('name')->whereHas('order')->get();

        // Basic widget counts
        $widget['total_users'] = User::count();
        $widget['verified_users'] = User::where('status', Status::USER_ACTIVE)
            ->where('ev', Status::VERIFIED)
            ->where('sv', Status::VERIFIED)
            ->count();
        $widget['email_unverified_users'] = User::emailUnverified()->count();
        $widget['mobile_unverified_users'] = User::mobileUnverified()->count();
        
        // Order related counts
        $widget['total_order'] = Order::directOrder()->count();
        $widget['pending_order'] = Order::directOrder()->pending()->count();
        $widget['processing_order'] = Order::directOrder()->processing()->count();
        $widget['completed_order'] = Order::directOrder()->completed()->count();
        $widget['cancelled_order'] = Order::directOrder()->cancelled()->count();
        $widget['refunded_order'] = Order::directOrder()->refunded()->count();
        $widget['total_dripfeed_order'] = Order::dripfeedOrder()->count();
        $widget['pending_dripfeed_order'] = Order::dripfeedOrder()->pending()->count();

        // Provide all deposit values required by the dashboard view
        $deposit = [
            'total_deposit_amount' => Deposit::where('status', Status::PAYMENT_SUCCESS)->sum('amount'),
            'total_deposit_pending' => Deposit::where('status', Status::PAYMENT_PENDING)->count(),
            'total_deposit_rejected' => Deposit::where('status', Status::PAYMENT_REJECT)->count(),
            'total_deposit_charge' => Deposit::where('status', Status::PAYMENT_SUCCESS)->sum('charge'),
        ];

        // User Browsing, Country, Operating Log
        try {
            $userLoginData = UserLogin::where('created_at', '>=', now()->subDays(30))
                ->selectRaw('COUNT(*) as count, browser, os, country_code')
                ->groupBy('browser', 'os', 'country_code')
                ->get();

            $chart['user_browser_counter'] = $userLoginData->groupBy('browser')
                ->mapWithKeys(fn($item) => [$item->first()->browser => $item->sum('count')])
                ->toArray();

            $chart['user_os_counter'] = $userLoginData->groupBy('os')
                ->mapWithKeys(fn($item) => [$item->first()->os => $item->sum('count')])
                ->toArray();

            $chart['user_country_counter'] = $userLoginData->groupBy('country_code')
                ->mapWithKeys(fn($item) => [$item->first()->country_code => $item->sum('count')])
                ->toArray();
        } catch (\Exception $e) {
            Log::error('Dashboard stats error: ' . $e->getMessage());
            $chart['user_browser_counter'] = [];
            $chart['user_os_counter'] = [];
            $chart['user_country_counter'] = [];
        }

        return view('admin.dashboard', compact('pageTitle', 'widget', 'chart', 'providers', 'deposit'));
    }

    public function depositAndWithdrawReport(Request $request) {
        $startDate = trim($request->start_date ?? '') ?: null;
        $endDate = trim($request->end_date ?? '') ?: null;
        \Log::info('depositAndWithdrawReport received', ['startDate' => $startDate, 'endDate' => $endDate]);
        if (!$this->isValidDate($startDate) || !$this->isValidDate($endDate)) {
            \Log::warning('depositAndWithdrawReport invalid date', ['startDate' => $startDate, 'endDate' => $endDate]);
            return response()->json([
                'created_on' => [],
                'data' => [[
                    'name' => 'Deposited',
                    'data' => []
                ]]
            ]);
        }
        $diffInDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';
        if ($groupBy == 'days') {
            $dates = $this->getAllDates($startDate, $endDate);
        } else {
            $dates = $this->getAllMonths($startDate, $endDate);
        }
        $deposits = Deposit::successful();
        if ($this->isValidDate($startDate)) {
            $deposits = $deposits->whereDate('created_at', '>=', $startDate);
        } elseif ($startDate !== null) {
            \Log::warning('depositAndWithdrawReport invalid startDate for whereDate', ['startDate' => $startDate]);
        }
        if ($this->isValidDate($endDate)) {
            $deposits = $deposits->whereDate('created_at', '<=', $endDate);
        } elseif ($endDate !== null) {
            \Log::warning('depositAndWithdrawReport invalid endDate for whereDate', ['endDate' => $endDate]);
        }
        $deposits = $deposits
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $data = [];
        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'deposits' => getAmount($deposits->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }
        $data = collect($data);
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Deposited',
                'data' => $data->pluck('deposits')
            ]
        ];
        return response()->json($report);
    }

    public function transactionReport(Request $request) {
        $startDate = trim($request->start_date ?? '') ?: null;
        $endDate = trim($request->end_date ?? '') ?: null;
        \Log::info('transactionReport received', ['startDate' => $startDate, 'endDate' => $endDate]);
        if (!$this->isValidDate($startDate) || !$this->isValidDate($endDate)) {
            \Log::warning('transactionReport invalid date', ['startDate' => $startDate, 'endDate' => $endDate]);
            return response()->json([
                'created_on' => [],
                'data' => [
                    ['name' => 'Plus Transactions', 'data' => []],
                    ['name' => 'Minus Transactions', 'data' => []]
                ]
            ]);
        }
        $diffInDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
        $groupBy = $diffInDays > 30 ? 'months' : 'days';
        $format = $diffInDays > 30 ? '%M-%Y'  : '%d-%M-%Y';
        if ($groupBy == 'days') {
            $dates = $this->getAllDates($startDate, $endDate);
        } else {
            $dates = $this->getAllMonths($startDate, $endDate);
        }
        $plusTransactions = Transaction::where('trx_type','+');
        if ($this->isValidDate($startDate)) {
            $plusTransactions = $plusTransactions->whereDate('created_at', '>=', $startDate);
        } elseif ($startDate !== null) {
            \Log::warning('transactionReport invalid startDate for plusTransactions', ['startDate' => $startDate]);
        }
        if ($this->isValidDate($endDate)) {
            $plusTransactions = $plusTransactions->whereDate('created_at', '<=', $endDate);
        } elseif ($endDate !== null) {
            \Log::warning('transactionReport invalid endDate for plusTransactions', ['endDate' => $endDate]);
        }
        $plusTransactions = $plusTransactions
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();
        $minusTransactions = Transaction::where('trx_type','-');
        if ($this->isValidDate($startDate)) {
            $minusTransactions = $minusTransactions->whereDate('created_at', '>=', $startDate);
        } elseif ($startDate !== null) {
            \Log::warning('transactionReport invalid startDate for minusTransactions', ['startDate' => $startDate]);
        }
        if ($this->isValidDate($endDate)) {
            $minusTransactions = $minusTransactions->whereDate('created_at', '<=', $endDate);
        } elseif ($endDate !== null) {
            \Log::warning('transactionReport invalid endDate for minusTransactions', ['endDate' => $endDate]);
        }
        $minusTransactions = $minusTransactions
            ->selectRaw('SUM(amount) AS amount')
            ->selectRaw("DATE_FORMAT(created_at, '{$format}') as created_on")
            ->latest()
            ->groupBy('created_on')
            ->get();

        $data = [];
        foreach ($dates as $date) {
            $data[] = [
                'created_on' => $date,
                'credits' => getAmount($plusTransactions->where('created_on', $date)->first()?->amount ?? 0),
                'debits' => getAmount($minusTransactions->where('created_on', $date)->first()?->amount ?? 0)
            ];
        }
        $data = collect($data);
        $report['created_on']   = $data->pluck('created_on');
        $report['data']     = [
            [
                'name' => 'Plus Transactions',
                'data' => $data->pluck('credits')
            ],
            [
                'name' => 'Minus Transactions',
                'data' => $data->pluck('debits')
            ]
        ];
        return response()->json($report);
    }

    public function downloadAttachment($file_hash)
    {
        // Adjust the path as needed for your app
        $filePath = storage_path('app/attachments/' . $file_hash);

        if (!file_exists($filePath)) {
            abort(404, 'File not found.');
        }

        return response()->download($filePath);
    }

    /**
     * View manual payment document uploaded by user (debugs file name and paths)
     */
    public function viewManualPaymentDocument($deposit_id)
    {
        $deposit = Deposit::findOrFail($deposit_id);
        $fileName = $deposit->detail->file ?? $deposit->detail->attachment ?? null;
        if (!$fileName) {
            \Log::info('No document uploaded for deposit', ['deposit_id' => $deposit_id]);
            abort(404, 'No document uploaded.');
        }
        $publicPath = public_path('assets/images/verify/deposit/' . $fileName);
        $storagePath = storage_path('app/verify/deposit/' . $fileName);
        \Log::info('Manual payment document debug', [
            'deposit_id' => $deposit_id,
            'fileName' => $fileName,
            'publicPath' => $publicPath,
            'storagePath' => $storagePath,
            'exists_public' => file_exists($publicPath),
            'exists_storage' => file_exists($storagePath),
        ]);
        if (file_exists($publicPath)) {
            return response()->file($publicPath);
        }
        if (file_exists($storagePath)) {
            return response()->file($storagePath);
        }
        abort(404, 'File not found.');
    }

    private function isValidDate($date) {
        if (!$date) return false;
        try {
            $d = Carbon::parse($date);
            return $d && $d->format('Y-m-d') === date('Y-m-d', strtotime($date));
        } catch (\Exception $e) {
            return false;
        }
    }

        // Keep the rest of your original AdminController.php code below this line

    /**
     * Get all dates between two dates as array of 'd-M-Y' format.
     */
    private function getAllDates($startDate, $endDate)
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $dates = [];
        while ($start <= $end) {
            $dates[] = $start->format('d-M-Y');
            $start->addDay();
        }
        return $dates;
    }

    /**
     * Get all months between two dates as array of 'M-Y' format.
     */
    private function getAllMonths($startDate, $endDate)
    {
        $start = Carbon::parse($startDate)->startOfMonth();
        $end = Carbon::parse($endDate)->startOfMonth();
        $months = [];
        while ($start <= $end) {
            $months[] = $start->format('M-Y');
            $start->addMonth();
        }
        return $months;
    }

    }