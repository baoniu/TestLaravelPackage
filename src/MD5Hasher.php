<?php

/**
 * Created by hare.
 * Email: 688977@qq.com
 * Date: 2017/1/21
 * Time: PM7:40
 */
namespace TestHelper\Hasher;
/**
 * Class MD5Hasher
 * @package TestHelper\Hasher
 */
class MD5Hasher
{
    /**
     * @param $value
     * @param array $options
     * @return string
     */
    public function make($value, array $options = [])
    {
        $salt = isset($options['salt']) ? $options['salt'] : '';
        return hash('md5', $value . $salt);
    }

    /**
     * @param $value
     * @param $hashValue
     * @param array $options
     * @return bool
     */
    public function check($value, $hashValue, $options = [])
    {
        return $this->make($value, $options) === $hashValue;
    }
}