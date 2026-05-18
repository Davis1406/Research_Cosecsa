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
    // Lead facilitator only
    Route::get('/trainees', 'MaterialsController@trainees')->name('trainees')->middleware('role:lead-facilitator');
});
