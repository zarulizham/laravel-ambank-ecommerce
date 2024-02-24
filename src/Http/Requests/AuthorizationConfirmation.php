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
        $referrer = $this->headers->get('referer');

        if (config('app.env') == 'production') {
            $verified = str_starts_with($referrer, "https://3dgateway.ambankgroup.com/");
        } else {
            $verified = str_starts_with($referrer, "https://3dgatewaytest.ambankgroup.com/");
        }

        if (!$verified) {
            throw new InvalidReferrer();
        }
        return true;
    }
}
