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

        $this->get('/', 'main.controller:indexAction');
        $this->get('/login', 'main.controller:loginAction');
        $this->get('/register', 'main.controller:registerAction');
        $this->get('/proposeTag', 'main.controller:proposeTagAction');
        $this->get('/proposeRef', 'main.controller:proposeRefAction');
        $this->post('/processLogin', 'login.controller:processLoginAction');
        $this->get('/logout', 'login.controller:logoutAction');
        $this->post('/registerUser', 'register.controller:registerUserAction');
        $this->post('/processProposedTag', 'tag.controller:processProposedTagAction');
        $this->get('/viewProposedTags', 'tag.controller:viewProposedTagsAction');
        $this->get('/upVoteTag/{id}', 'tag.controller:upVoteTagAction');
        $this->get('/downVoteTag/{id}', 'tag.controller:downVoteTagAction');
        $this->post('/processProposedRef', 'ref.controller:processProposedRefAction');
        $this->post('/adminViewAction', 'admin.controller:adminViewAction');
        $this->get('/returnToAdminHome', 'admin.controller:returnToAdminHome');
        $this->get('/deleteUser/{id}', 'admin.controller:deleteUser');
        $this->get('/register', 'admin.controller:registerAction');
        $this->get('/updateUserAction/{id}', 'admin.controller:updateUserAction');
        $this->post('/updateUser/{id}', 'admin.controller:updateUser');
    }
}