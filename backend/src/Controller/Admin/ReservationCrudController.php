<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Rezerwacja')
            ->setEntityLabelInPlural('Rezerwacje')
            ->setSearchFields(['room_no', 'starts_at', 'ends_at', 'paid'])
            ->setDefaultSort(['starts_at' => 'asc']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('room_id'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('room_id');
        yield DateTimeField::new('created_at')->hideOnIndex();
        yield DateTimeField::new('starts_at');
        yield DateTimeField::new('ends_at');
        yield BooleanField::new('paid');
    }
}
