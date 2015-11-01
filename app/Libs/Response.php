<?php

namespace Libs;

class Response
{

    use Singletonable;

    protected $status = 200;
    protected $message;
    protected $headers = array(
        'Cache-Control: no-cache',
        'Pragma: no-cache',
    );

    protected function header($content)
    {
        $this->headers[] = $content;
    }

    public function send($response = '')
    {
        ob_start();

        foreach ($this->headers as $header) {
            header($header, true);
        };

        if (is_array($response)){
            header('Content-Type: application/json', true);
            echo json_encode($response);
        } else {
            header('Content-Type: text/html', true);
            echo $response;
        }
        ob_end_flush();
    }
}
