<?php

namespace VideoPlace\Model\View\Helper;


use VideoPlace\Model\Database\Entity\UserEntity;

class UserTableBodyHelper implements HelperInterface
{

    public static function parse($data): string
    {
        $template = '';

        /**
         * @var $value UserEntity
         */
        foreach ($data as $value) {
            $template .= "<tr>" . PHP_EOL;
            $template .= "  <td>" . PHP_EOL;
            $template .= "  {$value->getId()}" . PHP_EOL;
            $template .= "  </td>" . PHP_EOL;

            $template .= "  <td>" . PHP_EOL;
            $template .= "  {$value->getName()}" . PHP_EOL;
            $template .= "  </td>" . PHP_EOL;

            $template .= "  <td>" . PHP_EOL;
            $template .= "  {$value->getEmail()}" . PHP_EOL;
            $template .= "  </td>" . PHP_EOL;

            $template .= "  <td>" . PHP_EOL;
            $template .= "  {$value->getCreatedAt()->format('d/m/Y H:i:s')}" . PHP_EOL;
            $template .= "  </td>" . PHP_EOL;

            $template .= "  <td>" . PHP_EOL;
            $template .= " <a href='/users/delete?id={$value->getId()}'> apagar </a>" . PHP_EOL;
            $template .= " |" . PHP_EOL;
            $template .= " <a href='/users/read?id={$value->getId()}'> ver </a>" . PHP_EOL;
            $template .= "  </td>" . PHP_EOL;

            $template .= "</tr>" . PHP_EOL;
        }

        return $template;
    }

}