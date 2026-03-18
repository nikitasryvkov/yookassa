@php use Illuminate\Support\Carbon; @endphp
<x-layout>

    <div class="text-lg font-semibold text-center">Acquiring internet payments</div>

    {{ $payments -> links() }}
    <internet-payments>
        <template #cards>
            @foreach($payments as $payment)
                <x-card class="mb-3 payment-card" data-type="{{ $payment->type }}" data-id="{{ $payment->id }}">
                    <div>
                        <div class="font-semibold">Плательщик:</div>{{$payment->payer_name}}
                    </div>
                    <div>
                        <div class="font-semibold">Тип платежа:</div>{{$payment->payment_type}}
                    </div>
                    <div>
                        <div class="font-semibold">Сумма:</div>{{$payment->amount}}
                    </div>
                    <div>
                        <div class="font-semibold">Id Транзакции:</div>{{$payment->transaction_id}}
                    </div>
                    <div>
                        <div class="font-semibold">Назначение:</div>{{$payment->purpose}}
                    </div>
                    <div>
                        <div class="font-semibold">Код клиента:</div>{{$payment->customer_code}}
                    </div>
                    <div>
                        <div class="font-semibold">Id Операции:</div>{{$payment->operation_id}}
                    </div>
                    <div>
                        <div class="font-semibold">Qr id:</div>{{$payment->qrc_id}}
                    </div>
                    <div>
                        <div class="font-semibold">Дата:</div>{{Carbon::parse($payment->created_at)->format('d-m-Y H:i')}}
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
    </internet-payments>
    {{ $payments -> links() }}

</x-layout>
