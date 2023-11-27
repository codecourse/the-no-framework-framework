<?php

namespace App\Exceptions;

use App\Session\SessionStore;
use App\Views\View;
use Exception;
use Psr\Http\Message\ResponseInterface;
use ReflectionClass;

class Handler
{
    protected $exception;

    protected $session;

    protected $view;

    protected $response;

    public function __construct(
        Exception $exception,
        SessionStore $session,
        ResponseInterface $response,
        View $view
    ) {
        $this->exception = $exception;
        $this->session = $session;
        $this->response = $response;
        $this->view = $view;
    }

    public function respond()
    {
        $class = (new ReflectionClass($this->exception))->getShortName();

        if (method_exists($this, $method = "handle{$class}")) {
            return $this->{$method}($this->exception);
        }

        return $this->unhandledException($this->exception);
    }

    protected function handleValidationException(Exception $e)
    {
        $this->session->set([
            'errors' => $e->getErrors(),
            'old' => $e->getOldInput(),
        ]);
        
        return redirect($e->getPath());
    }

    protected function handleCsrfTokenException(Exception $e)
    {
        return $this->view->render($this->response, 'errors/csrf.twig');
    }

    protected function unhandledException(Exception $e)
    {
        throw $e;
    }
}
