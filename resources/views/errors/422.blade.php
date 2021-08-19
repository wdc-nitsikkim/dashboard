@extends('layouts.error', [
    'statusCode' => 422,
    'shortText' => 'Unprocessable Entity',
    'message' => 'The request is semantically incorrect'
])
