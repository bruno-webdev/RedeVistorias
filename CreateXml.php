<?php

class CreateXml extends DOMDocument
{

    public $nodeName;
    private $xpath;
    private $root;
    private $node_name;

    public function __construct($root = 'root', $node_name = 'node')
    {
        parent::__construct();

        $this->encoding = "ISO-8859-1";
        $this->formatOutput = true;
        $this->node_name = $node_name;
        $this->root = $this->appendChild($this->createElement($root));
        $this->xpath = new DomXPath($this);
    }

    public function createNode($arr, $node = null)
    {
        if (is_null($node)) {
            $node = $this->root;
        }
        foreach ($arr as $element => $value) {
            $element = is_numeric($element) ? $this->node_name : $element;
            $elementValue = is_array($value) || is_object($value) ? null : utf8_encode($value);

            $child = $this->createElement($element, $elementValue);
            $node->appendChild($child);

            if (is_array($value) || is_object($value)) {
                self::createNode($value, $child);
            }
        }
    }

    public function __toString()
    {
        return $this->saveXML();
    }

}
