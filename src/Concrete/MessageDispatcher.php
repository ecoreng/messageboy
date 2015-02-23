<?php

namespace ecoreng\MessageBoy\Concrete;

use \ecoreng\MessageBoy\MessageDispatcher as IMessageDispatcher;
use \ecoreng\MessageBoy\Message as IMessage;
use \ecoreng\MessageBoy\Adapter;

class MessageDispatcher implements IMessageDispatcher
{
    protected $adapters = [];
    protected $types = [];
    protected $groups = [];

    public function __construct()
    {
        $this->adapters = new \ArrayIterator;
    }

    public function dispatch(IMessage $message, $type = null, $group = null)
    {
        if (!$type && !$group) {
            foreach ($this->adapters as $adapter) {
                $adapter->handle($message);
            }
        } else {
            $merged = new \AppendIterator;
            $merged->append($this->getAdaptersByType($type));
            $merged->append($this->getAdaptersByGroup($group));
            foreach ($merged as $adapter) {
                $adapter->handle($message);
            }
        }
    }

    public function registerAdapter(Adapter $adapter, $type = null, $group = null)
    {
        $this->adapters->append($adapter);
        if (!$type || !$group) {
            $key = count($this->adapters) - 1;
            $this->addToType($type, $key);
            $this->addToGroup($group, $key);
        }
    }

    public function getAdapters()
    {
        return $this->adapters;
    }

    public function getAdaptersByType($type)
    {
        return $this->getAdaptersFromCollection('types', $type);
    }

    public function getAdaptersByGroup($group)
    {
        return $this->getAdaptersFromCollection('groups', $group);
    }

    protected function getAdaptersFromCollection($collection, $index)
    {
        $results = new \ArrayIterator;
        if ($index) {
            if (isset($this->{$collection}[$index])) {
                foreach ($this->{$collection}[$index] as $key) {
                    $results->append($this->adapters->offsetGet($key));
                }
            }
        }
        return $results;
    }

    protected function addToType($type, $element)
    {
        $this->addToCollection('types', $type, $element);
    }

    protected function addToGroup($group, $element)
    {
        $this->addToCollection('groups', $group, $element);
    }

    protected function addToCollection($collection, $index, $element)
    {
        if ($element !== null && $index !== null) {
            if (isset($this->$collection)) {
                if (!is_array($this->$collection)) {
                    $this->$collection = [];
                }

                if (!array_key_exists($index, $this->$collection)) {
                    $this->{$collection}[$index] = [];
                }

                if ($element !== null) {
                    $this->{$collection}[$index][] = $element;
                }
            }
        }
    }
}
