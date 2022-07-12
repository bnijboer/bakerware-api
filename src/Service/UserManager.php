<?php

namespace App\Service;

use App\Repository\UserRepository;

class UserManager
{
    private $repository;
    
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
    
    public function find(int $id)
    {
        return $this->repository->find($id);
    }
}