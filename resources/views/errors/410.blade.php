@extends('layouts.error', [
    'statusCode' => 410,
    'shortText' => 'Gone',
    'message' => 'Link to the target resource has expired or moved permanently'
])
