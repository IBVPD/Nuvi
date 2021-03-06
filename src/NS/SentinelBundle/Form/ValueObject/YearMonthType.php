<?php
/**
 * Created by PhpStorm.
 * User: gnat
 * Date: 27/02/17
 * Time: 12:36 PM
 */

namespace NS\SentinelBundle\Form\ValueObject;

use NS\SentinelBundle\Entity\ValueObjects\YearMonth;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class YearMonthType extends AbstractType implements DataMapperInterface
{
    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('year', null, ['label' => 'ibd-form.date-of-birth-years', 'required' => $options['required']])
            ->add('month', null, ['label' => 'ibd-form.date-of-birth-months', 'required' => $options['required']]);

        $builder->setDataMapper($this);
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => YearMonth::class,
            'compound' => true,
            'empty_data' => function (FormInterface $form) {
                return new YearMonth($form->get('year')->getData(), $form->get('month')->getData());
            }
        ]);
    }

    /**
     * @inheritDoc
     */
    public function mapDataToForms($data, $forms)
    {
        if ($data instanceof YearMonth) {
            /** @var FormInterface[] $forms */
            $forms = iterator_to_array($forms);

            $forms['year']->setData($data->getYear());
            $forms['month']->setData($data->getMonth());
        }
    }

    /**
     * @inheritDoc
     */
    public function mapFormsToData($forms, &$data)
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        $data = new YearMonth($forms['year']->getData(), $forms['month']->getData());
    }
}
