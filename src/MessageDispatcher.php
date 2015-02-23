<?php

namespace ecoreng\MessageBoy;

use \ecoreng\MessageBoy\Message as IMessage;
use \ecoreng\MessageBoy\Adapter;

interface MessageDispatcher
{

    public function dispatch(IMessage $message);

    public function registerAdapter(Adapter $adapter, $type = null, $group = null);

    public function getAdapters();

    public function getAdaptersByType($type);

    public function getAdaptersByGroup($group);
}
