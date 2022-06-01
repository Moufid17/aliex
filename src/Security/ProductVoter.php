<?php

namespace App\Security;


use App\Entity\Product;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ProductVoter extends Voter
{
    // these strings are just invented: you can use anything
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports(string $attribute, $subject): bool
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::VIEW, self::EDIT])) {
            return false;
        }

        // only vote on `Product` objects
        if (!$subject instanceof Product) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Product object, thanks to `supports()`
        /** @var Product $product */
        $product = $subject;

        switch ($attribute) {
            case self::EDIT:
                return $user === $subject->getOwner() || in_array('ROLE_ADMIN', $user->getRoles());
            // case self::EDIT:
            //     return $this->canEdit($product, $user);
        }

        throw new \LogicException('Access Denied!');
    }

    // private function canView(Product $product, User $user): bool
    // {
    //     // if they can edit, they can view
    //     if ($this->canEdit($product, $user)) {
    //         return true;
    //     }

    //     // the Product object could have, for example, a method `isPrivate()`
    //     // return !$product->isPrivate();
    // }

    // private function canEdit(Product $product, User $user): bool
    // {
    //     // this assumes that the Product object has a `getOwner()` method
    //     return $user === $product->getOwner();
    // }
}