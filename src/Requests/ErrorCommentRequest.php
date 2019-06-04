<?php

namespace Eslym\ErrorReport\Requests;

use Eslym\ErrorReport\Model\ErrorComment;
use Illuminate\Foundation\Http\FormRequest;

class ErrorCommentRequest extends FormRequest
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
            'error_id' => 'required|exists:error_records,id',
            'email' => 'email',
            'content' => 'required|string|size:10,1000',
        ];
    }

    public function commit(){
        $data = $this->only(['error_id', 'email', 'content']);
        if(isset($data['email']) && empty($data['email'])){
            $data['email'] = null;
        }
        ErrorComment::create($data);
    }
}
