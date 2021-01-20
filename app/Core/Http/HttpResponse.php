<?php


namespace Quetzal\Core\Http;

use Quetzal\Core\AppException;

class HttpResponse extends Response
{
    protected $status = 0;
    protected $headers = [];
    protected $body = "";
    
    const HTTP_CODES = [
        200 => "OK",
        301 => "Moved Permanently",
        400 => "Bad Request",
        401 => "Unauthorized",
        403 => "Forbidden",
        404 => "Not Found",
        405 => "Method Not Allowed",
        408 => "Request Timeout",
        500 => "Internal Server Error",
        502 => "Bad Gateway",
        504 => "Gateway Timeout",
    ];

    public function __construct($data, $code = 200, $encode = true, $charset = 'utf-8', $options = 0)
    {
        $jsonResponse = ($encode) ? json_encode($data, $options) : $data;

        $this->response()
             ->status($code)
             ->header('application/json')
             ->body($jsonResponse)
             ->send();
    }

    public function response() {
        return $this;
    }

    public function status($code = null) {
        if ($code === null) {
            return $this->status;
        }

        if (array_key_exists($code, self::HTTP_CODES)) {
            $this->status = $code;
        }
        else {
            throw new \Exception('Invalid status code.');
        }

        return $this;
    }

    public function header($name, $value = null) {
        if (is_array($name)) {
            foreach ($name as $k => $v) {
                $this->headers[$k] = $v;
            }
        }
        else {
            $this->headers[$name] = $value;
        }

        return $this;
    }

    public function body($str) {
        $this->body .= $str;

        return $this;
    }

    public static function createResponse($data,
                                          $code = 200,
                                          $encode = true,
                                          $charset = 'utf-8',
                                          $options = 0): HttpResponse {
        return new HttpResponse($data, $code, $encode, $charset, $options);
    }

    public function send(): int
    {
        echo $this->body;
        return 1;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getHeader(): string
    {
        if (!isset($this->headers[$this->status])) {
            throw new AppException(
                "HttpResponse::getHeader():" . PHP_EOL .
                "Header not set for HTTP status code " . $this->status . "."
            );
        }

        return $this->headers[$this->status];
    }

    public function setHeader(string $msg): void
    {
        $this->headers[$this->status] = $msg;
    }

    public function getBody(): string
    {
        return $this->body;
    }

    public function setBody(string $body): void
    {
        $this->body = $body;
    }

}
