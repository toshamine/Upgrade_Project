<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Certification;
use App\Entity\Document;
use App\Entity\Question;
use App\Entity\User1;
use App\Entity\WhiteTest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use phpDocumentor\Reflection\Types\Parent_;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        if($this->getUser()){
            $entityManager = $this->getDoctrine()->getManager();
            $userV = $entityManager->getRepository(User1::class)->findOneBy(['email'=>$this->getUser()->getUserIdentifier(),
                'isVerified'=>false]);

            if ($userV)
            {
                return $this->redirectToRoute('app_logout');
            }
            if(!$this->isGranted('ROLE_ADMIN')){
                return $this->redirectToRoute('index');
            }

        }

        return parent::index();
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Upgrade');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linktoRoute('Back to the website', 'fas fa-home', 'index');
        yield MenuItem::linkToCrud('Users', 'fas fa-list', User1::class);
        yield MenuItem::linkToCrud('Certification', 'fas fa-list', Certification::class);
        yield MenuItem::linkToCrud('Categories', 'fas fa-list', Category::class);
        yield MenuItem::linkToCrud('Documents', 'fas fa-list', Document::class);
        yield MenuItem::linkToCrud('White Test', 'fas fa-list', WhiteTest::class);
        yield MenuItem::linkToCrud('Questions', 'fas fa-list', Question::class);



    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return Parent::configureUserMenu($user)->setName($user->getUsername())
            //->setGravatarEmail($user->getUsername())
            ->setAvatarUrl($this->getUser()->getPicture())
            ->displayUserAvatar(true)


            ;

    }
}
