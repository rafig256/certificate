@php
    $max = $block->payload['max'] ?? 3;
@endphp

<div class="block block-signatures align-{{ $block->align }}">
    @foreach ($context['signatories']->take($max) as $signer)
        <div class="signature">
            @if($signer->signature_path)
                <img src="{{ asset($signer->signature_path) }}" alt="">
            @endif
            <div class="name">{{ $signer->name }}</div>
        </div>
    @endforeach
</div>
