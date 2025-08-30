<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SidebarLink;

class GeneralViewController extends Controller
{
    public function show($link)
    {
        // rebuild full DB route_path (/something)
        $routePath = '/' . ltrim($link, '/');

        // ensure the link exists in DB
        $sidebar = SidebarLink::where('manual_type', 1)
            ->where('route_path', $routePath)
            ->first();

        if (! $sidebar) {
            abort(404, "Page not allowed");
        }

        // convert "/reports/sales" -> "general_views.reports.sales"
        $viewKey = str_replace('/', '.', ltrim($routePath, '/'));
        $viewPath = 'general_views.' . $viewKey;

        if (! view()->exists($viewPath)) {
            abort(404, "View file not found: {$viewPath}");
        }

        return view($viewPath, [
            'sidebar' => $sidebar
        ]);
    }
}
