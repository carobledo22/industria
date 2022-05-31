<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Usuario;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Form\LoginFormType;


class PublicController extends AbstractController
{
    /**
     * @Route("/index", name="app_homepage")
     * @IsGranted("ROLE_USER")
     */
    public function homepage(EntityManagerInterface $em, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        
        $usuario = $em->getRepository(Usuario::class)->findOneBy(['usuario' => "gespinosa"]);
        /*
        $usuario->setPassword($passwordEncoder->encodePassword(
            $usuario,
            'gustavo'
        ));  

        $em->persist($usuario);
        $em->flush();*/


        return $this->render('public/homepage.html.twig', [
            'controller_name' => 'PublicController',
            'clave' => $usuario->getPassword(),
            'usuario' => $usuario->getUsuario(),
        ]);
    }

    /**
     * @Route("/admin", name="admin_principal")
     * @IsGranted("ROLE_USER")
     */
    
    //la notacion @IsGranted() funciona con la funcion dentro del controlador denyAccessUnlessGranted()
    //si la notacion @IsGranted() va sobre la clase, todos sus metodos requeriran el rol argumento
    public function redireccion(): Response
    {
        //deniega el acceso a los usuarios con ese rol desde el controlador
        //$this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('public/admin_principal.html.twig', []);
    }

    /**
     * @Route("/login_alternativo", name="app_homepagealt")
     */

     public function loginExtra(): Response
     {
        //crea y luego guarda una instancia de la clase LoginFormType
        $form = $this->createForm(LoginFormType::class);

        return $this->render('public/homepagealt.html.twig', [
            //la vista no se puede pasar directamente si no que se llama siempre a
            //createView() que transforma el objeto de tipo Form 
            'loginForm' => $form->createView()
        ]);
     }
}
