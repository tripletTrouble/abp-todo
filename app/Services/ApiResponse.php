<?php

namespace App\Services;
use Illuminate\Database\Eloquent;

class ApiResponse
{
    public object | null $data;
    public string $status;
    public string $message;

    public function __construct(
        object | null $data = null,
        ?string $status = 'Success',
        ?string $message = 'Success'
    )
    {
        $this->data = $data;
        $this->status = $status;
        $this->message = $message;

        if (empty($data)) {
            unset($this->data);
        }
    }
}
