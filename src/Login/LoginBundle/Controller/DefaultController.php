<?php

namespace Login\LoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Login\LoginBundle\Entity\Users;
use Login\LoginBundle\Modals\Login;
use Guzzle\Service\Client;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $session = $this->getRequest()->getSession();
        $em = $this->getDoctrine()->getEntityManager();
        $repository = $em->getRepository('LoginLoginBundle:Users');

        if ($request->getMethod() == 'POST'){
            $username = $request->get('username');
            $password = $request->get('password');


            $user = $repository->findOneBy(array('userName' => $username, 'password' => $password));
            if($user){
                //Save the user to session
                $login = new Login();
                $login->setUsername($username);
                $login->setPassword($password);
                $session->set('login', $login);

                return $this->render('LoginLoginBundle:Default:search.html.twig', array('name' => $user->getFirstName()));
            } else {
                return $this->render('LoginLoginBundle:Default:index.html.twig', array('name' => 'Login failed'));
            }
        } else {
            if ($session->has('login')){
                $login = $session->get('login');
                $username = $login->getUsername();
                $password = $login->getPassword();
                $user = $repository->findOneBy(array('userName' => $username, 'password' => $password));
                if($user){
                    return $this->render('LoginLoginBundle:Default:search.html.twig', array('name' => $user->getFirstName()));
                }
            }
            return $this->render('LoginLoginBundle:Default:index.html.twig');
        }
    }

    public function searchAction(Request $request)
    {

        //Send user to login if the session is not set
        $session = $this->getRequest()->getSession();
        if ($session->get('login')){
            return $this->render('LoginLoginBundle:Default:search.html.twig');
        } else {
            return $this->render('LoginLoginBundle:Default:index.html.twig');
        }

    }

    public function logoutAction(Request $request)
    {
        $session = $this->getRequest()->getSession();
        $session->clear();

        return $this->render('LoginLoginBundle:Default:index.html.twig');

    }

    public function resultAction(Request $request)
    {
        $session = $this->getRequest()->getSession();
        if ($session->has('login')){
            if ($request->getMethod() == 'POST'){
                $search_username = $request->get('search_username');

                $client = new \Guzzle\Service\Client();
                $res = $client->get('https://api.github.com/users/'.$search_username.'/repos', ['auth' =>  ['user', 'pass']]);
                $response = $res->send();

                return $this->render('LoginLoginBundle:Default:result.html.twig', array('data' => $response->json()));

            } else {
                return $this->render('LoginLoginBundle:Default:search.html.twig');
            }
        } else {
            return $this->render('LoginLoginBundle:Default:index.html.twig');
        }
    }
}
