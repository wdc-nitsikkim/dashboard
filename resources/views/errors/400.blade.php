@extends('layouts.error', [
    'statusCode' => 400,
    'shortText' => 'Bad Request',
    'message' => 'Server could not understand the request'
])
