<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Factory\UserFactory;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FilterCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\SearchDto;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;

class UserCrudController extends AbstractCrudController
{
    public function __construct(private readonly UserFactory $userFactory)
    {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function createIndexQueryBuilder(SearchDto $searchDto, EntityDto $entityDto, FieldCollection $fields, FilterCollection $filters): QueryBuilder
    {
        $queryBuilder = AbstractCrudController::createIndexQueryBuilder($searchDto, $entityDto, $fields, $filters);

        return $queryBuilder
            ->andWhere('entity.roles NOT LIKE :role')->setParameter('role', '%ROLE_ADMIN%')
        ;
    }

    public function configureActions(Actions $actions): Actions
    {
        $generateUser = Action::new('generateUser', 'Générer un utilisateur')
            ->linkToCrudAction('generateUser');

        return $actions
            ->add(Crud::PAGE_INDEX, $generateUser);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username', 'Identifiant'),
            TextField::new('plainPassword', 'Mot de passe'),
            TextField::new('firstname', 'Prénom'),
            TextField::new('lastname', 'Nom'),
            TextField::new('phone', 'Téléphone'),
            BooleanField::new('enable', 'Actif'),
            TextEditorField::new('remark', 'Remarque'),
            DateField::new('createdAt', 'Créé le')
                ->hideOnForm(),
        ];
    }

    public function generateUser(AdminContext $context): Response
    {
        /** @var User $user */
        $user = $context->getEntity()->getInstance();
        $this->userFactory->generateUser($user);

        $this->addFlash('success', 'Utilisateur généré avec succès');

        return $this->redirect(
            $this->container->get(AdminUrlGenerator::class)
                ->setAction(Action::INDEX)
                ->generateUrl()
        );
    }
}
