<?php

declare(strict_types=1);

namespace Symfony\UX\Autocomplete\Form;

use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AutocompleteEntityTypeExtension extends AbstractTypeExtension
{
    public static function getExtendedTypes(): iterable
    {
        return [
            EntityType::class,
        ];
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $queryBuilderNormalizer = function (Options $options, $queryBuilder) {
            if (\is_callable($queryBuilder)) {
                $queryBuilder = $queryBuilder($options['em']->getRepository($options['class']), $options['query_context']);

                if (null !== $queryBuilder && !$queryBuilder instanceof QueryBuilder) {
                    throw new UnexpectedTypeException($queryBuilder, QueryBuilder::class);
                }
            }

            return $queryBuilder;
        };

        $resolver->setDefault('query_context', []);
        $resolver->setAllowedTypes('query_context', 'array');

        $resolver->setNormalizer('query_builder', $queryBuilderNormalizer);
    }
}