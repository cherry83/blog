<?php

namespace SoftUniBlogBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SoftUniBlogBundle\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class IndexController extends Controller
{
    /**
     * @Route("/", name="blog_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('blog/index.html.twig', ['categories'=>$categories]);
    }

    /**
     * @Route("/category/{id}", name="category_articles")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listArticles($id){
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        $articles = $category->getArticles()->toArray();

        return $this->render('article/list.html.twig',['articles'=>$articles]);
    }
}
