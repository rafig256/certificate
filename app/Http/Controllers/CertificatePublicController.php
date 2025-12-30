<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Services\CertificateTextRenderer;
use Illuminate\Http\Request;

class CertificatePublicController extends Controller
{
    public function show(Certificate $certificate, CertificateTextRenderer $renderer)
    {
        $certificate->load([
            'event.blocks',
            'event.organizer',
            'event.signatories',
            'certificateHolder',
        ]);

        $event = $certificate->event;

        /* ================= CONTEXT ================= */

        $context = [
            'holder' => [
                'first_name'    => $certificate->certificateHolder?->first_name,
                'last_name'     => $certificate->certificateHolder?->last_name,
                'full_name'     => $certificate->certificateHolder?->full_name,
                'mobile'        => $certificate->certificateHolder?->mobile,
                'national_code' => $certificate->certificateHolder?->national_code,
                'issued_at'     => $certificate->issued_at,
            ],

            'event' => [
                'id'        => $event->id,
                'title'     => $event->title,
                'level'     => $event->level,
                'location'  => $event->location,
                'status'    => $event->status,
                'starts_at' => optional($event->starts_at)->format('Y-m-d'),
            ],

            'organization' => [
                'name' => $event->organizer?->name,
            ],

            'certificate' => [
                'serial' => $certificate->serial,
                'status' => $certificate->status,
            ],

            'signatories' => $event->signatories,
        ];

        /* ================= BLOCKS ================= */

        $blocks = $event->blocks
            ->where('is_active', true)
            ->sortBy('order')
            ->groupBy('region');

        return view('cert.show', [
            'certificate' => $certificate,
            'blocks'      => $blocks,
            'context'     => $context,
        ]);
    }
}
