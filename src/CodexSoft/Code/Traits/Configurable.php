<?php

namespace CodexSoft\Code\Traits;

trait Configurable
{

    /**
     * SendMessageOperation constructor.
     *
     * @param array $data
     *
     * @return static
     */
    public static function createAndConfigure(array $data): self
    {
        $instance = new static;
        $instance->configure($data);
        return $instance;
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public function configure(array $data): self
    {
        foreach ($data as $k => $v) {
            $setterMethod = 'set'.\ucfirst($k);
            if (\method_exists($this, $setterMethod)) {
                $this->$setterMethod($v);
            } else if (\property_exists($this,$k)) {
                $this->{$k} = $v;
            }
        }
        return $this;
    }

}