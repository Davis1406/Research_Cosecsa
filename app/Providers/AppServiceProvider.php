<?php

namespace App\Providers;

use App\TrainingMaterial;
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

            $since = now()->subDays(7);

            $materials = TrainingMaterial::latest()->take(10)->get()->map(fn($m) => [
                'type'  => 'material',
                'title' => $m->title,
                'sub'   => ucfirst($m->type) . ($m->category ? ' · ' . $m->category : ''),
                'time'  => $m->created_at,
                'icon'  => 'fa-book-open',
                'color' => '#C9A84C',
                'new'   => $m->created_at->gte($since),
                'url'   => route('material.view', $m->id),
            ]);

            $comments = TraineeDocumentComment::with('user', 'document.trainee')
                ->latest()->take(10)->get()->map(fn($c) => [
                    'type'  => 'comment',
                    'title' => ($c->user?->name ?? 'Someone') . ' left feedback',
                    'sub'   => $c->document?->trainee?->name
                                   ? 'On ' . $c->document->trainee->name . '\'s presentation'
                                   : 'On a presentation',
                    'time'  => $c->created_at,
                    'icon'  => 'fa-comment-alt',
                    'color' => '#2c7a4b',
                    'new'   => $c->created_at->gte($since),
                    'url'   => route('facilitator.presentations.view', $c->trainee_document_id),
                ]);

            $notifItems = $materials->merge($comments)
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
