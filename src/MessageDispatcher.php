<?php

namespace ecoreng\MessageBoy;

use \ecoreng\MessageBoy\Message as IMessage;
use \ecoreng\MessageBoy\Adapter;

interface MessageDispatcher
{

    /**
     * Dispatch the message to the specified handlers registered for $type or
     * $group, if both variables are null, then it will dispatch the message
     * globally
     *
     * @param IMessage $message
     * @param string $type
     * @param string $group
     */
    public function dispatch(IMessage $message, $type = null, $group = null);

    /**
     * Register $adapter to handle the messages of type $type or group $group,
     * if these variables are null, the adapter will handle only global messages
     *
     * @param Adapter $adapter
     * @param string $type
     * @param string $group
     */
    public function registerAdapter(Adapter $adapter, $type = null, $group = null);

    /**
     * Returns an \ArrayIterator containing all adapters
     */
    public function getAdapters();

    /**
     * Returns an \ArrayIterator containing all adapters registered to handle
     * a $type request
     *
     * @param string $type
     */
    public function getAdaptersByType($type);

    /**
     * Returns an \ArrayIterator containing all adapters registered to handle
     * a $group request
     *
     * @param string $group
     */
    public function getAdaptersByGroup($group);
}
