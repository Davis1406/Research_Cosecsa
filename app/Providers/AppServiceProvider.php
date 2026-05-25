<?php

namespace App\Providers;

use App\TrainingMaterial;
use App\TraineeDocument;
use App\TraineeDocumentComment;
use App\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register() {}

    public function boot()
    {
        // Shared notification builder
        $buildNotifications = function () {
            if (!Auth::check()) {
                return ['notifItems' => collect(), 'notifCount' => 0];
            }

            $user      = Auth::user();
            $window    = now()->subDays(7);
            $readIds   = json_decode($user->notifications_read_ids ?? '{}', true) ?: [];
            $isAdmin   = $user->roles->pluck('title')
                             ->map(fn($t) => strtolower(trim($t)))
                             ->contains('admin');

            // An item is "new" when it is within the 7-day window AND its key
            // has not been individually marked as read by this user.
            $isNew = fn(string $key, $time) => $time->gte($window) && !isset($readIds[$key]);

            $materials = TrainingMaterial::latest()->take(10)->get()->map(fn($m) => [
                'key'   => 'material_' . $m->id,
                'type'  => 'material',
                'title' => $m->title,
                'sub'   => ucfirst($m->type) . ($m->category ? ' · ' . $m->category : ''),
                'time'  => $m->created_at,
                'icon'  => 'fa-book-open',
                'color' => '#C9A84C',
                'new'   => $isNew('material_' . $m->id, $m->created_at),
                'url'   => route('material.view', $m->id),
            ]);

            $comments = TraineeDocumentComment::with('user', 'document.trainee')
                ->latest()->take(10)->get()->map(fn($c) => [
                    'key'   => 'comment_' . $c->id,
                    'type'  => 'comment',
                    'title' => ($c->user?->name ?? 'Someone') . ' left feedback',
                    'sub'   => $c->document?->trainee?->name
                                   ? 'On ' . $c->document->trainee->name . '\'s presentation'
                                   : 'On a presentation',
                    'time'  => $c->created_at,
                    'icon'  => 'fa-comment-alt',
                    'color' => '#2c7a4b',
                    'new'   => $isNew('comment_' . $c->id, $c->created_at),
                    'url'   => $isAdmin
                                   ? route('admin.presentations.view', $c->trainee_document_id)
                                   : route('facilitator.presentations.view', $c->trainee_document_id),
                ]);

            $uploads = TraineeDocument::with('trainee')->latest()->take(10)->get()->map(fn($doc) => [
                'key'   => 'upload_' . $doc->id,
                'type'  => 'upload',
                'title' => ($doc->trainee?->name ?? 'A trainee') . ' uploaded a document',
                'sub'   => trim(($doc->document_type ? ucfirst($doc->document_type) : '') . ($doc->title ? ' · ' . $doc->title : '')),
                'time'  => $doc->created_at,
                'icon'  => 'fa-file-upload',
                'color' => '#2d6fa8',
                'new'   => $isNew('upload_' . $doc->id, $doc->created_at),
                'url'   => $isAdmin
                               ? route('admin.presentations.view', $doc->id)
                               : route('facilitator.presentations.view', $doc->id),
            ]);

            $notifItems = $materials->merge($comments)->merge($uploads)
                ->sortByDesc('time')
                ->take(15)
                ->values();

            $notifCount = $notifItems->where('new', true)->count();

            return compact('notifItems', 'notifCount');
        };

        // Inject notifications into the facilitator layout for every request
        View::composer('layouts.facilitator', function ($view) use ($buildNotifications) {
            if (!Auth::check()) {
                $view->with(['notifItems' => collect(), 'notifCount' => 0, 'unreadMessages' => 0]);
                return;
            }

            $isLead = Auth::user()->roles->pluck('title')->contains('Lead Facilitator');

            $unreadMessages = Message::where('receiver_id', Auth::id())->whereNull('read_at')->count();

            if (!$isLead) {
                $view->with(['notifItems' => collect(), 'notifCount' => 0, 'unreadMessages' => $unreadMessages]);
                return;
            }

            $data = $buildNotifications();
            $data['unreadMessages'] = $unreadMessages;
            $view->with($data);
        });

        // Inject notifications into the admin layout
        View::composer('layouts.admin', function ($view) use ($buildNotifications) {
            $view->with($buildNotifications());
        });
    }
}
