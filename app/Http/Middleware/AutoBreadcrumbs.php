<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AutoBreadcrumbs
{
    public function handle(Request $request, Closure $next)
    {
        $segments = $request->segments();

        $breadcrumbs = [];

        // Always start with Dashboard
        $breadcrumbs[] = [
            'name' => 'Dashboard',
            'href' => route('dashboard'),
        ];

        $url = '';

        foreach ($segments as $index => $segment) {

            // skip "dashboard" if it exists in URL
            if ($segment === 'dashboard') {
                continue;
            }

            $url .= '/' . $segment;

            $name = $this->formatLabel($segment);

            $isLast = $index === array_key_last($segments);

            if ($isLast) {
                $breadcrumbs[] = [
                    'name' => $name,
                ];
            } else {
                $breadcrumbs[] = [
                    'name' => $name,
                    'href' => $url,
                ];
            }
        }

        Inertia::share('breadcrumbs', $breadcrumbs);

        return $next($request);
    }

    /**
     * Convert URL segment into readable label
     */
    private function formatLabel(string $segment): string
    {
        // handle IDs (e.g. /students/12/edit → "Student")
        if (is_numeric($segment)) {
            return 'Details';
        }

        return ucfirst(str_replace('-', ' ', $segment));
    }
}