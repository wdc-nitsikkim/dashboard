@extends('layouts.error', [
    'statusCode' => 401,
    'shortText' => 'Unauthorized',
    'message' => 'Authentication required'
])
