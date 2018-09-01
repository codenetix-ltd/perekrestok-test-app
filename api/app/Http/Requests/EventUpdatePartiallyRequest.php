<?php

namespace App\Http\Requests;

use App\Services\EventServiceInterface;
use Illuminate\Database\Eloquent\Model;

class EventUpdatePartiallyRequest extends ABaseAPIRequest
{
    /**
     * @return boolean
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'is_viewed' => 'sometimes|bool',
            'is_hidden' => 'sometimes|bool'
        ];
    }

    /**
     * @param EventServiceInterface $service
     * @return mixed
     */
    public function getTargetModel(EventServiceInterface $service): Model
    {
        return $service->find($this->route()->parameter('event'));
    }
}
