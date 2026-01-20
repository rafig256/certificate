<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use App\Models\Signatory;
use App\ViewModels\HomeViewModel;


class HomeController extends Controller
{
    public function home()
    {
        $stat = new HomeViewModel();
        $organizes = Organization::query()->get();
        $signatories = Signatory::all();

        return view('welcome' , compact('stat','organizes' , 'signatories'));
    }
}
