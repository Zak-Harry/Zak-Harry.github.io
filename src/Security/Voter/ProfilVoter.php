<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Form\ProfilType;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ProfilVoter extends Voter
{
    public const EDIT = 'EDIT';
    public const VIEW = 'VIEW';
    public const CREATE = 'CREATE';

    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param string $attribute
     * @param $subject
     * @return bool
     */
    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        if($attribute === self::EDIT || $attribute === self::VIEW || $attribute === self::CREATE)
        {
            return true;
        }
        return false;
    }

    /**
     * @param string $attribute
     * @param $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {

        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                if($subject->getviewData()->getid() === $user->getid() || $this->security->isGranted("ROLE_RH")){ return true;};
                if($subject->getviewData()->getid() === $user->getid() || $this->security->isGranted("ROLE_RH")){ return true;};
                break;
            case self::CREATE:
                if($this->security->isGranted("ROLE_RH")){ return true;};
                break;
            case self::VIEW:
                if($this->security->isGranted('ROLE_USER')){ return true;};
                break;
        }
        return false;
    }
}
