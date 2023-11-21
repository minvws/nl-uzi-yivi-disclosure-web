<?php

declare(strict_types=1);

namespace App\Http\Controllers;

class PrivacyStatementController extends Controller
{
    public function __invoke()
    {
        return view('privacy-statement');
    }
}
