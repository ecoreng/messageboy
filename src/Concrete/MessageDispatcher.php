<?php

namespace ecoreng\MessageBoy\Concrete;

use \ecoreng\MessageBoy\MessageDispatcher as IMessageDispatcher;
use \ecoreng\MessageBoy\Message as IMessage;
use \ecoreng\MessageBoy\Adapter;

class MessageDispatcher implements IMessageDispatcher
{

    /**
     * All the registered adapters
     *
     * @var \ArrayIterator
     */
    protected $adapters;

    /**
     * Reference to which adapter handles which type of request;
     * Contains an array with keys that reference the $adapters iterator entry
     *
     * @var array
     */
    protected $types = [];

    /**
     * Reference to which adapter handles which group request;
     * Contains an array with keys that reference the $adapters iterator entry
     *
     * @var array
     */
    protected $groups = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->adapters = new \ArrayIterator;
    }

    /**
     * Dispatch the message to the specified handlers registered for $type or
     * $group, if both variables are null, then it will dispatch the message
     * globally. Returns the number of handlers that should have processed
     * the message.
     *
     * @param Message $message
     * @param string $type
     * @param string $group
     * @return int
     */
    public function dispatch(IMessage $message, $type = null, $group = null)
    {
        $dispatched = 0;
        if (!$type && !$group) {
            foreach ($this->adapters as $adapter) {
                $adapter->handle($message);
                $dispatched++;
            }
        } else {
            $merged = new \AppendIterator;
            $merged->append($this->getAdaptersByType($type));
            $merged->append($this->getAdaptersByGroup($group));
            foreach ($merged as $adapter) {
                $adapter->handle($message);
                $dispatched++;
            }
        }
        return $dispatched;
    }

    /**
     * Register $adapter to handle the messages of type $type or group $group,
     * if these variables are null, the adapter will handle only global messages
     *
     * @param Adapter $adapter
     * @param string $type
     * @param string $group
     * @return \ecoreng\MessageBoy\Concrete\MessageDispatcher
     */
    public function registerAdapter(Adapter $adapter, $type = null, $group = null)
    {
        $this->adapters->append($adapter);
        if ($type || $group) {
            $key = count($this->adapters) - 1;
            $this->addToType($type, $key);
            $this->addToGroup($group, $key);
        }
        return $this;
    }

    /**
     * Returns an \ArrayIterator containing all adapters
     *
     * @return \ArrayIterator
     */
    public function getAdapters()
    {
        return $this->adapters;
    }

    /**
     * Returns an \ArrayIterator containing all adapters registered to handle
     * a $type request
     *
     * @param string $type
     * @return \ArrayIterator
     */
    public function getAdaptersByType($type)
    {
        return $this->getAdaptersFromCollection('types', $type);
    }

    /**
     * Returns an \ArrayIterator containing all adapters registered to handle
     * a $group request
     *
     * @param string $group
     * @return \ArrayIterator
     */
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
