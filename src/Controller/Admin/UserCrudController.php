<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use SSymfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface as HasherUserPasswordHasherInterface;
use EasyCorp\Bundle\EasyAdminBundle\Orm\EntityRepository;

/** 
 * @method User getUser
 * */
class UserCrudController extends AbstractCrudController
{
    public function __construct(
      private HasherUserPasswordHasherInterface $passwordHasher,
      private EntityRepository $entityRepo
    )
    {
        
    }
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $userId = $this->getUser()->getId();

        $qb = $this->entityRepo->createQueryBuilder($searchDto, $entityDto, $fields , $filters);
        $qb->andWhere('entity.id != :userId')->setParameter('userId', $userId);

        return $qb;
    }

  
    public function configureFields(string $pageName): iterable
    {

        yield TextField::new('username');

        yield TextField::new('password')->onlyOnForms()
        ->setFormType(PasswordType::class);

        yield ChoiceField::new('roles')
            ->allowMultipleChoices()
            ->renderAsBadges([
                'ROLE_ADMIN' => 'success',
                'ROLE_AUTHOR' => 'warning'
            ])
            ->setChoices([
                'Administrateur' => 'ROLE_ADMIN',
                'Auteur' => 'ROLE_AUTHOR'
            ]);
           
    }

    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
         /**
         * @var User $user 
         */
        $user = $entityInstance ;

        $plainPassword = $user->getPassword();
        $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);

        $user->setPassword($hashedPassword);

        parent::persistEntity($entityManager, $entityInstance);
    }
   
}
