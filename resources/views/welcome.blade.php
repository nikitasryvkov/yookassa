@php use Illuminate\Support\Carbon; @endphp
<x-layout>
    <div class="text-center">
        <account-balance :account-id='@json($accountId)'>
            <template #commission>
                <span class="pl-2">Комиссия к выплате: <strong>{{ $commissionSum }}</strong></span>
            </template>
        </account-balance>
    </div>
    @if(auth()->user()->isAdmin())
        <welcome-page now="{{now()->format('d-m-Y H:i')}}" :payments="{{ $payments }}">
            @if(!empty($payments))
                <div class="text-center mb-10">Сегодняшние поступления:</div>
            @endif

            <template #cards>
                @foreach($payments as $payment)
                    <x-card class="mb-3 payment-card" data-type="{{ $payment->type }}" data-id="{{ $payment->id }}">
                        <div class="tochka-bank-payment-name-{{$payment->type}}">{{ $types[$payment->type] }}</div>
                        <div class="font-semibold">{{ $payment->name }}</div>
                        <div>{{ $payment->purpose }}</div>
                        <div class="font-semibold">{{ $payment->amount }}</div>
                        <div>
                            {{Carbon::parse($payment->created_at)->format('d-m-Y H:i')}}
                        </div>
                        <div>
                            @empty($payment->receipt)
                                <label for="check-create-{{$payment->type}}-{{$payment->id}}">
                                    <input id="check-create-{{$payment->type}}-{{$payment->id}}" class="create-reciept" type="checkbox">
                                    Создать чек
                                </label>
                            @endempty
                            <div class="@empty($payment->receipt) invisible @endif check-status font-semibold">
                                Запрос на создание чека отправлен
                                <span>{{ isset($payment->receipt->checkout_date_time) ? Carbon::parse($payment->receipt->checkout_date_time)->format('d-m-Y H:i') :  '' }}</span>
                            </div>
                            @isset($payment->receipt->statuses)
                                @foreach($payment->receipt->statuses as $status)
                                    <div>Статус: {{$status['status']}} Статус ФН: {{ $status['fn_state'] }}</div>
                                    <div>{{ $status['message'] }}</div>
                                    @if($status['failure_info'])
                                        <div>{{ $status['failure_info'] }}</div>
                                    @endif
                                @endforeach
                            @endisset
                            @isset($payment->receipt)
                                <div class="check-receipt-status-container">
                                    <a href="#" data-id="{{ $payment->receipt->id }}"
                                       class="check-receipt-status font-semibold text-indigo-600 hover:text-indigo-500">
                                        Проверить статус чека
                                    </a>
                                    <div class="check-receipt-status-response">

                                    </div>
                                </div>
                            @endisset
                        </div>
                    </x-card>
                @endforeach
            </template>

            <template #bpayments>
                @include('partials.bot-payments')
            </template>

        </welcome-page>
    @else
        @if(!empty($botPayments))
            <div class="text-center mb-10">Сегодняшние платежи:</div>
        @endif
        @include('partials.bot-payments')
    @endif
</x-layout>
