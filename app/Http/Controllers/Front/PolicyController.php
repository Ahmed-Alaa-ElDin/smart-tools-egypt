<?php

namespace App\Http\Controllers\Front;

use App\Models\Policy;
use App\Http\Controllers\Controller;

class PolicyController extends Controller
{
    public function delivery()
    {
        $policy = Policy::where('name', 'delivery')->firstOrFail();

        return view('front.policies.delivery', compact('policy'));
    }

    public function returnAndExchange()
    {
        $policy = Policy::where('name', 'return-and-exchange')->firstOrFail();

        return view('front.policies.return-and-exchange', compact('policy'));
    }

    public function privacy()
    {
        $policy = Policy::where('name', 'privacy')->firstOrFail();

        return view('front.policies.privacy', compact('policy'));
    }
}
