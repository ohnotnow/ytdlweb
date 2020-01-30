<?php

Route::get('/', 'HomeController@show')->name('home');

Route::get('/_healthz', 'HealthController@show')->name('health');