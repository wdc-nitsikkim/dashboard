@extends('layouts.error', [
    'statusCode' => 405,
    'shortText' => 'Method Not Allowed',
    'message' => 'Target resource does not support \'' . request()->method() . '\' method'
])
