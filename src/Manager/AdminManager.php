<?php


namespace App\Manager;


use App\Entity\Admin;
use App\Entity\User;
use App\Repository\AdminRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AdminManager
{
    private $adminRepository;
    private $entityManager;
    private $passwordEncoder;

    public function __construct(AdminRepository $adminRepository,
                                EntityManagerInterface $entityManager,
                                UserPasswordEncoderInterface $passwordEncoder
    )
    {
        $this->adminRepository = $adminRepository;
        $this->entityManager = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function addAdmin(Admin $admin)
    {
        $password = $this->passwordEncoder->encodePassword($admin, $admin->getPassword());
        $admin->setPassword($password);
        $this->entityManager->persist($admin);
        $this->entityManager->flush();
    }

}