<?php

namespace App\Http\Requests;

class UpdateEventRequest extends StoreEventRequest
{
    // Same rules as creating an event — updating requires the full payload.
}
