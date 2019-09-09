<?php

namespace CodexSoft\Code\Context;

use CodexSoft\Code\Helpers\Classes;
use CodexSoft\Code\Context\Exception\NotResolvedException;
use CodexSoft\Code\Helpers\Arrays;

/**
 * Class DependencyManager
 *
 * Can be used as a layered dependency container
 * Improves testability, can be faked e.t.c.
 */
class DependencyManager
{

    public const ACCEPT_SAME = 1;
    public const ACCEPT_PARENTS = 2;
    public const ACCEPT_CHILDREN = 3;
    public const ACCEPT_BOTH = 4;

    /**
     * @var DependencyLayer[]
     */
    private $dependencyLayers = [];

    /**
     * @var DependencyLayer
     */
    private $current;

    /**
     * @param array $dependencies
     * @param bool $isolated
     */
    public function create(array $dependencies, $isolated = false): void
    {

        $layer = new DependencyLayer();

        $dependencies = Context::normalizeDependencies($dependencies);

        // если контекст не изолированный, то мержим его поверх последнего контекста
        if (!$isolated && $this->current) {
            $dependencies = array_replace($this->current->all(), $dependencies);
        }
        $layer->setIsolated($isolated)->add($dependencies);
        $this->current = $layer;
        $this->dependencyLayers[] = $layer;

    }

    public function destroy(): void
    {

        if ($this->dependencyLayers) {
            array_pop($this->dependencyLayers);
        }

        $this->current = \count($this->dependencyLayers)
            ? Arrays::tool()->getLast($this->dependencyLayers)
            : null;

    }

    /**
     * @return DependencyLayer
     */
    public function actual(): DependencyLayer
    {

        if (!$this->current) {
            $this->create([]);
        }

        return $this->current;

    }

    /**
     * @return DependencyLayer|null
     */
    public function base(): ?DependencyLayer
    {
        if (\count($this->dependencyLayers)) {
            return $this->dependencyLayers[0];
        }
        return null;
    }

    /**
     * @param $className
     * @param int $mode
     *
     * @return mixed|null
     */
    public function resolveOrNull($className, $mode = self::ACCEPT_SAME)
    {
        try {
            return $this->resolve($className, $mode);
        } catch (NotResolvedException $e) {
            return null;
        }
    }

    /**
     * @param string $searchKey идентификатор или класс объекта искомой зависимости
     * @param int $mode 1 - SAME, 2 - ACCEPT PARENT, 3 - ACCEPT CHILD, 4 - ACCEPT BOTH
     *
     * @return mixed|null
     * @throws NotResolvedException
     */
    public function resolve($searchKey, $mode = self::ACCEPT_SAME)
    {

        // todo: accept interface?

        foreach (array_reverse($this->dependencyLayers) as $dependencyLayer) {

            /** @var DependencyLayer $dependencyLayer */
            $dependency = null;

            // если ищем класс
            if (class_exists($searchKey)) {

                foreach ($dependencyLayer->all() as $key => $value) {

                    // распаковываем зависимость
                    if ($value instanceof \Closure) // точно?..
                    {
                        $value = $dependencyLayer[$searchKey] = $value();
                    }

                    // если нужно точное соответствие класса
                    if (( $mode === self::ACCEPT_SAME ) && \is_object($value) && \get_class($value) !== $searchKey) {
                        continue;
                    }
                    /** @noinspection RedundantElseClauseInspection */
                    // если подойдет объект искомого класса или один из родительских
                    elseif (( $mode === self::ACCEPT_PARENTS )
                        && !Classes::isSameOrExtends($searchKey, $value)) {
                        continue;
                    } // если подойдет объект искомого класса или один из дочерних
                    elseif (( $mode === self::ACCEPT_CHILDREN )
                        && !Classes::isSameOrExtends($value, $searchKey)) {
                        continue;
                    } // если подойдет объект искомого класса, один из дочерних или один из родительских
                    elseif (( $mode === self::ACCEPT_BOTH )
                        && !Classes::isSameOrExtends($value, $searchKey)
                        && !Classes::isSameOrExtends($searchKey, $value)) {
                        continue;
                    }

                    // зависимость удовлетворяет параметрам
                    $dependency = $value;
                    break;

                } // итерация по элементам слоя зависимостей

            } else { // если ищем по имени
                if (!$dependencyLayer->has($searchKey)) {
                    continue;
                } // зависимости с искомым именем не найдено, опускаемся на следующий слой
                $dependency = $dependencyLayer->get($searchKey);
            }

            if (!$dependency) {
                continue;
            }

            return $dependency;

        } // итерация по слою зависимостей

        throw new NotResolvedException("Dependency with name {$searchKey} was not resolved!");

    }

}