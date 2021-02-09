<?php

use Analytics;
use Illuminate\Support\Str;
use Spatie\Analytics\Period;
use Illuminate\Support\Facades\Route;

Route::get('/visitor-page-view', function () {
    return Analytics::fetchVisitorsAndPageViews(Period::days(7));
});

Route::get('/total-visitor-page-view', function () {
    return Analytics::fetchTotalVisitorsAndPageViews(Period::days(7));
});

Route::get('/most-visited-page', function () {
    return Analytics::fetchMostVisitedPages(Period::days(7));
});

Route::get('/top-referrers', function () {
    return Analytics::fetchTopReferrers(Period::days(7), 20);
});

Route::get('/user-types', function () {
    return Analytics::fetchUserTypes(Period::days(7));
});

Route::get('/top-browser', function () {
    return Analytics::fetchTopBrowsers(Period::days(7), 10);
});

// More info https://ga-dev-tools.appspot.com/query-explorer/#report-end
Route::get('/from-query', function () {
    $data = Analytics::performQuery(
        Period::days(7),
        'ga:sessions,ga:sessionDuration,ga:pageViews,ga:newUsers',
        [
            'dimensions' => 'ga:source,ga:mobileDeviceInfo,ga:medium',
            'sort' => 'ga:newUsers',
        ]
    );

    $results = [];

    foreach ($data->rows as $key => $rows) {
        $results[$key] = [];
        for ($i = 0; $i < count($rows); $i++) {
            $headerTitle = (string)Str::of($data->columnHeaders[$i]->name)
                ->replace('ga:', '')
                ->snake()
                ->replace('_', ' ')
                ->title();

            $results[$key][$headerTitle] = $rows[$i];
        }
    }

    return $results;
});
