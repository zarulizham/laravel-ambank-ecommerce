<?php

namespace ZarulIzham\EcommercePayment\Contracts;

interface Message
{
    /**
     * handle a message
     *
     * @param array $options
     * @return mixed
     */
    public function handle(array $options);
}
