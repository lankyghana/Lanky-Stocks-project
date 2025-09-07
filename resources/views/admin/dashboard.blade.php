@extends('admin.layouts.app')
@section('panel')
    <div class="row gy-4">
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.users.all') }}" icon="las la-users" title="Total Users"
                value="{{ $widget['total_users'] }}" bg="primary" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.users.active') }}" icon="las la-user-check" title="Active Users"
                value="{{ $widget['verified_users'] }}" bg="success" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.users.email.unverified') }}" icon="lar la-envelope"
                title="Email Unverified Users" value="{{ $widget['email_unverified_users'] }}" bg="danger" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget style="6" link="{{ route('admin.users.mobile.unverified') }}" icon="las la-comment-slash"
                title="Mobile Unverified Users" value="{{ $widget['mobile_unverified_users'] }}" bg="warning" />
        </div>
    </div>
    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            @isset($deposit)
                <x-widget style="7" link="{{ route('admin.deposit.list') }}" title="Total Deposited"
                    icon="fas fa-hand-holding-usd" value="{{ showAmount($deposit['total_deposit_amount']) }}" bg="success" />
            @else
                <x-widget style="7" link="{{ route('admin.deposit.list') }}" title="Total Deposited"
                    icon="fas fa-hand-holding-usd" value="0" bg="success" />
            @endisset
        </div>
        <div class="col-xxl-3 col-sm-6">
            @isset($deposit)
                <x-widget style="7" link="{{ route('admin.deposit.pending') }}" title="Pending Deposits"
                    icon="fas fa-spinner" value="{{ $deposit['total_deposit_pending'] }}" bg="warning" />
            @else
                <x-widget style="7" link="{{ route('admin.deposit.pending') }}" title="Pending Deposits"
                    icon="fas fa-spinner" value="0" bg="warning" />
            @endisset
        </div>
        <div class="col-xxl-3 col-sm-6">
            @isset($deposit)
                <x-widget style="7" link="{{ route('admin.deposit.rejected') }}" title="Rejected Deposits"
                    icon="fas fa-ban" value="{{ $deposit['total_deposit_rejected'] }}" bg="danger" />
            @else
                <x-widget style="7" link="{{ route('admin.deposit.rejected') }}" title="Rejected Deposits"
                    icon="fas fa-ban" value="0" bg="danger" />
            @endisset
        </div>
        <div class="col-xxl-3 col-sm-6">
            @isset($deposit)
                <x-widget style="7" link="{{ route('admin.deposit.list') }}" title="Deposited Charge"
                    icon="fas fa-percentage" value="{{ showAmount($deposit['total_deposit_charge']) }}" bg="primary" />
            @else
                <x-widget style="7" link="{{ route('admin.deposit.list') }}" title="Deposited Charge"
                    icon="fas fa-percentage" value="0" bg="primary" />
            @endisset
        </div>
    </div>
    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget value="{{ $widget['total_order'] }}" title="Total Order" style="6"
                link="{{ route('admin.orders.index') }}" icon="las la-shopping-cart" bg="primary" outline="true" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget value="{{ $widget['pending_order'] }}" title="Pending Orders" style="6"
                link="{{ route('admin.orders.pending') }}" icon="las la-spinner" bg="warning" outline="true" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget value="{{ $widget['processing_order'] }}" title="Processing Orders" style="6"
                link="{{ route('admin.orders.processing') }}" icon="la la-refresh" bg="primary" outline="true" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget value="{{ $widget['completed_order'] }}" title="Completed Orders" style="6"
                link="{{ route('admin.orders.completed') }}" icon="las la-check-circle" bg="success" outline="true" />
        </div>
    </div>
    <div class="row gy-4 mt-2">
        <div class="col-xxl-3 col-sm-6">
            <x-widget value="{{ $widget['cancelled_order'] }}" title="Cancelled Orders" style="6"
                link="{{ route('admin.orders.cancelled') }}" icon="las la-times-circle" bg="danger" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget value="{{ $widget['refunded_order'] }}" title="Refunded Orders" style="6"
                link="{{ route('admin.orders.refunded') }}" icon="la la-fast-backward" bg="indigo" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget value="{{ $widget['total_dripfeed_order'] }}" title="Total Dripfeed Orders" style="6"
                link="{{ route('admin.dripfeed.index') }}" icon="las la-fill-drip" bg="primary" />
        </div>
        <div class="col-xxl-3 col-sm-6">
            <x-widget value="{{ $widget['pending_dripfeed_order'] }}" title="Pending Dripfeed Orders" style="6"
                link="{{ route('admin.dripfeed.pending') }}" icon="las la-spinner" bg="warning" />
        </div>
    </div>
    <div class="row mb-none-30 mt-30">
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h5 class="card-title">@lang('Deposit Report')</h5>

                        <div id="dwDatePicker" class="border p-1 cursor-pointer rounded">
                            <i class="la la-calendar"></i>&nbsp;
                            <span></span> <i class="la la-caret-down"></i>
                        </div>
                    </div>
                    <div id="dwChartArea"> </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex flex-wrap justify-content-between">
                        <h5 class="card-title">@lang('Transactions Report')</h5>

                        <div id="trxDatePicker" class="border p-1 cursor-pointer rounded">
                            <i class="la la-calendar"></i>&nbsp;
                            <span></span> <i class="la la-caret-down"></i>
                        </div>
                    </div>

                    <div id="transactionChartArea"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-none-30 mt-5">
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card overflow-hidden">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Browser') (@lang('Last 30 days'))</h5>
                    <canvas id="userBrowserChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By OS') (@lang('Last 30 days'))</h5>
                    <canvas id="userOsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-lg-6 mb-30">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">@lang('Login By Country') (@lang('Last 30 days'))</h5>
                    <canvas id="userCountryChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    @if (gs('cron_status'))
        @include('admin.partials.cron_modal')
    @endif
