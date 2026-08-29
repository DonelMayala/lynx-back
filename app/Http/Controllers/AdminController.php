<?php

namespace App\Http\Controllers;

use App\Models\Camera;
use App\Models\Organization;
use App\Models\Site;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View
    {
        Gate::authorize('administer-system');

        $users = User::with(['organization', 'roles'])->orderBy('name')->paginate(12);
        $stats = [
            'organizations' => Organization::count(),
            'sites' => Site::count(),
            'cameras' => Camera::count(),
            'users' => User::count(),
        ];

        return view('admin.index', compact('users', 'stats'));
    }
}
