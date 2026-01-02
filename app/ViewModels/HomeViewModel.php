<?php

namespace App\ViewModels;

use App\Models\CertificateHolder;
use App\Models\Organization;
use App\Models\Signatory;
use App\Models\Certificate;

class HomeViewModel
{
    public int $userCount;
    public int $organizationCount;
    public int $signerCount;
    public int $certificateCount;

    public function __construct()
    {
        $this->userCount = CertificateHolder::count();
        $this->organizationCount = Organization::count();
        $this->signerCount = Signatory::count();
        $this->certificateCount = Certificate::count();
    }

    public function totalStat(): int
    {
        return $this->userCount + $this->organizationCount + $this->signerCount + $this->certificateCount;
    }
}
