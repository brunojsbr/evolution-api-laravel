<?php

namespace Brunojsbr\EvolutionApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array createInstance(string $name)
 * @method static array getInstanceQR(string $instance)
 * @method static array getInstanceStatus(string $instance)
 * @method static array disconnectInstance(string $instance)
 * @method static array sendText(string $instance, string $to, string $message)
 * @method static array sendImage(string $instance, string $to, string $url, ?string $caption = null)
 * @method static array sendDocument(string $instance, string $to, string $url, string $filename)
 * @method static array sendAudio(string $instance, string $to, string $url)
 * @method static array sendVideo(string $instance, string $to, string $url, ?string $caption = null)
 * @method static array sendLocation(string $instance, string $to, float $latitude, float $longitude, ?string $name = null)
 * @method static array sendContact(string $instance, string $to, string $number, string $name)
 * @method static array sendButton(string $instance, string $to, string $text, array $buttons)
 * @method static array sendList(string $instance, string $to, string $text, array $sections)
 *
 * @see \Brunojsbr\EvolutionApi\Services\EvolutionApiService
 */
class EvolutionApi extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'evolution-api';
    }
}
