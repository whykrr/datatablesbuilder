<?php

namespace DatatablesBuilder\Generator;


/**
 * The Builder interface declares a set of methods to assemble an SQL query.
 *
 * All of the construction steps are returning the current builder object to
 * allow chaining: $builder->select(...)->where(...)
 */
interface DatatablesBuilderInterface
{
    public function layout(string $header, string $key): DatatablesBuilder;

    public function source(array $data): DatatablesBuilder;

    public function useSerialNumber(bool $sn): DatatablesBuilder;

    public function actionButton(string $button): DatatablesBuilder;

    public function loadHelper(array $helpers): DatatablesBuilder;

    public function build(int $page, int $per_page): object;
}
