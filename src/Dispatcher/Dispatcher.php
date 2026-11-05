<?php

namespace Zayd54500\TouiteurSae\Dispatcher;

use Zayd54500\TouiteurSae\Action\ActionFactory;

class Dispatcher
{
    private ActionFactory $actionFactory;

    public function __construct(ActionFactory $actionFactory)
    {
        $this->actionFactory = $actionFactory;
    }

    public function run(string $nomAction): string
    {
        $action = $this->actionFactory->create($nomAction);

        return $action->execute();
    }
}