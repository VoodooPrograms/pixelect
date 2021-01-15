<?php

namespace Quetzal\Core\Http;

use Quetzal\Core\AppException;
use Quetzal\Core\Http\Bags\GetBag;
use Quetzal\Core\Http\Bags\PostBag;
use Quetzal\Core\Http\Bags\SessionBag;

class HttpRequest extends Request
{
    protected $path = "/";
    protected $status = 0;
    protected $request_method = "GET";
    protected $ip_address = "";
    protected $user_agent = "";
    protected $content_type = "";
    protected $content_length = 0;
    protected $cookies = [];
    protected $session;
    protected $get;
    protected $post;

    protected function launch()
    {
        $this->setPath($_SERVER["REQUEST_URI"]);
        $this->setRequestMethod($_SERVER["REQUEST_METHOD"]);
        $this->setIpAddress($_SERVER["SERVER_NAME"]);
        $this->setUserAgent($_SERVER["HTTP_USER_AGENT"]);
        $this->setContentType($_SERVER["CONTENT_TYPE"]);
        $this->setContentLength((int)$_SERVER["CONTENT_LENGTH"]);
        $this->setCookies($_COOKIE);
        $this->setSession($_SESSION ?? []);
        $this->setGet($_GET);
        $this->setPost($_POST);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(?string $path): void
    {
        $this->path = $path;
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    public function getRequestMethod(): string
    {
        return $this->request_method;
    }

    public function setRequestMethod(?string $method): void
    {
        $this->request_method = $method ?? "GET";
    }

    public function getIpAddress(): string
    {
        return $this->ip_address;
    }

    public function setIpAddress(?string $address): void
    {
        $this->ip_address = $address ?? "";
    }

    public function getUserAgent(): string
    {
        return $this->user_agent;
    }

    public function setUserAgent(?string $user_agent): void
    {
        $this->user_agent = $user_agent ?? "";
    }

    public function getContentType(): string
    {
        return $this->content_type;
    }

    public function setContentType(?string $content_type): void
    {
        $this->content_type = $content_type ?? "";
    }

    public function getContentLength(): int
    {
        return $this->content_length;
    }

    public function setContentLength(?int $content_length): void
    {
        $this->content_length = $content_length ?? 0;
    }

    public function getCookie(string $name)
    {
        if (!isset($this->cookies[$name])) {
            throw new AppException("(HTTP REQUEST) Cookie \"" . $name . "\" is not set!");
        }

        return $this->cookies[$name];
    }

    public function setCookies(array $cookies): void
    {
        $this->cookies = $cookies;
    }

    public function getSession(): SessionBag
    {
        return $this->session;
    }

    public function setSession($session): void
    {
        $this->session = new SessionBag($session);
    }

    public function setGet(array $get_data)
    {
        $this->get = new GetBag($get_data);
    }

    public function setPost(array $post_data)
    {
        $this->post = new PostBag($post_data);
    }

    public function get(): GetBag
    {
        return $this->get;
    }

    public function post(): PostBag
    {
        return $this->post;
    }


}
