<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;

abstract class AbstractCrudController extends \EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController
{
    protected function getIdField(): FieldInterface
    {
        return IdField::new('id')->setDisabled()->hideWhenCreating();
    }

    /**
     * @return FieldInterface[]
     */
    protected function getDateTimeFields(): array
    {
        return [
            DateTimeField::new('createdAt')->setDisabled()->hideWhenCreating(),
            DateTimeField::new('updatedAt')->setDisabled()->hideWhenCreating(),
        ];
    }

    public static function makeShortText(string $text, int $start = 0, int $maxLength = 15): string
    {
        return mb_substr(
            $text,
            $start,
            $maxLength,
            'UTF-8'
        ) . '...';
    }

    public function configureFields(string $pageName): iterable
    {
        $configure = $this->otherConfigureFields();

        array_unshift($configure, $this->getIdField());
        array_push($configure, ...$this->getDateTimeFields());

        return $configure;
    }

    /**
     * @return FieldInterface[]
     */
    abstract protected function otherConfigureFields(): array;
}
