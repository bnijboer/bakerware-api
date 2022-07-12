<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/users", methods={"POST"})
     */
    public function store(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        $data = json_decode($request->request->get('user'));

        $user = new User();
        $user->setFirstName($data->firstName);
        $user->setLastName($data->lastName);
        
        try {
            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
            $data = [
                'message' => 'User saved successfully!'
            ];
        } catch (\Exception $e) {
            $data = [
                'message' => "Cannot save user! Exception thrown: {$e->getMessage()}"
            ];
        }
        
        $headers = [
            'Content-type' => 'application/json',
            'Access-Control-Allow-Origin' => 'http://localhost:3000'
        ];
        
        return new JsonResponse($data, 200, $headers);
    }
    
    /**
     * @Route("/users/{id}", methods={"GET"})
     */
    public function show(int $id, UserManager $userManager): JsonResponse
    {
        $user = $userManager->find($id);
        
        if (! $user) {
            $data = [
                'message' => "Cannot find user with ID: {$id}."
            ];
        } else {
            $data = [
                'user' => [
                    'firstName' => $user->getFirstName(),
                    'lastName' => $user->getLastName()
                ],
                'message' => 'Successfully found user with the UserManager service!'
            ];
        }
        
        $headers = [
            'Content-type' => 'application/json',
            'Access-Control-Allow-Origin' => 'http://localhost:3000'
        ];
        
        return new JsonResponse($data, 200, $headers);
    }
}
