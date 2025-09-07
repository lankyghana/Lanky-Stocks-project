@extends($activeTemplate . 'layouts.master')
@section('content')
    <div class="row justify-content-center gy-4 py-5">
        <div class="col-lg-8">
            <div class="card">
                <h5 class="card-header deposit-card-title">@lang('Paystack')</h5>
                <div class="card-body">
                    <form class="text-center dashboard-form" action="{{ route('ipn.' . $deposit->gateway->alias) }}" method="POST" id="paystack-form">
                        @csrf
                        <ul class="list-group text-center">
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You have to pay '):
                                <strong>{{ showAmount($deposit->final_amount) }}
                                    {{ __($deposit->method_currency) }}</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                @lang('You will get '):
                                <strong>{{ showAmount($deposit->amount) }}</strong>
                            </li>
                        </ul>
                        <button class="btn btn--base w-100 h-45 mt-3" id="btn-confirm" type="button">@lang('Pay Now')</button>
                        <input type="hidden" name="reference" id="reference" value="{{ $data->ref }}">
                        <input type="hidden" name="paystack-trxref" id="paystack-trxref" value="{{ $data->ref }}">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
<script src="https://js.paystack.co/v1/inline.js"></script>
<script>
    document.getElementById('btn-confirm').onclick = function() {
        console.log('Paystack button clicked');
        console.log('Payment data:', {
            key: '{{ $data->key }}',
            email: '{{ $data->email }}',
            amount: {{ $data->amount }},
            currency: '{{ $data->currency }}',
            ref: '{{ $data->ref }}'
        });
        
        const handler = PaystackPop.setup({
            key: '{{ $data->key }}',
            email: '{{ $data->email }}',
            amount: {{ $data->amount }},
            currency: '{{ $data->currency }}',
            ref: '{{ $data->ref }}',
            callback: function(response) {
                console.log('Payment successful:', response);
                // Payment was successful
                document.getElementById('reference').value = response.reference;
                document.getElementById('paystack-trxref').value = response.trxref;
                document.getElementById('paystack-form').submit();
            },
            onClose: function() {
                console.log('Payment cancelled');
                // Payment was cancelled
                alert('Payment was cancelled');
            }
        });
        handler.openIframe();
    };
</script>
@endpush
