<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\Blog;
use App\Entity\User;
use LogicException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

use function in_array;

class BlogVoter extends Voter
{
    public const VIEW_BLOG = 'view';
    public const EDIT_BLOG = 'edit';

    /**
     * @param Security $security
     */
    public function __construct(private readonly Security $security)
    {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW_BLOG, self::EDIT_BLOG])) {
            return false;
        }

        if (!$subject instanceof Blog) {
            return false;
        }

        return true;
    }

    /**
     * @param string $attribute
     * @param Blog $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        /* @var Blog $blog */
        $blog = $subject;

        return match ($attribute) {
            self::VIEW_BLOG => $this->canView($blog, $user),
            self::EDIT_BLOG => $this->canEdit($blog, $user),
            default => throw new LogicException('This code should not be reached!')
        };
    }

    /**
     * @param Blog $blog
     * @param User $user
     * @return bool
     */
    private function canView(Blog $blog, User $user): bool
    {
        return true;
    }

    /**
     * @param Blog $blog
     * @param User $user
     * @return bool
     */
    private function canEdit(Blog $blog, User $user): bool
    {
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $user === $blog->getUser();
    }
}
