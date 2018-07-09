<?php
/**
 * Copyright (c) 2018 Alexander V. Basyrov, basyrov.av@gmail.com
 * Date: 10.07.18
 * Time: 1:43
 */
declare(strict_types=1);


namespace src\Integration\DataProvider;


/**
 * @implements ApiInterface
 */
class Api implements ApiInterface
{
    /** @var Credentials $credentials */
    private $credentials;

    /**
     * @param Credentials $credentials
     */
    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * {@inheritdoc}
     */
    public function request(Request $requestParams): Reply
    {
        // ...
        // тут какой-то вызов к внешнему сервису
        // ...

        return new Reply();
    }
}
