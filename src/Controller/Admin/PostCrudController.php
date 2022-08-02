<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use DateTime;
use App\Entity\Post;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PostCrudController extends AbstractCrudController
{

    public function __construct(private CategoryRepository $catRepo){}


    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Article')
                    ->setEntityLabelInPlural('Articles')
                    ->setDateTimeFormat('medium')
                    ->setPaginatorPageSize(10)
                    ->setPaginatorRangeSize(4)
                    ;
    }

    public function createEntity(string $entityFqcn): Post
    {
        $post = new Post;
        $post->setCreatedAt(new DateTime());

        return $post;
    }

    // public function persistEntity(EntityManagerInterface $entityManager, $entityInstance)
    // {
    //     $entityInstance->setCategory($this->catRepo->find($entityInstance->getCategory()));
        
    //     $entityManager->persist($entityInstance);
    //     $entityManager->flush();
    // }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextEditorField::new('content'),
            DateTimeField::new('createdAt')->hideOnForm(),
            AssociationField::new('category')->setQueryBuilder(fn (QueryBuilder $qb) => $qb->getEntityManager()->getRepository(Category::class)->findAll()),
            ImageField::new('picture')->setUploadDir('/public/assets/img/upload/')
            
        ];
    }

}
