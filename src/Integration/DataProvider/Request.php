<?php
/**
 * Copyright (c) 2018 Alexander V. Basyrov, basyrov.av@gmail.com
 * Date: 10.07.18
 * Time: 1:44
 */
declare(strict_types=1);


namespace src\Integration\DataProvider;


/**
 * Запрос. Тоже вполне может быть POCO, если имеет простую структуру
 */
class Request
{
    // для определенности ввел несколько полей:
    public $searchString;
    public $offset;
    public $limit;
}
