<?php
/**
 * Created by PhpStorm.
 * User: Valera
 * Date: 01.01.2015
 * Time: 12:02
 */
namespace Blogger\BlogBundle\Controller;
use Blogger\BlogBundle\Entity\Enquiry;
use Blogger\BlogBundle\Form\EnquiryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
class PageController extends Controller{
    public function indexAction(){

        $em=$this->getDoctrine()->getManager();
        $blogs=$em->createQueryBuilder()->select('b')->from('BloggerBlogBundle:Blog','b')->addOrderBy('b.created','Desc') ->getQuery()
            ->getResult();

        return $this->render('BloggerBlogBundle:Page:index.html.twig', array(
            'blogs' => $blogs
        ));
    }
    public function aboutAction(){
        return $this->render('BloggerBlogBundle:Page:about.html.twig');
    }
    public function contactAction(Request $request){

        $enquiry=new Enquiry();

        $form=$this->createForm(new EnquiryType(),$enquiry);

        if($request->getMethod()=='POST'){
            $form->handleRequest($request);
            if($form->isValid()){
                $message = \Swift_Message::newInstance()
                    ->setSubject('Contact enquiry from symblog')
                    ->setFrom('enquiries@symblog.co.uk')
                    ->setTo($this->container->getParameter('blogger_blog.emails.contact_email'))
                    ->setBody($this->renderView('BloggerBlogBundle:Page:contactEmail.txt.twig', array('enquiry' => $enquiry)));
                    $this->get('mailer')->send($message);
                $this->get('session')->getFlashBag()->add(
                    'blogger-notice',
                    'Ваш запрос успешно отправлен. Спасибо!');

                // Редирект - это важно для предотвращения повторного ввода данных в форму,
                // если пользователь обновил страницу.
                return $this->redirect($this->generateUrl('BloggerBlogBundle_contact'));
            }
        }

        return $this->render('BloggerBlogBundle:Page:contact.html.twig', array(
        'form' => $form->createView()));
    }

} 