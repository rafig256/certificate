@inject('renderer', \App\Services\CertificateTextRenderer::class)

<div class="block block-body align-{{ $block->align }}">
    {!! $renderer->render(
        $block->payload['html'] ?? '',
        $context,
        ['unknown_token_mode' => 'empty']
    ) !!}
</div>
