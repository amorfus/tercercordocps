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

use AppBundle\Form\EventType;
use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\redirectToRoute;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Intl\Intl;

class EventController extends Controller
{
    /**
     * @Route("/event/list", name="list_event")
     * @Security("has_role('ROLE_USER')")
     */
    public function listAction()
    {

    	$events = $this->getDoctrine()
	        ->getRepository('AppBundle:Event')
	        ->findAll();

	    if (!$events) {
	        $this->addFlash(
                'notice',
                'No events to display!'
            );
	    }

        return $this->render(
        	'event/list.html.twig',
        	array('events' => $events)
        );
    }

    /**
     * @Route("/event/new", name="new_event")
     * @Security("has_role('ROLE_USER')")
     */
    public function newAction(Request $request)
    {
        // 1) build the form
        $event = new Event();
        $form = $this->createForm(new EventType(), $event);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            // 4) save the User!
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

            // ... do any other work - like send them an email, etc
            // maybe set a "flash" success message for the user

            return $this->redirectToRoute('list_event');
        }

        return $this->render(
            'event/new.html.twig',
            array('form' => $form->createView())
        );
    }

    /**
     * @Route("/event/delete/{event_id}", name="del_event")
     * @Security("has_role('ROLE_USER')")
     */
    public function delAction(Request $request, $event_id)
    {
        $event = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->find($event_id);

        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('list_event');
    }
}

?>