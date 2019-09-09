<?php

namespace CodexSoft\Code\Traits;

/**
 * В некоторых случаях создание нескольких экземпляров класса нежелательно (например, его
 * конструирование и/или инициализация ресурсоемки), или же планируется использование только одного
 * экземпляра в приложении. В этом случае синглтон может быть полезен. Конструирование экземпляра
 * происходит только один раз, и только при первом запросе к этому экземпляру.
 */
trait InvokableSingletone
{

    /** @var static|null */
	private static $instance;

    /**
     * @return static
     */
	public function __invoke(): self
    {
		return self::$instance ?: self::$instance = new static;
	}

}