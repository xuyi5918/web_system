<?php
/**
 * Created by PhpStorm.
 * User: njj
 * Date: 2017/10/12
 * Time: 10:28
 */
include __DIR__ . DIRECTORY_SEPARATOR . 'RPC' . DIRECTORY_SEPARATOR . 'RPC_Client.php';
Class CI_Client extends RPC_Client
{
    public function __construct($uri)
    {
        parent::__construct($uri);
    }
}