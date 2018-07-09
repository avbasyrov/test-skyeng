<?php
/**
 * Copyright (c) 2018 Alexander V. Basyrov, basyrov.av@gmail.com
 * Date: 10.07.18
 * Time: 1:58
 */
declare(strict_types=1);


namespace src\Integration\DataProvider;


interface ApiInterface
{
    /**
     * @param Request $requestParams
     * @return Reply
     */
    public function request(Request $requestParams): Reply;
}
