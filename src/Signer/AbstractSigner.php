<?php

namespace Liyu\Signature\Signer;

abstract class AbstractSigner
{
    /**
     * convert sign data.
     *
     * @param mixed $signData
     *
     * @return string
     */
    public function getSignString($signData)
    {
        if (!is_array($signData)) {
            return $signData;
        }

        $params = $this->multiksort($signData);

        return json_encode($params);
    }

    /**
     * Deep ksort array.
     *
     * @param mixed $params
     *
     * @return array
     */
    protected function multiksort(&$params)
    {
        if (is_array($params)) {
            $params = array_filter($params);
            ksort($params);
            array_walk($params, [$this, 'multiksort']);
        } else {
            // convert item to tring
            $params = (string)$params;
        }

        return $params;
    }

    /**
     * setConfig.
     *
     * @param array $config
     *
     * @return $this
     */
    public function setConfig(array $config)
    {
        foreach ($config as $key => $value) {
            $method = 'set'.ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }

        return $this;
    }
}
