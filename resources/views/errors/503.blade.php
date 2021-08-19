@extends('layouts.error', [
    'statusCode' => 503,
    'shortText' => 'Service Unavailable',
    'message' => 'Server returned an invalid response'
])
