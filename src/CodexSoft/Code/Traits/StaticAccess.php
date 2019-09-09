<?php

namespace CodexSoft\Code\Traits;

/**
 * В некоторых случаях создание нескольких экземпляров класса нежелательно (например, его
 * конструирование и/или инициализация ресурсоемки), или же планируется использование только одного
 * экземпляра в приложении. В этом случае синглтон может быть полезен. Конструирование экземпляра
 * происходит только один раз, и только при первом запросе к этому экземпляру.
 */
trait StaticAccess
{

	private static $_static_instance;

    /**
     * @return static
     */
	public static function tool()
    {

        if (!self::$_static_instance instanceof static) {
            self::$_static_instance = new static();
        }

        return self::$_static_instance;

	}

    /**
     * @param static $instance
     *
     * @throws \Exception
     */
    public static function setInstance( $instance ) {

        if (!$instance instanceof static) {
            throw new \Exception('Wrong instance provided: '.static::class.' expected, '.get_class($instance).' provided.');
        }

        self::$_static_instance = $instance;

    }

}