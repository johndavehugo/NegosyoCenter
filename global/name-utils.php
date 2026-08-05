<?php

function splitFullName(string $fullName): array
{
    $fullName = trim(preg_replace('/\s+/', ' ', $fullName));
    if ($fullName === '') return ['first_name' => '', 'middle_name' => '', 'last_name' => ''];

    if (strpos($fullName, ',') !== false) {
        [$last, $rest] = array_map('trim', explode(',', $fullName, 2));
        $parts = $rest === '' ? [] : explode(' ', $rest);
    } else {
        $parts = explode(' ', $fullName);
    }

    if (count($parts) >= 3 && preg_match('/^(jr|sr|i{1,3}|iv|v)\.?$/i', end($parts))) {
        $suffix = array_pop($parts);
        $last = ($last ?? array_pop($parts)) . ' ' . $suffix;
    } elseif (!isset($last)) {
        $last = array_pop($parts) ?? '';
    }

    $first = array_shift($parts) ?? '';
    $middle = implode(' ', $parts);
    return ['first_name' => $first, 'middle_name' => $middle, 'last_name' => $last];
}