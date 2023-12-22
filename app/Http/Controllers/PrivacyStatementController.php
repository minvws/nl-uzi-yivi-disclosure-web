<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class PrivacyStatementController extends Controller
{
    public function __invoke(): View
    {
        return view('privacy-statement');
    }
}
