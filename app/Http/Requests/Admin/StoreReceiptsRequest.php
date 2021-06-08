<?php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreReceiptsRequest extends FormRequest
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
        return [
            'bank_name' => 'required_if:payment_mode,==,Depositar|required_if:payment_mode,==,Cheque',
            'issue_deposit_date' => 'required_if:payment_mode,==,Depositar|required_if:payment_mode,==,Cheque',
            'reference_number' => 'required_if:payment_mode,==,Depositar',
            'cheque_number' => 'required_if:payment_mode,==,Cheque',
            'total_payment' => 'required|numeric',
        ];
    }
}
