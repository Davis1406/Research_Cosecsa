<?php

Route::get('/', function () { return redirect('/login'); })->name('home');
Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
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
    Route::get('/manage/materials/{material}/edit', 'MaterialManagerController@edit')->name('material-manager.edit');
    Route::put('/manage/materials/{material}', 'MaterialManagerController@update')->name('material-manager.update');
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

    // Lead facilitator only — facilitators management
    Route::get('/facilitators', 'FacilitatorsController@index')->name('facilitators.index')->middleware('role:lead-facilitator');
    Route::get('/facilitators/create', 'FacilitatorsController@create')->name('facilitators.create')->middleware('role:lead-facilitator');
    Route::post('/facilitators', 'FacilitatorsController@store')->name('facilitators.store')->middleware('role:lead-facilitator');
    Route::get('/facilitators/{id}/edit', 'FacilitatorsController@edit')->name('facilitators.edit')->middleware('role:lead-facilitator');
    Route::put('/facilitators/{id}', 'FacilitatorsController@update')->name('facilitators.update')->middleware('role:lead-facilitator');
    Route::delete('/facilitators/{id}', 'FacilitatorsController@destroy')->name('facilitators.destroy')->middleware('role:lead-facilitator');
});
