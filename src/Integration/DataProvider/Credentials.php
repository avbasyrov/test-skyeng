<?php
/**
 * Copyright (c) 2018 Alexander V. Basyrov, basyrov.av@gmail.com
 * Date: 10.07.18
 * Time: 1:41
 */
declare(strict_types=1);


namespace src\Integration\DataProvider;


/**
 * Параметры доступа к внешнему сервису DataProvider.
 * Вполне подходит просто POCO
 */
class Credentials
{
    public $host;
    public $user;
    public $password;
}
