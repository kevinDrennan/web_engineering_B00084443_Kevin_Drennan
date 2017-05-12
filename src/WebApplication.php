<?php

namespace Itb;

use Silex\Application;
use Silex\Provider\TwigServiceProvider;

class WebApplication extends Application
{
    private $myTemplatesPath = __DIR__ . '/../templates';

    public function __construct()
    {
        parent::__construct();

        $this->register(new \Silex\Provider\ServiceControllerServiceProvider());

        $this['debug'] = true;
        $this->setupTwig();
        $this->addRoutes();
    }

    public function setupTwig()
    {
        $this->register(new \Silex\Provider\TwigServiceProvider(),
            [
                'twig.path' => $this->myTemplatesPath
            ]
        );
    }

    public function addRoutes()
    {
        $this->register(new\Silex\Provider\ServiceControllerServiceProvider());

        $this['main.controller'] =
            function() {return new MainController($this);   };
        $this['login.controller'] =
            function() {return new LoginController($this);  };
        $this['register.controller'] =
            function() {return new RegisterController($this);  };
        $this['tag.controller'] =
            function() {return new TagController($this);  };
        $this['ref.controller'] =
            function() {return new RefController($this);  };
        $this['admin.controller'] =
            function() {return new AdminController($this);  };
        $this['bib.controller'] =
            function() {return new BibController($this);  };

        $this->get('/', 'main.controller:indexAction');
        $this->get('/studentLecturerIndex', 'main.controller:studentLecturerIndexAction');
        $this->get('/login', 'main.controller:loginAction');
        $this->get('/register', 'main.controller:registerAction');
        $this->get('/proposeTag', 'main.controller:proposeTagAction');
        $this->get('/proposeRef', 'main.controller:proposeRefAction');
        $this->get('/viewPersonDetails/{id}', 'main.controller:viewPersonDetails');

        $this->post('/processLogin', 'login.controller:processLoginAction');
        $this->get('/logout', 'login.controller:logoutAction');

        $this->post('/registerUser', 'register.controller:registerUserAction');

        $this->post('/processProposedTag', 'tag.controller:processProposedTagAction');
        $this->get('/viewProposedTags', 'tag.controller:viewProposedTagsAction');
        $this->get('/upVoteTag/{id}', 'tag.controller:upVoteTagAction');
        $this->get('/downVoteTag/{id}', 'tag.controller:downVoteTagAction');
        $this->get('/publishTag/{id}', 'tag.controller:publishTag');

        $this->post('/processProposedRef', 'ref.controller:processProposedRefAction');
        $this->get('/viewRefs', 'ref.controller:viewRefs');
        $this->get('/viewRefDetails/{id}', 'ref.controller:viewRefDetails');
        $this->post('/searchRefsByTags', 'ref.controller:searchRefsByTags');
        $this->post('/searchRefsByFreeText', 'ref.controller:searchRefsByFreeText');

        $this->get('/addRefToBib/{bibId}/{refId}', 'bib.controller:addRefToBib');
        $this->get('/viewBibs', 'bib.controller:viewBibs');
        $this->get('/proposeBibPage', 'bib.controller:proposeBibPage');
        $this->get('/viewBibDetails/{id}', 'bib.controller:viewBibDetails');
        $this->post('/saveBib', 'bib.controller:saveBib');
        $this->get('/showUsersBibs/{id}', 'bib.controller:showUsersBibs');
        $this->get('/modifyUsersBibsIndex', 'bib.controller:modifyUsersBibsIndex');
        $this->get('/modifyUserBib/{id}', 'bib.controller:modifyUserBib');
        $this->post('/updateBib/{id}', 'bib.controller:updateBib');
        $this->get('/deleteRefFromBib/{refId}/{bibId}', 'bib.controller:deleteRefFromBib');
        $this->get('/publishBib/{id}', 'bib.controller:publishBib');
        $this->post('/publishModifiedBib/{id}', 'bib.controller:publishModifiedBib');
        $this->get('/lecturerViewBibToModify/{id}', 'bib.controller:lecturerViewBibToModify');

        $this->post('/adminViewAction', 'admin.controller:adminViewAction');
        $this->get('/returnToAdminHome', 'admin.controller:returnToAdminHome');
        $this->get('/deleteUser/{id}', 'admin.controller:deleteUser');
        $this->get('/register', 'admin.controller:registerAction');
        $this->get('/updateUserAction/{id}', 'admin.controller:updateUserAction');
        $this->post('/updateUser/{id}', 'admin.controller:updateUser');
    }
}