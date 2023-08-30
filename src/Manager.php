<?php

require_once __DIR__ . '/Contract/ManagerInterface.php';

class Manager implements ManagerInterface
{

    private ?DatabaseInterface $databaseManager;

    private ?SessionInterface $sessionManager;

    private ?RouterInterface $routerManager;

    private ?Response $responseManager;

    private ?Request $requestManager;


    public function setDatabaseManager(\DatabaseInterface $manager): self
    {
        $this->databaseManager = $manager;

        return $this;
    }

    /**
     * @return DatabaseInterface|null
     */
    public function getDatabaseManager(): ?DatabaseInterface
    {
        return $this->databaseManager;
    }

    /**
     * @param SessionInterface|null $sessionManager
     */
    public function setSessionManager(?SessionInterface $sessionManager): self
    {
        $this->sessionManager = $sessionManager;

        return $this;
    }

    public function getSessionManager(): ?SessionInterface
    {
        return $this->sessionManager;
    }

    /**
     * @param Response|null $responseManager
     */
    public function setResponseManager(?Response $responseManager): self
    {
        $this->responseManager = $responseManager;

        return $this;
    }

    public function getResponseManager(): ?Response
    {
        return $this->responseManager;
    }

    /**
     * @return Request|null
     */
    public function getRequestManager(): ?Request
    {
        return $this->requestManager;
    }

    /**
     * @param Request|null $requestManager
     */
    public function setRequestManager(?Request $requestManager): void
    {
        $this->requestManager = $requestManager;
    }

    /**
     * @return RouterInterface|null
     */
    public function getRouterManager(): ?RouterInterface
    {
        return $this->routerManager;
    }

    /**
     * @param RouterInterface|null $routerManager
     */
    public function setRouterManager(?RouterInterface $routerManager): self
    {
        $this->routerManager = $routerManager;

        return $this;
    }

    public function getEntity(string $name): mixed
    {
        $filename = sprintf("%s/Entities/%s.php", __DIR__, $name);
        if (file_exists($filename)) {
            require_once $filename;

            return new $name;
        }

        return null;
    }

    public function getService(string $name): mixed
    {
        $filename = sprintf("%s/Services/%s.php", __DIR__, $name);
        if (file_exists($filename)) {
            require_once $filename;

            return new $name;
        }

        throw new Exception("Server Error: Service $name Not Found.");
    }

    public function getRepository(string $name): mixed
    {
        $filename = sprintf("%s/Repositories/%s.php", __DIR__, $name);
        if (file_exists($filename)) {
            require_once $filename;

            return new $name;
        }

        return null;
    }

}