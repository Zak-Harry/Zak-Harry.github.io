<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;



class PlanningVoter extends Voter
{
    public const EDIT = 'PLANNING_EDIT';
    public const VIEW = 'PLANNING_VIEW';
    public const VIEWTEAM = 'PLANNING_VIEWTEAM';



    protected function supports(string $attribute, $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        if($attribute === self::EDIT || $attribute === self::VIEW || $attribute === self::VIEWTEAM)
        {
            return true;
        }
        return false;

    }

    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
                // logic to determine if the user can EDIT
                // return true or false
                if($this->security->isGranted('ROLE_RH')){ return true;};
                break;
            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                if($this->security->isGranted('ROLE_USER')){ return true;};
                break;
            case self::VIEWTEAM:
                // logic to determine if the user can VIEW
                // return true or false
                if($this->security->isGranted('ROLE_MANAGER')){ return true;};

                break;    
        }

        return false;
    }
}
