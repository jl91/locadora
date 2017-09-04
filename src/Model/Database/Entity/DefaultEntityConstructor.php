<?php

namespace VideoPlace\Model\Database\Entity;


trait DefaultEntityConstructor
{

    public function __construct(array $data = [])
    {
        if (!empty($data)) {
            foreach ($data as $key => $value) {
                $normalizedKey = $this->normalizedParamsName($key);
                $method = 'set' . $normalizedKey;

                if (!method_exists($this, $method)) {
                    throw new \InvalidArgumentException("Param {$key} does not exists on Entity : " . __CLASS__, 500);
                }
                $this->$method($value);
            }
        }
    }

    private function normalizedParamsName($param)
    {
        $param = ucfirst($param);
        if (strpos($param, '_') !== false) {
            $pieces = explode('_', $param);

            array_walk($pieces, function ($value, $index) use ($pieces) {
                $pieces[$index] = ucfirst($value);
            });

            $param = implode('', $pieces);
        }
        return $param;
    }

    private function desnormalizeParamsName($param)
    {
        $pieces = preg_split('/(?<=\\w)(?=[A-Z])/', $param);
        foreach ($pieces as $key => &$value) {
            $value = strtolower($value);
        }

        return implode('_', $pieces);
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }

    public function extract(): array
    {

        $data = $this->toArray();

        foreach ($data as $key => $value) {
            $currentKey = $key;

            $denormalizedData = $this->desnormalizeParamsName($key);

            if ($value instanceof \DateTimeInterface) {
                $value = $value->format('Y:m:d H:i:s');
            }

            $data[$denormalizedData] = $value;

            if (preg_match('/[A-Z]/', $currentKey)) {
                unset($data[$currentKey]);
            }
        }

        return $data;
    }
}