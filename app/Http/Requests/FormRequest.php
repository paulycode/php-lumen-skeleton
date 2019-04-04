<?php

namespace App\Http\Requests;

use Illuminate\Validation\ValidatesWhenResolvedTrait;
use Illuminate\Contracts\Validation\ValidatesWhenResolved;
use Laravel\Lumen\Http\Request;

abstract class FormRequest extends Request implements ValidatesWhenResolved
{
    use ValidatesWhenResolvedTrait;

    /**
     * @var Illuminate\Contracts\Validation\Validator
     */
    protected $validator = null;

    /**
     *
     * @return array
     */
    public function fillkeys()
    {
        return array_keys($this->rules());
    }

    /**
     *
     * @return void
     */
    public function filldata()
    {
        return $this->validated();
    }

    /**
     *
     * @return Illuminate\Contracts\Validation\Validator
     */
    public function validator()
    {
        $this->validator = app('validator')->make($this->all(), $this->rules(), $this->messages(), $this->attributes());
        $this->validator->after(array($this, 'afterValidating'));

        return $this->validator;
    }

    /**
     *
     * @return array
     */
    public function validated()
    {
        return $this->validator->validated();
    }

    /**
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }

    /**
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     *
     * @return array
     */
    public function attributes()
    {
        return [];
    }

    /**
     *
     * @return void
     */
    public function afterValidating($validator)
    {
    }
}
