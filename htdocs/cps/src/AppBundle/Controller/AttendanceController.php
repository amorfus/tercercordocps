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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Intl\Intl;

class AttendanceController extends Controller
{
    /**
     * @Route("/attendance/list", name="list_attendance")
     * @Security("has_role('ROLE_USER')")
     */
    public function listAction()
    {
    	$events = $this->getDoctrine()
	        ->getRepository('AppBundle:Event')
	           ->findBy(array(), array('date' => 'ASC'));

	    if (!$events) {
	        $this->addFlash(
                'notice',
                'No events to display!'
            );
	    }
            foreach ($events as $event) {
                $to_ret[] = $event->getDate()->format('m');
                switch ($event->getDate()->format('m')) {
                    case '01':
                        $to_list['gennaio'][] = $event;
                        break;
                    case '02':
                        $to_list['febbraio'][] = $event;
                        break;
                    case '03':
                        $to_list['marzo'][] = $event;
                        break;
                    case '04':
                        $to_list['aprile'][] = $event;
                        break;
                    case '05':
                        $to_list['maggio'][] = $event;
                        break;
                    case '06':
                        $to_list['giugno'][] = $event;
                        break;
                    case '07':
                        $to_list['luglio'][] = $event;
                        break;
                    case '08':
                        $to_list['agosto'][] = $event;
                        break;
                    case '09':
                        $to_list['settembre'][] = $event;
                        break;
                    case '10':
                        $to_list['ottobre'][] = $event;
                        break;
                    case '11':
                        $to_list['novembre'][] = $event;
                        break;
                    case '12':
                        $to_list['dicembre'][] = $event;
                        break;
                    
                    default:
                        # code...
                        break;
                }
            }

            $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render(
        	'attendance/list_attendance.html.twig',
        	array(
                'to_list' => $to_list,
                'to_ret'  => $to_ret,
                'user'    => $user
            )
        );
    }

    /**
     * @Route("/attendance/show", name="show_attendance")
     * @Security("has_role('ROLE_USER')")
     */
    public function showAction(Request $request)
    {
        $event_list = $this->getDoctrine()
        ->getRepository('AppBundle:Event')
           ->findBy(array(), array('date' => 'ASC'));    

        if ($request->request->get('event_id')){
            $event_id = $request->request->get('event_id');
            $event = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
               ->findBy(array( 'id' => $event_id ))[0];
        } else {
            $event = $event_list[0];
        }

        return $this->render(
            'attendance/show_attendance.html.twig',
            array(
                'event_selected' => $event,
                'event_list'  => $event_list
            )
        );
    }

    /**
     * @Route("/attendance/{action}/{user_id}/{event_id}", name="add_attendance")
     * @Security("has_role('ROLE_USER')")
     */
    public function changeAction($action, $user_id, $event_id, $action)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
               ->findBy(array('id' => $user_id));

        $event = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
               ->findBy(array('id' => $event_id));

        if(isset($user[0]) && isset($event[0])){
            switch ($action) {
                case 'add':
                    $user[0]->addEvent($event[0]);
                    break;
                case 'remove':
                    $user[0]->removeEvent($event[0]);
                    break;
                default:
                    
                    break;
            }

            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $to_ret = array('result' => 'ok');
        }else{
            $to_ret = array('result' => 'error');
        }

        $response = new Response(json_encode($to_ret));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}

?>