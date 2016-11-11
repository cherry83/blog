<?php

namespace SoftUniBlogBundle\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use SoftUniBlogBundle\Entity\Article;
use SoftUniBlogBundle\Entity\Role;
use SoftUniBlogBundle\Entity\Tag;
use SoftUniBlogBundle\Form\ArticleType;
use SoftUniBlogBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ArticleController extends Controller
{
    /**
     * @param Request $request
     *
     * @Route("/article/create", name="article_create")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $article->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();

            $tagsString = $request->get("tags");
            $tags = $this->getTags($em, $tagsString);
            $article->setTags($tags);

            $em->persist($article);
            $em->flush();

            return $this->redirectToRoute('blog_index');
        }

        return $this->render('article/create.html.twig', array('form' => $form->createView()));

    }

    /**
     * @Route("/article/{id}", name="article_view")
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function viewArticle($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render('article/article.html.twig', ['article' => $article]);
    }


    /**
     * @Route("article/edit/{id}", name="article_edit")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editArticle($id, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if ($article === null) {
            return $this->redirectToRoute("blog_index");
        }

        $tags = $article->getTags();

        $tagNames = [];
        foreach ($tags as $tag) {
            $tagNames[] = $tag->getName();
        }

//        $tagsString = implode(", ",$tags->toArray() );
        $tagsString = implode(", ", $tagNames);


        $currentUser = $this->getUser();
        if (!$currentUser->isAuthor($article) && !$currentUser->isAdmin())
            return $this->redirectToRoute("blog_index");

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $tagsString = $request->get("tags");
            $tags = $this->getTags($em, $tagsString);
            $article->setTags($tags);

            $em->persist($article);
            $em->flush();
            return $this->redirectToRoute('article_view', array('id' => $article->getId()));
        }

        return $this->render('article/edit.html.twig', array(
                'article' => $article,
                'form' => $form->createView(),
                'tags' => $tagsString,
            )
        );
    }

    /**
     * @Route("article/delete/{id}", name="article_delete")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     *
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deleteArticle($id, Request $request)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        if ($article === null)
            return $this->redirectToRoute("blog_index");

        $currentUser = $this->getUser();
        if (!$currentUser->isAuthor($article) && !$currentUser->isAdmin())
            return $this->redirectToRoute("blog_index");

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            return $this->redirectToRoute('blog_index');
        }

        return $this->render('article/delete.html.twig', array('article' => $article, 'form' => $form->createView()));
    }

    /**
     * @param $em EntityManager
     * @param $tagsSting String
     *
     * @return ArrayCollection
     */
    private function getTags($em, $tagsSting)
    {
        $tags = explode(",", $tagsSting);
        $tagRepo = $this->getDoctrine()->getRepository(Tag::class);
        $tagsToSave = new  ArrayCollection();

        foreach ($tags as $tagName) {
            $tagName = trim($tagName);
            if ($tagName !== '') {
                $tag = $tagRepo->findOneBy(["name" => $tagName]);
                if ($tag === null) {
                    $tag = new Tag();
                    $tag->setName($tagName);
                    $em->persist($tag);
                }

                $tagsToSave->add($tag);
            }
        }
        return $tagsToSave;
    }

}
