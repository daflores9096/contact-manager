<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use App\Service\ContactService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/contacts")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="contact_index")
     */
    public function index(ContactRepository $repository): Response
    {
        $contacts = $repository->findBy([
            'owner' => $this->getUser()
        ]);

        return $this->render('contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    /**
     * @Route("/{id}", name="contact_show", requirements={"id"="\d+"}, methods={"GET"})
     */
    public function show(
        int $id,
        ContactRepository $repository
    ): Response {
        $contact = $repository->find($id);

        if (!$contact) {
            throw $this->createNotFoundException();
        }

        // Seguridad: solo el dueño puede ver el contacto
        if ($contact->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
        ]);
    }

    /**
     * @Route("/new", name="contact_new")
     */
    public function new(
        Request $request,
        ContactService $contactService
    ): Response {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactService->create($contact, $this->getUser());

            return $this->redirectToRoute('contact_index');
        }

        return $this->render('contact/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="contact_edit")
     */
    public function edit(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        ContactRepository $repository,
        ContactService $contactService
    ): Response {
        $contact = $repository->find($id);

        if (!$contact) {
            throw $this->createNotFoundException();
        }

        // Seguridad: solo el dueño puede editar
        if ($contact->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactService->update();

            return $this->redirectToRoute('contact_index');
        }


        return $this->render('contact/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="contact_delete", methods={"POST"})
     */
    public function delete(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        ContactRepository $repository,
        ContactService $contactService
    ): Response {
        $contact = $repository->find($id);

        if (!$contact) {
            throw $this->createNotFoundException();
        }

        // Seguridad: solo el dueño puede borrar
        if ($contact->getOwner() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $contactService->delete($contact);
        }

        return $this->redirectToRoute('contact_index');
    }

}
