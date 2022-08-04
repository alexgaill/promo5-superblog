<?php
namespace App\Tests\Form;

use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;

class CategoryTypeTest extends TypeTestCase {


    protected function getExtensions()
    {
        // or if you also need to read constraints from annotations
        $validator = Validation::createValidatorBuilder()
            ->enableAnnotationMapping(true)
            ->addDefaultDoctrineAnnotationReader()
            ->getValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }
    
    public function testSubmitValidData()
    {
        // Tableau de fausses données pour tester
        $formData = [
            'title' => 'Titre'
        ];
        // Catégorie qui sera surchargée par le formulaire
        $category = new Category;
        // Catégorie attendue à la sortie du formulaire
        $expected = (new Category)->setTitle($formData['title']);

        // On génère le formulaire auquel on associe la catégorie à surcharger
        $form = $this->factory->create(CategoryType::class, $category);
        // On soumet au formulaire notre tableau de fausses données
        $form->submit($formData);

        // On exécute un test afin de s'assurer que le formulaire soit fonctionnel (aucune due à un data transformer)
        $this->assertTrue($form->isSynchronized());
        // On exécute un test afin de vérifier que la catégorie en sortie de formulaire soit bien complétée
        $this->assertTrue($form->isSubmitted() && $form->isValid());
        $this->assertEquals($expected, $category);
    }

    public function testSubmitUnvalidConstraintsData()
    {
        $formData = [
            'title' => 'Ti'
        ];
        $category = new Category;
        $expected = (new Category)->setTitle($formData['title']);

        $form = $this->factory->create(CategoryType::class, $category);
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertFalse($form->isSubmitted() && $form->isValid());
    }

}