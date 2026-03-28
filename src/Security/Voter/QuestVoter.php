<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Vote;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class QuestVoter extends Voter
{
    public const EDIT = 'QUEST_EDIT';
    public const REGISTER = 'QUEST_REGISTER';
    public const UNREGISTER = 'QUEST_UNREGISTER';
    public const DELETE = 'QUEST_DELETE';
    public const CANCEL = 'QUEST_CANCEL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE, self::REGISTER, self::UNREGISTER, self::REGISTER, self::CANCEL])
            && $subject instanceof \App\Entity\Quest;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token, ?Vote $vote = null): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            $vote?->addReason('L\'utilisateur doit être connecté');
            return false;
        }


        /** @var Quest $quest */
        $quest = $subject;



        switch ($attribute) {
            case self::EDIT:

                if (in_array('ROLE_ADMIN', $user->getRoles())) {
                    return $quest->getStatus()->getLabel() !== 'Archive';
                }

                if (null === $quest->getId()) {
                    return true;
                }

                if ($user === $quest->getPromoter()) {
                    return in_array($quest->getStatus()->getLabel(), ['Ouverte', 'En création']);
                }

                return false;


            case self::REGISTER:
                return $quest->getStatus()->getLabel() === 'Ouverte'
                    && !$quest->getUsers()->contains($user)
                    && $quest->getUsers()->count() < $quest->getNbMaxInscription();


            case self::UNREGISTER:
                return $quest->getUsers()->contains($user)
                && in_array($quest->getStatus()->getLabel(), ['Ouverte', 'Cloturée']);


            case self::DELETE:
                return $quest->getPromoter() === $user || in_array('ROLE_ADMIN', $user->getRoles());

            case self::CANCEL:
                return ($quest->getPromoter() === $user || in_array('ROLE_ADMIN', $user->getRoles())) && $quest->getStatus()->getLabel() != 'Annulée';

        }

        return false;
    }

}
