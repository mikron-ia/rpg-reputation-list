<?php

namespace Mikron\ReputationList\Domain\Service;

/**
 * Class Output - packs and presents output
 *
 * @package Mikron\ReputationList\Domain\ValueObject
 */
final class Output
{
    private $pack;

    /**
     * Output constructor.
     * @param string $title
     * @param string $description
     * @param array $content
     */
    public function __construct($title, $description, array $content)
    {
        $this->pack = [
            "title" => $title,
            "description" => $description,
            "content" => $content
        ];
    }

    /**
     * @return mixed
     */
    public function getArrayForJson()
    {
        return $this->pack;
    }
}
