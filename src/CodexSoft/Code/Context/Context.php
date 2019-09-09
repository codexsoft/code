<?php

namespace CodexSoft\Code\Context;

/**
 * Fascade for using layered dependency containers
 *
 * Class Context
 */
class Context
{

    /** @var DependencyManager */
    protected static $dependencyManager;

    /**
     * @return DependencyManager
     */
    public static function getDependencyManager(): DependencyManager
    {

        if (!static::$dependencyManager instanceof DependencyManager) {
            static::$dependencyManager = new DependencyManager;
        }

        return static::$dependencyManager;

    }

    public static function normalizeDependencies(array $dependencies): array
    {

        $result = [];
        foreach ($dependencies as $key => $value) {

            if (\is_int($key) && \is_object($value)) {
                $result[\get_class($value)] = $value;
            } else {
                $result[$key] = $value;
            }

        }
        return $result;

    }

    /**
     * @param $instances
     * Чтобы работало предсказуемо и заменяло существующие, надо или указывать обязательно string
     *     keys!
     */
    public static function mergeWith($instances)
    {
        $parameter = \is_object($instances) ? [$instances] : $instances;
        $parameter = static::normalizeDependencies($parameter);
        return static::getDependencyManager()->actual()->mergeWith($parameter);
    }

    /**
     * Returns resolved instance of a given class, using provided matching mode
     * Throws exception if not found
     *
     * @param $class
     * @param int $mode
     *
     * @return mixed|null
     * @throws Exception\NotResolvedException
     */
    public static function get($class, $mode = DependencyManager::ACCEPT_SAME)
    {
        return static::getDependencyManager()->resolve($class, $mode);
    }

    /**
     * Returns resolved instance of a given class, using provided matching mode
     * Returns null if not found
     *
     * @param $class
     * @param int $mode
     *
     * @return mixed|null
     */
    public static function getOrNull($class, $mode = DependencyManager::ACCEPT_SAME)
    {
        return static::getDependencyManager()->resolveOrNull($class, $mode);
    }

    /**
     * @return DependencyLayer|null
     */
    public static function actual(): ?DependencyLayer
    {
        return static::getDependencyManager()->actual();
    }

    /**
     * Destroys current context (if any)
     */
    public static function destroy(): void
    {
        static::getDependencyManager()->destroy();
    }

    /**
     * Returns first (base) layer
     *
     * @return DependencyLayer|null
     */
    public static function base(): ?DependencyLayer
    {
        return static::getDependencyManager()->base();
    }

    /**
     * Creates [isolated] layer
     *
     * @param array $dependencies
     * @param bool $isolated
     */
    public static function create(array $dependencies, $isolated = false): void
    {
        static::getDependencyManager()->create($dependencies, $isolated);
    }

}