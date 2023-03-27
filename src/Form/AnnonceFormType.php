<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Product;
use App\Entity\Categorie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FileToPhotoTransformer implements DataTransformerInterface
{
    private $uploadsDirectory;

    public function __construct($uploadsDirectory)
    {
        $this->uploadsDirectory = $uploadsDirectory;
    }

    public function transform($value)
    {
        return null;
    }

    public function reverseTransform($value)
    {
        if (!$value instanceof UploadedFile) {
            return;
        }

        $fileName = uniqid() . '.' . $value->guessExtension();

        try {
            $value->move($this->uploadsDirectory, $fileName);
        } catch (FileException $e) {
            throw new TransformationFailedException(sprintf(
                'An error occurred while uploading the file: %s',
                $e->getMessage()
            ));
        }

        return $fileName;
    }
}
class AnnonceFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',TextType::class,[
                'label' => 'Titre',
                'attr' => [
                    'placeholder' => 'Titre de l\'annonce...'
                ]
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
            ])
            ->add('product', TextType::class, [
                'mapped' => false,
                'label' => 'Produit',
            ])
            ->add('product_description', TextType::class, [
                'mapped' => false,
                'label' => 'Description',
            ])
            ->add('price', TextType::class, [
                'mapped' => false,
                'label' => 'Prix',
            ])
            ->add('photo', FileType::class, [
                'label' => 'Image',
                'required' => false,
                'mapped' => false,
                'data_class' => null,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Poster'
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}