@endsection

@if (gs('cron_status'))
    @push('breadcrumb-plugins')
        <button class="btn btn-outline--primary btn-sm" data-bs-toggle="modal" data-bs-target="#cronModal">
            <i class="las la-server"></i>@lang('Cron Setup')
        </button>
    @endpush
@endif

@push('script-lib')
    <script src="{{ asset('assets/admin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/vendor/chart.js.2.8.0.js') }}"></script>
    <script src="{{ asset('assets/admin/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/daterangepicker.min.js') }}"></script>
    <script src="{{ asset('assets/admin/js/charts.js') }}"></script>
@endpush

@push('style-lib')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/admin/css/daterangepicker.css') }}">
@endpush

@push('script')
    <script>
        "use strict";

        const start = moment().subtract(14, 'days');
        const end = moment();

        const dateRangeOptions = {
            startDate: start,
            endDate: end,
            ranges: {
                'Today': [moment(), moment()],
                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                'Last 15 Days': [moment().subtract(14, 'days'), moment()],
                'Last 30 Days': [moment().subtract(30, 'days'), moment()],
                'This Month': [moment().startOf('month'), moment().endOf('month')],
                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf(
                    'month')],
                'Last 6 Months': [moment().subtract(6, 'months').startOf('month'), moment().endOf('month')],
                'This Year': [moment().startOf('year'), moment().endOf('year')],
            },
            maxDate: moment()
        }

        const changeDatePickerText = (element, startDate, endDate) => {
            $(element).html(startDate.format('MMMM D, YYYY') + ' - ' + endDate.format('MMMM D, YYYY'));
        }

        let dwChart = barChart(
            document.querySelector("#dwChartArea"),
            @json(__(gs('cur_text'))),
            [{
                    name: 'Deposited',
                    data: []
                }
            ],
            [],
        );

        let trxChart = lineChart(
            document.querySelector("#transactionChartArea"),
            [{
                    name: "Plus Transactions",
                    data: []
                },
                {
                    name: "Minus Transactions",
                    data: []
                }
            ],
            []
        );

        const isValidDate = d => d && d.isValid && d.isValid() && typeof d.format === 'function';

        const depositWithdrawChart = (startDate, endDate) => {
            if (!isValidDate(startDate) || !isValidDate(endDate)) return;
            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }
            const url = @json(route('admin.chart.deposit.withdraw'));
            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {
                        dwChart.updateSeries(data.data);
                        dwChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }

        const transactionChart = (startDate, endDate) => {
            if (!isValidDate(startDate) || !isValidDate(endDate)) return;
            const data = {
                start_date: startDate.format('YYYY-MM-DD'),
                end_date: endDate.format('YYYY-MM-DD')
            }
            const url = @json(route('admin.chart.transaction'));
            $.get(url, data,
                function(data, status) {
                    if (status == 'success') {
                        trxChart.updateSeries(data.data);
                        trxChart.updateOptions({
                            xaxis: {
                                categories: data.created_on,
                            }
                        });
                    }
                }
            );
        }

        $('#dwDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#dwDatePicker span',
            start, end));
        $('#trxDatePicker').daterangepicker(dateRangeOptions, (start, end) => changeDatePickerText('#trxDatePicker span',
            start, end));

        changeDatePickerText('#dwDatePicker span', start, end);
        changeDatePickerText('#trxDatePicker span', start, end);

        // Only load charts if we have valid dates
        if (start && end) {
            depositWithdrawChart(start, end);
            transactionChart(start, end);
        }

        $('#dwDatePicker').on('apply.daterangepicker', (event, picker) => {
            if (picker.startDate && picker.endDate) {
                depositWithdrawChart(picker.startDate, picker.endDate);
            }
        });
        $('#trxDatePicker').on('apply.daterangepicker', (event, picker) => {
            if (picker.startDate && picker.endDate) {
                transactionChart(picker.startDate, picker.endDate);
            }
        });

        piChart(
            document.getElementById('userBrowserChart'),
            @json(array_keys((array)@$chart['user_browser_counter'])),
            @json(array_values((array)@$chart['user_browser_counter']))
        );

        piChart(
            document.getElementById('userOsChart'),
            @json(array_keys((array)@$chart['user_os_counter'])),
            @json(array_values((array)@$chart['user_os_counter']))
        );

        piChart(
            document.getElementById('userCountryChart'),
            @json(array_keys((array)@$chart['user_country_counter'])),
            @json(array_values((array)@$chart['user_country_counter']))
        );
    </script>
@endpush

@push('style')
    <style>
        .apexcharts-menu {
            min-width: 120px !important;
        }
    </style>
@endpush
