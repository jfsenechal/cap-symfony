<?php

namespace Cap\Commercio\Mailer;

class MandrillMailDataItem
{
    public function __construct(private $name, private $content)
    {
    }

    public function toArray()
    {
        return ["name" => $this->name, "content" => $this->content];
    }
}
