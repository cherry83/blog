<?php

namespace SoftUniBlogBundle\Controller\Admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use SoftUniBlogBundle\Entity\Category;
use SoftUniBlogBundle\Form\CategoryType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CategoryController
 * @Route("/admin/categories")
 * @package SoftUniBlogBundle\Controller\Admin
 */
class CategoryController extends Controller
{
    /**
     * @Route("/", name="admin_categories")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listCategories()
    {
        $categories = $this->getDoctrine()->getRepository(Category::class)->findAll();

        return $this->render('admin/categories/list.html.twig', array('categories' => $categories));
    }

    /**
     * @Route("/create", name="admin_categories_create")
     * @param Request $request
     * @return Response
     */
    public function createCategory(Request $request)
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted()  && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('admin_categories');
        }

        return $this->render('admin/categories/create.html.twig', ['form' => $form->createView()]);

    }

    /**
     * @Route("/edit/{id}", name="admin_categories_edit")
     * @param $id
     * @param Request $request
     * @return Response
     */
    public function editCategory($id, Request $request){
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if($category===null){
            return $this->redirectToRoute("admin_categories");
        }

        $form = $this->createForm(CategoryType::class,$category);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute("admin_categories");
        }
        return $this->render('admin/categories/edit.html.twig',['form'=>$form->createView(), 'category'=>$category]);
    }

    /**
     * @Route("/delete/{id}", name="admin_categories_delete")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function deleteCategory($id, Request $request){
        $category = $this->getDoctrine()->getRepository(Category::class)->find($id);

        if($category===null){
            return $this->redirectToRoute("admin_categories");
        }

        $form = $this->createForm(CategoryType::class,$category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();

            foreach ($category->getArticles() as $article){
                $em->remove($article);
            }
            $em->remove($category);
            $em->flush();

            return $this->redirectToRoute("admin_categories");
        }

        return $this->render('admin/categories/delete.html.twig',['form'=>$form->createView(), 'category'=>$category]);

    }
}
