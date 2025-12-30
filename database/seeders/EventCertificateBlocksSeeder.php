<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\EventBlock;

class EventCertificateBlocksSeeder extends Seeder
{
    public function run(): void
    {
        // فرض: اولین رویداد
        $event = Event::query()->first();

        if (! $event) {
            $this->command->warn('No event found. Seeder skipped.');
            return;
        }

        EventBlock::where('event_id', $event->id)->delete();

        $order = 1;

        /* ================= HEADER ================= */

        EventBlock::create([
            'event_id' => $event->id,
            'region'   => 'header',
            'type'     => 'token',
            'align'    => 'right',
            'order'    => $order++,
            'payload'  => [
                'token' => 'organization.name',
            ],
        ]);

        EventBlock::create([
            'event_id' => $event->id,
            'region'   => 'header',
            'type'     => 'token',
            'align'    => 'left',
            'order'    => $order++,
            'payload'  => [
                'token' => 'certificate.serial',
            ],
        ]);

        /* ================= BODY ================= */

        EventBlock::create([
            'event_id' => $event->id,
            'region'   => 'body',
            'type'     => 'title',
            'align'    => 'center',
            'order'    => 1,
            'payload'  => [
                'text' => 'گواهینامه پایان دوره',
            ],
        ]);

        EventBlock::create([
            'event_id' => $event->id,
            'region'   => 'body',
            'type'     => 'body_text',
            'align'    => 'center',
            'order'    => 2,
            'payload'  => [
                'html' => '<p>
بدین وسیله گواهی می‌شود که
<strong>{{holder.full_name}}</strong>
در دوره
<strong>{{event.title}}</strong>
با موفقیت شرکت نموده است.
</p>',
            ],
        ]);

        /* ================= FOOTER ================= */

        EventBlock::create([
            'event_id' => $event->id,
            'region'   => 'footer',
            'type'     => 'signature_group',
            'align'    => 'center',
            'order'    => 1,
            'payload'  => [
                'max' => 3,
            ],
        ]);

        $this->command->info('Event certificate blocks seeded successfully.');
    }
}
