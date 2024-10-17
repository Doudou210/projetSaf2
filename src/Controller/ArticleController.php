<?php

namespace App\Controller;

use App\Entity\Article;
use App\Form\AddArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class ArticleController extends AbstractController
{
    //Pour voir les articles
    #[Route('/articles', name: 'articles', methods:["GET"])]
    public function article(Request $request, ManagerRegistry $mr): Response
    {
        $articles= $mr->getRepository(Article::class)->findAll();
        return $this->render("article/article-index.html.twig", [
            "articles" => $articles
        ]);
    }

    //Pour ajouter un article
    #[Route("/addArticle", name:"addArticle")]
    public function addArticle(Request $request, SluggerInterface $slug,EntityManagerInterface $emi): Response
    {
        $article = new Article();
        $form = $this->createForm(AddArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slug->slug($article->getTitre())->lower();
            $article->setSlug($slug);

            $emi ->persist($article);
            $emi->flush();
            return $this->redirectToRoute("articles");
        }
        return $this->render('article/addArticle.html.twig', [
            "form" =>$form->createView()
        ]);
    }
    
    //Pour mettre à jour un article
    #[Route('/articles/edit/{id}', name: 'edit', methods:['GET','POST'])]
    public function edit(Article $article, Request $request,
    EntityManagerInterface $emi, SluggerInterface $slug){
        $form = $this->createForm(AddArticleType::class, $article);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $slug = $slug->slug($article->getTitre())->lower();
            $article->setSlug($slug);

            $emi->flush();
            return $this->redirectToRoute("articles");
        }
        return $this->render("article/edit.html.twig",[
            "form" => $form,
            "article" => $article
        ]);
    }
    
    //Pour supprimer un article
    #[Route('/articles/delete/{id}', name: 'delete', methods:['GET','DELETE'])]
    public function delete(Article $article,
    EntityManagerInterface $emi){
        $emi->remove($article);
        $emi->flush();
        return $this->redirectToRoute("articles");
    }


    //Pour voir un article
    #[Route('/articles/{slug}-{id}', name: 'article.show', requirements:["id"=>"\d+", "slug"=>"[a-z0-9-]+"])]
    public function show(Request $request, string $slug, int $id, ArticleRepository $repository): Response
    {
        $articles =$repository ->find($id);
        if ($articles->getSlug()!=$slug) {
            return $this->redirectToRoute("article.show",[
                "slug"=>$articles->getSlug(),
                "id"=>$articles->getId()
            ]);
        }
        return $this -> render("article/show.html.twig",[
            "articles"=>$articles
        ]);
    }
}
