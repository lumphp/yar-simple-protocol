<?php
namespace Lum\Service;

/**
 * Class TestService
 *
 * @package Lum\src
 */
class TestService
{
    /**
     * @param $params
     *
     * @return string
     */
    public function query($params)
    {
        return sprintf('query %s(%s)', __FUNCTION__, $params);
    }
}