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

class AttendanceController extends Controller
{
    /**
     * @Route("/attendance", name="list_attendance")
     * @Security("has_role('ROLE_USER')")
     */
    public function listAction()
    {
    	$events = $this->getDoctrine()
	        ->getRepository('AppBundle:Event')
	           ->findBy(array(), array('date' => 'ASC'));;

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

        return $this->render(
        	'attendance/attendance.html.twig',
        	array('to_list' => $to_list,
                    'to_ret' => $to_ret)
        );
    }
}

?>