@extends('layouts.error', [
    'statusCode' => 403,
    'shortText' => 'Forbidden',
    'message' => 'You are not authorized to view this page'
])
