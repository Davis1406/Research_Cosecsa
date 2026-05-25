<?php

Route::get('/', function () { return redirect('/login'); })->name('home');
Auth::routes(['register' => false]);

// Extra HTTP-level rate limit on login (on top of Laravel's built-in ThrottleLogins)
Route::post('/login', '\App\Http\Controllers\Auth\LoginController@login')->middleware('throttle:5,1');

// Force-password-change page — any authenticated user, bypasses role checks
Route::get('/change-password',  'Auth\ChangePasswordController@show')->name('change-password.show')->middleware('auth');
Route::post('/change-password', 'Auth\ChangePasswordController@update')->name('change-password.update')->middleware('auth');

// /home — smart redirect to role dashboard
Route::get('/home', function () {
    if (!auth()->check()) {
        return redirect('/login');
    }
    $roles = auth()->user()->roles->pluck('title');
    if ($roles->contains('Super Admin') || $roles->contains('Admin')) {
        return redirect('/admin');
    }
    if ($roles->contains('Lead Facilitator') || $roles->contains('Facilitator')) {
        return redirect('/facilitator');
    }
    if ($roles->contains('Trainee')) {
        return redirect('/trainee');
    }
    if ($roles->contains('Viewer')) {
        return redirect('/viewer');
    }
    return redirect('/admin');
})->middleware('auth')->name('dashboard');

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth', 'role:admin,super-admin,facilitator,lead-facilitator,trainee']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::post('users/{user}/reset-password', 'UsersController@resetPassword')->name('users.resetPassword');
    Route::resource('users', 'UsersController');

    // Settings
    Route::delete('settings/destroy', 'SettingsController@massDestroy')->name('settings.massDestroy');
    Route::resource('settings', 'SettingsController');

    // Speakers
    Route::delete('speakers/destroy', 'SpeakersController@massDestroy')->name('speakers.massDestroy');
    Route::post('speakers/media', 'SpeakersController@storeMedia')->name('speakers.storeMedia');
    Route::resource('speakers', 'SpeakersController');

    // Timetable (Schedules)
    Route::delete('schedules/destroy', 'ScheduleController@massDestroy')->name('schedules.massDestroy');
    Route::post('schedules/{schedule}/toggle-complete', 'ScheduleController@toggleComplete')->name('schedules.toggleComplete');
    Route::resource('schedules', 'ScheduleController');

    // Trainees
    Route::delete('trainees/destroy', 'TraineesController@massDestroy')->name('trainees.massDestroy');
    Route::resource('trainees', 'TraineesController');

    // Training Materials
    Route::delete('training-materials/destroy', 'TrainingMaterialsController@massDestroy')->name('training-materials.massDestroy');
    Route::post('training-materials/media', 'TrainingMaterialsController@storeMedia')->name('training-materials.storeMedia');
    Route::get('training-materials/{trainingMaterial}/viewer', 'TrainingMaterialsController@viewer')->name('training-materials.viewer');
    Route::get('training-materials/{trainingMaterial}/render-slides', 'TrainingMaterialsController@renderSlides')->name('training-materials.renderSlides');
    Route::resource('training-materials', 'TrainingMaterialsController');

    // Venues (kept for backward compatibility)
    Route::delete('venues/destroy', 'VenuesController@massDestroy')->name('venues.massDestroy');
    Route::post('venues/media', 'VenuesController@storeMedia')->name('venues.storeMedia');
    Route::resource('venues', 'VenuesController');

    // Hotels
    Route::delete('hotels/destroy', 'HotelsController@massDestroy')->name('hotels.massDestroy');
    Route::post('hotels/media', 'HotelsController@storeMedia')->name('hotels.storeMedia');
    Route::resource('hotels', 'HotelsController');

    // Galleries
    Route::delete('galleries/destroy', 'GalleriesController@massDestroy')->name('galleries.massDestroy');
    Route::post('galleries/media', 'GalleriesController@storeMedia')->name('galleries.storeMedia');
    Route::resource('galleries', 'GalleriesController');

    // Sponsors
    Route::delete('sponsors/destroy', 'SponsorsController@massDestroy')->name('sponsors.massDestroy');
    Route::post('sponsors/media', 'SponsorsController@storeMedia')->name('sponsors.storeMedia');
    Route::resource('sponsors', 'SponsorsController');

    // Faqs
    Route::delete('faqs/destroy', 'FaqsController@massDestroy')->name('faqs.massDestroy');
    Route::resource('faqs', 'FaqsController');

    // Amenities
    Route::delete('amenities/destroy', 'AmenitiesController@massDestroy')->name('amenities.massDestroy');
    Route::resource('amenities', 'AmenitiesController');

    // Prices
    Route::delete('prices/destroy', 'PricesController@massDestroy')->name('prices.massDestroy');
    Route::resource('prices', 'PricesController');

    // Quizzes
    Route::post('quizzes/{quiz}/toggle-publish', 'QuizzesController@togglePublish')->name('quizzes.toggle-publish');
    Route::get('quizzes/{quiz}/results', 'QuizzesController@show')->name('quizzes.results');
    Route::resource('quizzes', 'QuizzesController');

    // Discussions
    Route::post('discussions/{discussion}/reply', 'DiscussionsController@reply')->name('discussions.reply');
    Route::resource('discussions', 'DiscussionsController', ['only' => ['index', 'show', 'destroy']]);

    // Messages / Chat (admin)
    Route::get('messages', 'MessagesController@index')->name('messages.index');
    Route::get('messages/thread/{user}', 'MessagesController@thread')->name('messages.thread');
    Route::post('messages/thread/{user}/send', 'MessagesController@send')->name('messages.send');
    Route::get('messages/thread/{user}/poll', 'MessagesController@poll')->name('messages.poll');
    // Admin read-only view of conversations between other users
    Route::get('messages/view/{sender}/{receiver}', 'MessagesController@viewThread')->name('messages.view-thread');
    // Soft-delete an entire conversation thread
    Route::delete('messages/thread/delete', 'MessagesController@deleteThread')->name('messages.delete-thread');
    Route::get('messages/{message}', 'MessagesController@show')->name('messages.show');
    Route::delete('messages/{message}', 'MessagesController@destroy')->name('messages.destroy');

    // Certificates
    Route::get('certificates/{certificate}/preview', 'CertificatesController@preview')->name('certificates.preview');
    Route::resource('certificates', 'CertificatesController', ['only' => ['index', 'create', 'store', 'destroy']]);

    // Trainee presentation review (admin can view + comment)
    Route::get('presentations/{document}', 'PresentationsController@view')->name('presentations.view');
    Route::post('presentations/{document}/comment', 'PresentationsController@comment')->name('presentations.comment');

    // Facilitator Directory (admin view)
    Route::get('directory', 'DirectoryController@index')->name('directory.index');
    Route::get('directory/{speaker}', 'DirectoryController@show')->name('directory.show');
});

