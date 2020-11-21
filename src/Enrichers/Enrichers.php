<?php

declare(strict_types=1);

namespace Klitsche\Dog\Enrichers;

use Klitsche\Dog\ConfigInterface;

class Enrichers
{
    /**
     * @var EnricherInterface[]
     */
    protected array $enrichers;

    public function __construct(EnricherInterface ...$enrichers)
    {
        $this->enrichers = $enrichers;
    }

    public static function createFromConfig(ConfigInterface $config): self
    {
        $enrichers = [];

        foreach ($config->getEnrichers() as $id => $enricherConfig) {
            $class = $enricherConfig['class'] ?? '';

            self::ensureClassImplementsEnricherInterface($class);

            /** @var EnricherInterface $class */
            $enrichers[] = $class::create($id, $config);
        }

        return new static(...$enrichers);
    }

    private static function ensureClassImplementsEnricherInterface(string $class): void
    {
        $reflection = self::getClassReflection($class);

        if ($reflection->implementsInterface(EnricherInterface::class) === false) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Class %s does not implement %s',
                    $class,
                    EnricherInterface::class
                )
            );
        }
    }

    private static function getClassReflection(string $class): \ReflectionClass
    {
        try {
            $reflection = new \ReflectionClass($class);
        } catch (\ReflectionException $exception) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Class %s not valid. Reason: %s',
                    $class,
                    $exception->getMessage()
                )
            );
        }

        return $reflection;
    }

    /**
     * @return EnricherInterface[]
     */
    public function getEnrichers(): array
    {
        return $this->enrichers;
    }
}
