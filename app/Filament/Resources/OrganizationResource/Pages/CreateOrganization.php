<?php

namespace App\Filament\Resources\OrganizationResource\Pages;

use App\Filament\Resources\OrganizationResource;
use App\Models\Organization;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateOrganization extends CreateRecord
{
    protected static string $resource = OrganizationResource::class;

    protected function handleRecordCreation(array $data): Organization
    {
        return DB::transaction(function () use ($data) {

            $users = $data['users'] ?? [];
            unset($data['users']);

            $organization = Organization::create($data);

            if (! empty($users)) {
                $organization->users()->attach(
                    collect($users)->mapWithKeys(fn ($u) => [
                        $u['user_id'] => ['role' => $u['role']],
                    ])->toArray()
                );
            }

            return $organization;
        });
    }
}
