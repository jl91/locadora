<?php

namespace VideoPlace\Model\View;


class DefaultViewParser implements ViewInterface
{
    private $file = null;
    private $vars = null;

    const VIEW_PATH = APP_ROOT . DS . 'src' . DS . 'view' . DS . '%s.phtml';

    public function __construct(string $file, array $vars = [])
    {
        $this->vars = $vars;
        $this->file = sprintf(static::VIEW_PATH, $file);;
        $this->validateFile();
    }

    private function validateFile()
    {

        if (!file_exists($this->file)) {
            $message = "View {$this->file} not found";
            throw  new \RuntimeException($message, 500);
        }

        if (!is_readable($this->file)) {
            $message = "View {$this->file} is not readable";
            throw  new \RuntimeException($message, 500);
        }

        return true;
    }

    private function getContent()
    {
        $content = file_get_contents($this->file);
        return $content;
    }

    private function parse(string $content, array $vars = [])
    {
        if (!empty($this->vars)) {
            foreach ($this->vars as $varName => $varValue) {
                $content = str_replace($varName, $varName, $content);
            }
        }

        return $content;
    }


    public function __toString(): string
    {
        $content = $this->getContent($this->vars);
        return $this->parse($content, $this->vars);
    }


}