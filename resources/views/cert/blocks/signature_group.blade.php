@php
    $max = $block->payload['max'] ?? 3;
@endphp

<div class="block block-signatures align-{{ $block->align }}">
    @foreach ($context['signatories']->take($max) as $signer)
        <div class="signature">

            {{-- تصویر امضا (overlay) --}}
            @if($signer->sign_path)
                <img class="signature-overlay" src="{{ asset('storage/'.$signer->sign_path) }}" alt="signature">
            @endif

            <div class="name">
                {{ $signer->name }}
                @if($signer->responsible)
                    <br>
                    <span>{{ $signer->responsible }}</span>
                @endif
            </div>

            @if($signer->logo_path)
                <img
                    class="logo"
                    src="{{ asset('storage/'.$signer->logo_path) }}"
                    alt=""
                >
            @endif

        </div>
    @endforeach
</div>

