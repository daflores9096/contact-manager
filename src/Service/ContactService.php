<?php

namespace App\Service;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ContactService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function create(Contact $contact, User $owner): void
    {
        $contact->setOwner($owner);
        $contact->setCreatedAt(new \DateTime());

        $this->em->persist($contact);
        $this->em->flush();
    }

    public function update(): void
    {
        $this->em->flush();
    }

    public function delete(Contact $contact): void
    {
        $this->em->remove($contact);
        $this->em->flush();
    }
}
