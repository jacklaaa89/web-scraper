<?php

namespace Example\Controller;

/**
 * Interface Constants
 *
 * Deliberately an interface so it cannot be instantiated.
 *
 * @package Example\Controller
 */
interface Constants
{
    /** @const string */
    const VIEW = 'view';

    /** @const string */
    const ROUTER = 'router';

    /** @const string */
    const REQUEST = 'request';

    /** @const string */
    const RESPONSE = 'response';
}