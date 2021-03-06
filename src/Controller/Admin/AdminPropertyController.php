<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPropertyController extends AbstractController {

    /**
     * @var PropertyRepository
     */
    private $repository; 

    /**
     * @var ObjectManager 
     */
    private $em; 

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository; 
        $this->em = $em; 
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return Response
     */
    public function index()
    {
        $properties = $this->repository->findAll();

        return $this->render('admin/property/index.html.twig', compact('properties'));

    }

    /**
     * @Route ("/admin/property/{id}", name="admin.property.edit", methods="POST|GET")
     * @return Response
     */
    public function edit(Property $property, Request $request)
    {
       $form = $this->createForm(PropertyType::class, $property);
       $form->handleRequest($request); 

       if($form->isSubmitted() && $form->isValid())
       {
           $this->em->flush();
           $this->addFlash('succes', 'Bien modifié avec succès'); 
           return $this->redirectToRoute('admin.property.index');
       }
        return $this->render('admin/property/edit.html.twig', [
            'property' => $property, 
            'form' => $form->createView()
        ]);
    }

     /**
     * @Route ("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @return Response
     */
    public function delete(Property $property, Request $request)
    {
        
        $submittedToken = $request->request->get('token');
        if ($this->isCsrfTokenValid('delete-item', $submittedToken)) {
            // ... do something, like deleting an object
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('succes', 'Bien supprimé avec succès'); 
        }
        return $this->redirectToRoute('admin.property.index');
    }



    /**
     * @Route ("/admin/property_create", name="admin.property.new")
     * 
     */
    public function new(Request $request){
        
        $property = new Property(); 
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request); 
 
        if($form->isSubmitted() && $form->isValid())
        {
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('succes', 'Bien ajouté avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/property/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }
}