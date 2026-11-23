<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class AdminController extends Controller
{
    protected function authorizeStaff(): void
    {
        abort_unless(Auth::user()?->is_council_staff, 403);
    }
}
