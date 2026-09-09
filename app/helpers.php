<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('course_type')) {
    /**
     * Resolve the currently active course instance (physical|online) for the
     * viewing staff member (Admin/Facilitator/Viewer). Resolution order:
     *   1. An explicit switch on this request (?switch_course=... or the
     *      /switch-course/{type} route already having primed the session)
     *   2. The value remembered in the session for this browser
     *   3. The signed-in user's remembered preference (survives across logins/devices)
     *   4. The app default from config('courses.default')
     *
     * Trainees are NOT expected to call this — their course is fixed by
     * their own enrollment (Trainee::course_type), not a switchable filter.
     */
    function course_type(): string
    {
        static $resolved = null;

        if ($resolved !== null) {
            return $resolved;
        }

        $types = array_keys(config('courses.types', []));

        $switch = request('switch_course');
        if ($switch && in_array($switch, $types, true)) {
            return $resolved = $switch;
        }

        $sessionValue = session('course_type');
        if ($sessionValue && in_array($sessionValue, $types, true)) {
            return $resolved = $sessionValue;
        }

        if (Auth::check()) {
            $preferred = Auth::user()->preferred_course_type;
            if ($preferred && in_array($preferred, $types, true)) {
                return $resolved = $preferred;
            }
        }

        return $resolved = config('courses.default', 'physical');
    }
}
