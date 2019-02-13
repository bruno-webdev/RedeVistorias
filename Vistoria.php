<?php

class Vistoria
{

    private $token;
    private $format;
    private $urlBase = "#"; // URL não divulgada por questões de segurança

    public function __construct($format = null)
    {
        $this->setFormat($format);
        $this->token = "Bearer XXX"; // Token não divulgada por questões de segurança
    }

    public function setFormat($format)
    {
        $this->format = $format;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function pricePreview($post)
    {
        $url = "/erp/price_preview";

        $ret = $this->curlExecute($url, $post);

        if (isset($ret->info) && (int) $ret->info['http_code'] === 200) {
            $print = $this->imprimir(['price' => str_replace('"', '', $ret->output)]);
            print_r($print);
        }
    }

    public function building($code)
    {
        $url = "/challenge/service/bar/building/{$code}";
        $ret = $this->curlExecute($url);
        if ((int) $ret->info['http_code'] === 200) {
            $print = $this->imprimir(json_decode($ret->output));
            print_r($print);
        }
    }

    public function item($id)
    {
        $url = "/challenge/service/foo/item/{$id}";
        $ret = $this->curlExecute($url);

        if ((int) $ret->info['http_code'] === 200) {
            $print = $this->imprimir(json_decode($ret->output));
            print_r($print);
        }
    }

    public function curlExecute($url, $post = null)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->urlBase . $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Authorization: {$this->token}"]);
        if ($post !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        }
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $server_output = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);

        curl_close($ch);

        return (object) [
                    'output' => $server_output,
                    'info' => $info,
                    'error' => $error
        ];
    }

    public function imprimir($array)
    {
        $format = strtolower($this->getFormat());

        switch ($format) {
            case 'xml':
                $this->xml($array);
                break;
            default:
                $this->json($array);
                break;
        }
    }

    public function xml($array)
    {
        try {
            if (count($array) === 0) {
                throw new Exception("Nada Encontrado", '404');
            }
            $xml = new CreateXml('vistoria');
            $xml->createNode($array);
            echo $xml;
        } catch (Exception $e) {
            header("HTTP/1.1 {$e->getCode()}");
        }
    }

    public function json($array)
    {
        header('Content-Type: application/json');
        echo json_encode($array);
    }

}
