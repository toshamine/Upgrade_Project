<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Certification;
use App\Entity\Cheaters;
use App\Entity\Company;
use App\Entity\Contact;
use App\Entity\Difficulty;
use App\Entity\Document;
use App\Entity\Notification;
use App\Entity\Question;
use App\Entity\RDV;
use App\Entity\Records;
use App\Entity\User1;
use App\Entity\WhiteTest;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use phpDocumentor\Reflection\Types\Parent_;
use phpDocumentor\Reflection\Types\This;
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
        yield MenuItem::linktoRoute('Back to the website', 'fa fa-mail-reply', 'index');
        yield MenuItem::linkToCrud('Users', 'fa fa-address-card-o', User1::class);
        yield MenuItem::linkToCrud('Certification', 'fa fa-graduation-cap', Certification::class);
        yield MenuItem::linkToCrud('Categories', 'fa fa-object-group', Category::class);
        yield MenuItem::linkToCrud('Documents', 'fa fa-object-group', Document::class);
        yield MenuItem::linkToCrud('White Test', 'fas fa-list', WhiteTest::class);
        yield MenuItem::linkToCrud('Questions', 'fas fa-list', Question::class);
        yield MenuItem::linkToCrud('Cheaters', 'fa fa-graduation-cap', Cheaters::class);
        yield MenuItem::linkToCrud('Difficulty', 'fa fa-graduation-cap', Difficulty::class);
        yield MenuItem::linkToCrud('RDV', 'fa fa-graduation-cap', RDV::class);
        yield MenuItem::linkToCrud('Company', 'fa fa-graduation-cap', Company::class);
        yield MenuItem::linkToCrud('Records', 'fa fa-graduation-cap', Records::class);
        yield MenuItem::linkToCrud('Contact', 'fa fa-graduation-cap', Contact::class);





    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        $entityManager = $this->getDoctrine()->getManager();
        $userV = $entityManager->getRepository(User1::class)->findOneBy(['email'=>$user->getUserIdentifier()]);


        return Parent::configureUserMenu($user)->setName($user->getUsername())
            //->setGravatarEmail($user->getUserIdentifier())
            ->setAvatarUrl($this->getParameter("app.path.product_images").'/'.$userV->getPicture() )
            ->displayUserAvatar(true)


            ;

    }
}
