@php
    $data = $getState();
@endphp

<x-filament::badge
    :color="match($data['status']) {
        'draft'   => 'gray',
        'active'  => 'success',
        'revoked' => 'danger',
        default   => 'gray',
    }"
    class="whitespace-normal break-words h-auto leading-relaxed text-center"
>
    {{ __('fields.certificate_statuses.' . $data['status']) }}
</x-filament::badge>

<x-filament::badge
    :color="$data['has_payment_issue'] ? 'warning' : 'success'"
    class="whitespace-normal break-words h-auto leading-relaxed text-center"
>
    {{ $data['has_payment_issue']
        ? __('fields.require_to_pay')
        : __('fields.payed')
    }}
</x-filament::badge>

