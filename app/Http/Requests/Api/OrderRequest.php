<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                    return [
                        'subject' => 'bail|required|max:255',
                        'amount' => 'bail|required|integer|min:1|max:1000000000',
                        'out_trade_no' => 'bail|required|max:64|min:10',
                        'channel' => ''
                        'body' => 'bail|max:128',
                        'seller_id' => 'max:255',
                        'buyer_id' => 'max:255',
                        'goods_detail' => 'bail'
                    ];
                break;
            
            default:
                # code...
                break;
        }
    }

    public function messages()
    {
        return [
            'subject.required' => '支付标题不能为空'
            'subject.max' => '支付标题最多支持255个字符',
        ];
    }
}
