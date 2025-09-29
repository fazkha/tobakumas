<?php

use App\Models\Notif;

function notif_count()
{
    // return Notif::where('isactive', 1)->count();
    return Notif::count();
}

function notif_data()
{
    // return Notif::where('isactive', 1)->get();
    return Notif::get();
}

function elapsed_interval(string $date1_str, string $date2_str)
{
    $timestamp1 = strtotime($date1_str . ' 00:00:00');
    $timestamp2 = strtotime($date2_str);

    $difference_seconds = round($timestamp2 - $timestamp1, 1);
    $difference_minutes = round($difference_seconds / 60, 1);
    $difference_hours = round($difference_seconds / (60 * 60), 1);
    $difference_days = round($difference_seconds / (60 * 60 * 24), 1);

    return $difference_days >= 1 ? $difference_days . ' day(s)' : ($difference_hours >= 1 ? $difference_hours . ' hour(s)' : ($difference_minutes >= 1 ? $difference_minutes . ' minutes(s)' : $difference_seconds . ' second(s)'));
}
