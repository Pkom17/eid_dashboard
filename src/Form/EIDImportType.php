<?php

#src/Form/EIDImportType.php

namespace App\Form;

use App\Entity\EIDImport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

/**
 * Description of EIDFormType
 *
 * @author PKom
 */
class EIDImportType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('file', FileType::class, [
                    'label' => 'EID Export file (CSV file)',
                    'mapped' => false,
                    'required' => true,
                    'constraints' => [
                        new File([
                            'maxSize' => '8M',
                            'mimeTypes' => [
                                'text/plain',
                                'text/csv',
                                'application/csv',
                                'application/x-csv',
                                'text/comma-separated-values',
                                'text/x-comma-separated-values',
                                'text/tab-separated-values',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid CSV file',
                                ])
                    ],
                ])
                ->add('save', SubmitType::class,[
                    'label' => 'Importer',
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults([
            'data_class' => EIDImport::class,
        ]);
    }

}
