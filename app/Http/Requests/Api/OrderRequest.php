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
                    'out_trade_no' => 'bail|required|max:64',
                    'subject' => 'bail|required|max:255',
                    'amount' => 'bail|required|integer|min:1|max:1000000000',
                    'pay_way' => 'bail|required|string|exists:channel_pay_ways,way',
                    'detail' => 'bail|max:255',
                    'buyer' => 'max:255',
                    'seller' => 'max:255',
                    'body' => 'max:255',
                    'goods_detail' => 'max:255',
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
            'out_trade_no.required' => '外部订单号不能为空',
            'out_trade_no.max' => '外部订单号最大支持64个字符',
            'subject.required' => '支付标题不能为空',
            'subject.max' => '支付标题最大支持255个字符',
            'amount.required' => '订单金额最大支持255个字符',
            'amount.integer' => '订单金额仅支持数字',
            'amount.min' => '订单金额至少是0.01元',
            'pay_way.required' => '付款方式不能为空',
            'pay_way.string' => '付款方式格式不正确',
            'pay_way.exists' => '不支持该付款方式',
        ];
    }
}
