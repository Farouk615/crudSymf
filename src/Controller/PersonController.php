<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\AnimalRepository;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;


use phpDocumentor\Reflection\DocBlock\Tags\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Constraints\Json;

class PersonController extends AbstractController
{
    #[Route('/person', name: 'person',methods: 'GET')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/PersonController.php',
        ]);
    }
    public function getPersons(PersonRepository $personRepository,SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse($serializer->serialize($personRepository->findAll(),"json"),JsonResponse::HTTP_OK,[],true);
    }

    public function create(Request $request,EntityManagerInterface $entityManager,SerializerInterface $serializer):JsonResponse
    {
//        $person=new Person();
//        $person->setName("karim");
//        $person->setAge(59);
//        $person->setJob("mrameji");
//        $em=$this->getDoctrine()->getManager();
//        $em->persist($person);
//        $em->flush();
//        return $person;

          $person=new Person();
          $person=$serializer->deserialize($request->getContent(),Person::class,"json");
         $form=$this->createForm(PersonType::class,$person);
         $form->handleRequest($request);
             $entityManager->persist($person);
             $entityManager->flush();
             return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_OK,[],true);


//        $person=$serializer->deserialize($request->getContent(),Person::class,"json");
//        $entityManager->persist($person);
//        $entityManager->flush();
//        return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_CREATED,[],true);
    }
        public function item(int $id,SerializerInterface $serializer,PersonRepository $personRepository){
             $person=$personRepository->find($id);
            return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_OK,[],true);
        }

        public function update(Person $person,SerializerInterface $serializer,Request $request,EntityManagerInterface $entityManager,PersonRepository $personRepository):JsonResponse
        {
            //dd($request->getContent());
//            $person2=$serializer->deserialize($request->getContent(),Person::class,'json');
//            $form = $this->createForm(PersonType::class,$person2);
//            $form->handleRequest($request);
//            if ($form->isSubmitted() && $form->isValid())
//                $entityManager->flush();
//            return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_OK,[],true);
            $data = json_decode($request->getContent(), true);
            empty($data['name']) ? true : $person->setName($data['name']);
            empty($data['age']) ? true : $person->setAge($data['age']);
            empty($data['job']) ? true : $person->setJob($data['job']);
            $entityManager->persist($person);
            $entityManager->flush();

            return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_OK,[],true);
        }



        public function delete(Person $person,EntityManagerInterface $entityManager,PersonRepository $personRepository,Request $request,SerializerInterface $serializer):JsonResponse
        {
                $entityManager->remove($person);
                $entityManager->flush();
            return new JsonResponse($serializer->serialize($person,"json"),JsonResponse::HTTP_OK,[],true);
        }
}
