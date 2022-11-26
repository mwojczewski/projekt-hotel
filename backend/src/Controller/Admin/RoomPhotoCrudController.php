<?php

namespace App\Controller\Admin;

use App\Entity\RoomPhoto;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class RoomPhotoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return RoomPhoto::class;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('room_id'));
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Zdjęcie')
            ->setEntityLabelInPlural('Zdjęcia')
            ->setSearchFields(['room_id', 'name'])
            ->setDefaultSort(['room_id' => 'asc']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield AssociationField::new('room_id');
        yield ImageField::new('name')
            ->setBasePath('uploads/images')
            ->setUploadDir('public/uploads/images')
            ->setUploadedFileNamePattern('[randomhash].[extension]');
    }
}
