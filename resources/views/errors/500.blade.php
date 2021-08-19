@extends('layouts.error', [
    'statusCode' => 500,
    'shortText' => 'Internal Server Error',
    'message' => 'The request couldn\'t be handled by the server'
])