// Shared material viewer — accessible to any authenticated user (no admin gate)
Route::get('/material/{material}/view', 'MaterialViewerController@show')
     ->name('material.view')
     ->middleware('auth');
Route::get('/material/{material}/render-slides', 'MaterialViewerController@renderSlides')
     ->name('material.render-slides')
     ->middleware('auth');
Route::get('/trainee-document/{document}/render-slides', 'MaterialViewerController@renderTraineeSlides')
     ->name('trainee-document.render-slides')
     ->middleware('auth');

// Mark a single notification item as read (shared — admin & lead facilitator)
Route::post('/notifications/mark-item-read', function (\Illuminate\Http\Request $request) {
    $key  = preg_replace('/[^a-z0-9_]/', '', $request->input('key', ''));
    if (!$key) return response()->json(['ok' => false], 422);

    $user    = auth()->user();
    $readIds = json_decode($user->notifications_read_ids ?? '{}', true) ?: [];
    $readIds[$key] = 1;
    $user->update(['notifications_read_ids' => json_encode($readIds)]);
    return response()->json(['ok' => true]);
})->name('notifications.mark-item-read')->middleware('auth');

// ── Viewer Portal ─────────────────────────────────────────────────
Route::prefix('viewer')->name('viewer.')->namespace('Viewer')->middleware(['auth', 'role:viewer'])->group(function () {
    Route::get('/', 'ViewerController@dashboard')->name('dashboard');
    Route::get('/facilitators', 'ViewerController@facilitators')->name('facilitators');
    Route::get('/trainees', 'ViewerController@trainees')->name('trainees');
    Route::get('/timetable', 'ViewerController@timetable')->name('timetable');
    Route::get('/materials', 'MaterialsController@index')->name('materials');
    Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
});

