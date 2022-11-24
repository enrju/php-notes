<?php

declare(strict_types=1);

namespace App;

class Request
{
    private array $get = [];
    private array $post = [];
    private array $server = [];

    public function __construct(array $get, array $post, array $server)
    {
        $this->get = $get;
        $this->post = $post;
        $this->server = $server;
    }

    public function getHTTPMethod(): string
    {
        return $this->server['REQUEST_METHOD'];
    }

    public function getQueryStringParam(string $name, $default = null)
    {
        return $this->get[$name] ?? $default;
    }

    public function getPostBodyParam(string $name, $default = null)
    {
        return $this->post[$name] ?? $default;
    }
}
