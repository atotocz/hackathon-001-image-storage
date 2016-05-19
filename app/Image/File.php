<?php
namespace Hackaton\ImageStorage\Image;

class File
{
    protected $key;
    protected $content;

    public function __construct($key, $content)
    {
        $this->content = $content;
        $this->key = $key;
    }

    public function getKey()
    {
        return $this->key;
    }

    public function getContent()
    {
        return $this->content;
    }
}
