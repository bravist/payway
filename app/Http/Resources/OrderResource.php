<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $prepay = json_decode($this->prepay->response);
        return [
            'trade_no' => $this->trade_no,
            'prepay_id' => $prepay->prepay_id
        ];
    }
}
