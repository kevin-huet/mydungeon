<?php


namespace App\Form;


use App\Entity\WoW\WarcraftCharacter;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DungeonGroupRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dps', CheckboxType::class, [
                'required' => false,

            ])
            ->add('tank', CheckboxType::class, [
                'required' => false,

            ])
            ->add('heal', CheckboxType::class, [
                'required' => false,
            ])
            ->add('character', EntityType::class, [
                'class' => WarcraftCharacter::class,
                'query_builder' => function (EntityRepository $er) use ($options) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.blizzardUser = :user')
                        ->setParameter('user', $options['user'])
                        ->orderBy('u.playableClass', 'ASC');
                },


                'choice_label' => function (WarcraftCharacter $character) {
                    $path = 'class.json';
                    $content = file_get_contents($path);
                    $json = json_decode($content, true);
                    foreach ($json as $item) {
                        if ($character->getPlayableClass() == $item)
                            return $character->getName() . ' - ' . $character->getRealm() . ' - '.$item['name'];
                    }
                    return $character->getName() . ' - ' . $character->getRealm();
                },
            ])
            ->add('message', TextareaType::class, [])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'user' => 0,
        ]);
        $resolver->setAllowedTypes('user', 'int');
    }
}
