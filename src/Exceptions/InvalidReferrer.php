<?php

namespace ZarulIzham\EcommercePayment\Exceptions;

use Exception;

class InvalidReferrer extends Exception
{
     /**
     * Report the exception.
     *
     * @return bool|null
     */
    public function report()
    {
        return true;
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View
     */
    public function render($request)
    {
        return view('ecommerce-payment::invalid_referer');
    }
}
