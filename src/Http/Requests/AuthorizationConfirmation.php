<?php

namespace ZarulIzham\EcommercePayment\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use ZarulIzham\EcommercePayment\Exceptions\InvalidReferrer;
use ZarulIzham\EcommercePayment\Messages\AuthorizationConfirmation as AuthorizationConfirmationMessage;

class AuthorizationConfirmation extends FormRequest
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
        return [];
    }

    /**
     * Presist the data to the users table
     */
    public function handle()
    {
        $this->verifyReferrer();
        $data = $this->all();

        return (new AuthorizationConfirmationMessage())->handle($data);
    }

    public function headers()
    {
        return $this->headers->all();
    }

    public function getHeader($key)
    {
        return $this->headers->get($key);
    }

    protected function verifyReferrer()
    {
        if (! in_array($this->headers->get('referer'), ["https://3dgatewaytest.ambankgroup.com/","https://3dgateway.ambankgroup.com/"])) {
            throw new InvalidReferrer();
        }
    }
}
