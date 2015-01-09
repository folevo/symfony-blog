<?php
/**
 * Created by PhpStorm.
 * User: Valera
 * Date: 03.01.2015
 * Time: 19:30
 */

namespace Blogger\BlogBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BlogController extends Controller{
    public function showAction($id){
        $em = $this->getDoctrine()
            ->getManager();

        $blogs = $em->getRepository('BloggerBlogBundle:Blog')
            ->getLatestBlogs();
        if(!$blogs){
           throw $this->createNotFoundException('Не удается найти сообщение блога.');
        }
        return $this->render('BloggerBlogBundle:Blog:show.html.twig', array(
            'blogs'      => $blogs,
        ));

    }
} 