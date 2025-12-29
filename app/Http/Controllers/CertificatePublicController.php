<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\CertificateTextRenderer;
use Illuminate\Http\Request;

class CertificatePublicController extends Controller
{
    public function show(Certificate $certificate, CertificateTextRenderer $renderer)
    {
        // پیشنهاد: روابطی که نیاز داری را لود کن
        $certificate->load([
            'event.organizer',
            'certificateHolder',
            'event.signatories',
        ]);

        $event = $certificate->event;

        // متن خام (HTML) از event
        $templateHtml = (string) ($event->certificate_text ?? '');

        // Context: داده‌هایی که توکن‌ها از آن خوانده می‌شوند
        $context = [
            'holder' => [
                'first_name'    => $certificate->certificateHolder?->first_name,
                'last_name'     => $certificate->certificateHolder?->last_name,
                'full_name'     => $certificate->certificateHolder?->full_name, // accessor
                'mobile'        => $certificate->certificateHolder?->mobile,
                'national_code' => $certificate->certificateHolder?->national_code,
                'issued_at '    => $certificate->certificateHolder?->issued_at,
            ],

            'event' => [
                'title'     => $event?->title,
                'level'     => $event?->level,
                'location'     => $event?->location,
                'status'     => $event?->status,
                'starts_at' => optional($event?->starts_at)->format('Y-m-d'),
            ],

            'organization' => [
                'name' => $event?->organization?->name,
            ],

            'signer' => [
                'name' => $event?->signer?->name, // اگر رابطه signer داری
            ],

            'certificate' => [
                'serial' => $certificate->serial,
                'status' => $certificate->status,
            ],
        ];

        $renderedHtml = $renderer->render($templateHtml, $context, [
            'unknown_token_mode' => 'empty', // یا 'keep'
        ]);

        return view('certificates.show', [
            'certificate'  => $certificate,
            'renderedHtml' => $renderedHtml,
        ]);
    }
}
