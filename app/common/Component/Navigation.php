<?php

namespace Component;

class Navigation extends \Phalcon\Mvc\User\Component
{
    protected $nodes = [];

    public function addNode(string $label, string $routeName, array $args = [], array $aliases = []):Navigation
    {
        // TODO: add children, recursive

        $this->nodes[] = [
            'label' => $label,
            'route' => $routeName,
            'url' => $this->url->get(array_merge(
                ['for' => $routeName],
                $args
            )),
            'matched' => $this->isMatch($routeName, $aliases),
        ];

        return $this;
    }

    public function isMatch(string $routeName, array $aliases = []):bool
    {
        $matchedRoute = $this->router->getMatchedRoute()->getName();

        if ($matchedRoute === $routeName) {
            return true;
        }

        if (\in_array($matchedRoute, $aliases)) {
            return true;
        }

        return false;
    }

    public function getNodes():array
    {
        return $this->nodes;
    }
}
