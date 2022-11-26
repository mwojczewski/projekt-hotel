<?php

namespace App\Controller\Admin;

use App\Entity\Room;
use Doctrine\DBAL\Types\BooleanType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\HiddenField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class RoomCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Room::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Pokój')
            ->setEntityLabelInPlural('Pokoje')
            ->setSearchFields(['room_no', 'floor_no', 'name', 'beds', 'price'])
            ->setDefaultSort(['room_no' => 'asc']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield NumberField::new('room_no');
        yield TextField::new('name');
        yield NumberField::new('floor_no');
        yield NumberField::new('size');
        yield NumberField::new('beds');
        yield BooleanField::new('balcony')->hideOnIndex();
        yield BooleanField::new('breakfast')->hideOnIndex();
        yield BooleanField::new('pets_allowed')->hideOnIndex();
        yield NumberField::new('price');
        yield TextareaField::new('description')->hideOnIndex();
    }
}
