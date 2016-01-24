<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace AppBundle\Controller;

use AppBundle\Form\UserType;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\redirectToRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class UserController extends Controller
{
    /**
     * @Route("/user/list", name="list_user")
     * @Security("has_role('ROLE_USER')")
     */
    public function listAction()
    {

    	$users = $this->getDoctrine()
	        ->getRepository('AppBundle:User')
	        ->findAll();

	    if (!$users) {
	        $this->addFlash(
                'notice',
                'No user to display!'
            );
	    }

        return $this->render(
        	'user/list.html.twig',
        	array('users' => $users)
        );
    }

    /**
     * @Route("/user/new", name="new_user")
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(new UserType(), $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // ... do any other work - like send them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('list_user');
        }

        return $this->render(
            'user/new.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/user/delete/{user_id}", name="del_user")
     * @Security("has_role('ROLE_USER')")
     */
    public function delAction(Request $request, $user_id)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($user_id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('list_user');
    }
}

?>