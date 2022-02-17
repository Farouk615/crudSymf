<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class PersonController extends AbstractController
{
    #[Route('/getPersons', name: 'all_persons', methods: ['GET'])]
    public function getPersons(PersonRepository $personRepository,SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse($serializer->serialize($personRepository->findAll(),"json"),JsonResponse::HTTP_OK,[],true);
    }
    public function getPerson(PersonRepository $personRepository,SerializerInterface $serializer,int $id): JsonResponse
    {
        return new JsonResponse($serializer->serialize($personRepository->findOneBy($id),"json"),JsonResponse::HTTP_OK,[],true);
    }

    #[Route('/make', name: 'create_person', methods: ['POST'])]
    public function create(Request $request,EntityManagerInterface $entityManager,SerializerInterface $serializer):JsonResponse
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->submit(json_decode($request->getContent(), true));
        if ($form->isSubmitted()) {
            $entityManager->persist($person);
            $entityManager->flush();
            return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_CREATED,[],true);

        }
        return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_CREATED,[],true);
    }

    #[Route('/getPerson/{id}', name: 'person_by_id', methods: ['GET'])]
    public function item(int $id,SerializerInterface $serializer,PersonRepository $personRepository){
             $person=$personRepository->find($id);
            return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_OK,[],true);
        }

    #[Route('/update/{id}', name: 'updating_person', methods: ['PATCH'])]
    public function update(Person $person,SerializerInterface $serializer,Request $request,EntityManagerInterface $entityManager,PersonRepository $personRepository):JsonResponse
        {
            $form = $this->createForm(PersonType::class, $person);
            $form->submit(json_decode($request->getContent(), true),false);
            if ($form->isSubmitted()) {
                $entityManager->persist($person);
                $entityManager->flush();
                return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_CREATED,[],true);
            }
            return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_CREATED,[],true);
        }


        #[Route('/delete/{id}', name: 'delete_person', methods: ['DELETE'])]
        public function delete(Person $person,EntityManagerInterface $entityManager,PersonRepository $personRepository,Request $request,SerializerInterface $serializer):JsonResponse
        {
                $entityManager->remove($person);
                $entityManager->flush();
            return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_OK,[],true);
        }
}