// ── Trainee Portal ────────────────────────────────────────────────
Route::prefix('trainee')->name('trainee.')->namespace('Trainee')->middleware(['auth', 'role:trainee'])->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/timetable', 'TimetableController@index')->name('timetable');
    Route::get('/materials', 'MaterialsController@index')->name('materials');
    Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
    Route::get('/documents', 'DocumentsController@index')->name('documents.index');
    Route::post('/documents', 'DocumentsController@store')->name('documents.store');
    Route::delete('/documents/{document}', 'DocumentsController@destroy')->name('documents.destroy');
});

// ── Facilitator Portal ────────────────────────────────────────────
Route::prefix('facilitator')->name('facilitator.')->namespace('Facilitator')->middleware(['auth', 'role:facilitator,lead-facilitator'])->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/timetable', 'TimetableController@index')->name('timetable');
    Route::get('/materials', 'MaterialsController@index')->name('materials');
    Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
    // All facilitators — materials management (delete is lead-only)
    Route::get('/manage/materials', 'MaterialManagerController@index')->name('material-manager.index');
    Route::get('/manage/materials/create', 'MaterialManagerController@create')->name('material-manager.create');
    Route::post('/manage/materials', 'MaterialManagerController@store')->name('material-manager.store');
    Route::post('/manage/materials/media', 'MaterialManagerController@storeMedia')->name('material-manager.storeMedia');
    Route::get('/manage/materials/{material}/edit', 'MaterialManagerController@edit')->name('material-manager.edit');
    Route::put('/manage/materials/{material}', 'MaterialManagerController@update')->name('material-manager.update');
    Route::post('/manage/materials/{material}/replace-file', 'MaterialManagerController@replaceFile')->name('material-manager.replaceFile');
    Route::delete('/manage/materials/{material}', 'MaterialManagerController@destroy')->name('material-manager.destroy')->middleware('role:lead-facilitator');

    // Lead facilitator only — timetable/schedule management
    Route::get('/manage/timetable', 'ScheduleManagerController@index')->name('schedule-manager.index')->middleware('role:lead-facilitator');
    Route::get('/manage/timetable/create', 'ScheduleManagerController@create')->name('schedule-manager.create')->middleware('role:lead-facilitator');
    Route::post('/manage/timetable', 'ScheduleManagerController@store')->name('schedule-manager.store')->middleware('role:lead-facilitator');
    Route::get('/manage/timetable/{session}/edit', 'ScheduleManagerController@edit')->name('schedule-manager.edit')->middleware('role:lead-facilitator');
    Route::put('/manage/timetable/{session}', 'ScheduleManagerController@update')->name('schedule-manager.update')->middleware('role:lead-facilitator');
    Route::delete('/manage/timetable/{session}', 'ScheduleManagerController@destroy')->name('schedule-manager.destroy')->middleware('role:lead-facilitator');
    Route::post('/manage/timetable/{session}/toggle', 'ScheduleManagerController@toggleComplete')->name('schedule-manager.toggle')->middleware('role:lead-facilitator');

    // Lead facilitator only — trainees management
    Route::get('/trainees', 'TraineesController@index')->name('trainees')->middleware('role:lead-facilitator');
    Route::get('/trainees/create', 'TraineesController@create')->name('trainees.create')->middleware('role:lead-facilitator');
    Route::post('/trainees', 'TraineesController@store')->name('trainees.store')->middleware('role:lead-facilitator');
    Route::get('/trainees/{id}/edit', 'TraineesController@edit')->name('trainees.edit')->middleware('role:lead-facilitator');
    Route::put('/trainees/{id}', 'TraineesController@update')->name('trainees.update')->middleware('role:lead-facilitator');
    Route::delete('/trainees/{id}', 'TraineesController@destroy')->name('trainees.destroy')->middleware('role:lead-facilitator');

    // All facilitators — trainee presentations review + comments
    Route::get('/presentations', 'PresentationsController@index')->name('presentations.index');
    Route::get('/presentations/{document}', 'PresentationsController@view')->name('presentations.view');
    Route::post('/presentations/{document}/comment', 'PresentationsController@comment')->name('presentations.comment');
    Route::post('/presentations/{document}/assign-reviewers', 'PresentationsController@assignReviewers')->name('presentations.assign-reviewers')->middleware('role:lead-facilitator');

    // Lead facilitator only — facilitators management
    Route::get('/facilitators', 'FacilitatorsController@index')->name('facilitators.index')->middleware('role:lead-facilitator');
    Route::get('/facilitators/create', 'FacilitatorsController@create')->name('facilitators.create')->middleware('role:lead-facilitator');
    Route::post('/facilitators', 'FacilitatorsController@store')->name('facilitators.store')->middleware('role:lead-facilitator');
    Route::get('/facilitators/{id}/edit', 'FacilitatorsController@edit')->name('facilitators.edit')->middleware('role:lead-facilitator');
    Route::put('/facilitators/{id}', 'FacilitatorsController@update')->name('facilitators.update')->middleware('role:lead-facilitator');
    Route::delete('/facilitators/{id}', 'FacilitatorsController@destroy')->name('facilitators.destroy')->middleware('role:lead-facilitator');

    // Directory (all facilitators)
    Route::get('/directory', 'DirectoryController@index')->name('directory.index');
    Route::get('/directory/{speaker}', 'DirectoryController@show')->name('directory.show');

    // Quizzes
    Route::get('/quizzes', 'QuizController@index')->name('quizzes.index');
    Route::get('/quizzes/create', 'QuizController@create')->name('quizzes.create');
    Route::post('/quizzes', 'QuizController@store')->name('quizzes.store');
    Route::get('/quizzes/{quiz}/edit', 'QuizController@edit')->name('quizzes.edit');
    Route::get('/quizzes/{quiz}/results', 'QuizController@results')->name('quizzes.results');
    Route::post('/quizzes/{quiz}/toggle-publish', 'QuizController@togglePublish')->name('quizzes.toggle-publish');
    Route::get('/quizzes/{quiz}', 'QuizController@show')->name('quizzes.show');
    Route::put('/quizzes/{quiz}', 'QuizController@update')->name('quizzes.update');
    Route::delete('/quizzes/{quiz}', 'QuizController@destroy')->name('quizzes.destroy');

    // Discussions
    Route::get('/discussions', 'DiscussionController@index')->name('discussions.index');
    Route::get('/discussions/create', 'DiscussionController@create')->name('discussions.create');
    Route::post('/discussions', 'DiscussionController@store')->name('discussions.store');
    Route::post('/discussions/{discussion}/reply', 'DiscussionController@reply')->name('discussions.reply');
    Route::delete('/discussions/{discussion}', 'DiscussionController@destroy')->name('discussions.destroy');
    Route::get('/discussions/{discussion}', 'DiscussionController@show')->name('discussions.show');

    // Messages / Chat
    Route::get('/messages', 'MessagesController@index')->name('messages.index');
    Route::get('/messages/compose', 'MessagesController@compose')->name('messages.compose');
    Route::post('/messages', 'MessagesController@store')->name('messages.store');
    Route::get('/messages/thread/{user}', 'MessagesController@thread')->name('messages.thread');
    Route::post('/messages/thread/{user}/send', 'MessagesController@send')->name('messages.send');
    Route::get('/messages/thread/{user}/poll', 'MessagesController@poll')->name('messages.poll');
    Route::get('/messages/{message}', 'MessagesController@show')->name('messages.show');
    Route::delete('/messages/{message}', 'MessagesController@destroy')->name('messages.destroy');

    // Certificates (lead only)
    Route::get('/certificates', 'CertificateController@index')->name('certificates.index')->middleware('role:lead-facilitator');
    Route::get('/certificates/create', 'CertificateController@create')->name('certificates.create')->middleware('role:lead-facilitator');
    Route::post('/certificates', 'CertificateController@store')->name('certificates.store')->middleware('role:lead-facilitator');
    Route::get('/certificates/{certificate}/preview', 'CertificateController@preview')->name('certificates.preview');
    Route::delete('/certificates/{certificate}', 'CertificateController@destroy')->name('certificates.destroy')->middleware('role:lead-facilitator');
});
