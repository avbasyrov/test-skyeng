<?php
/**
 * Copyright (c) 2018 Alexander V. Basyrov, basyrov.av@gmail.com
 * Date: 10.07.18
 * Time: 1:49
 */
declare(strict_types=1);


namespace src\Integration\DataProvider;


/**
 * Ответ сервиса. Положим, что тоже имеет простую структуру, поэтому POCO.
 */
class Reply
{
    // поля для определенности:

    /** @var array $foundItems */
    public $foundItems;

    /** @var int $foundItems */
    public $totalItems;
}
