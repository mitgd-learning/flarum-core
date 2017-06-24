<?php

/*
 * This file is part of Flarum.
 *
 * (c) Toby Zerner <toby.zerner@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flarum\Event;

use Flarum\Admin\Controller\FrontendController as AdminWebAppController;
use Flarum\Forum\Controller\FrontendController as ForumWebAppController;
use Flarum\Frontend\AbstractFrontendController;
use Flarum\Frontend\FrontendView;
use Psr\Http\Message\ServerRequestInterface;

class ConfigureWebApp
{
    /**
     * @var AbstractFrontendController
     */
    public $controller;

    /**
     * @var FrontendView
     */
    public $view;

    /**
     * @var ServerRequestInterface
     */
    public $request;

    /**
     * @param AbstractFrontendController $controller
     * @param FrontendView $view
     * @param ServerRequestInterface $request
     */
    public function __construct(AbstractFrontendController $controller, FrontendView $view, ServerRequestInterface $request)
    {
        $this->controller = $controller;
        $this->view = $view;
        $this->request = $request;
    }

    public function isForum()
    {
        return $this->controller instanceof ForumWebAppController;
    }

    public function isAdmin()
    {
        return $this->controller instanceof AdminWebAppController;
    }

    public function addAssets($files)
    {
        foreach ((array) $files as $file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);

            switch ($ext) {
                case 'js':
                    $this->view->getJs()->addFile($file);
                    break;

                case 'css':
                case 'less':
                    $this->view->getCss()->addFile($file);
                    break;
            }
        }
    }

    public function addBootstrapper($bootstrapper)
    {
        $this->view->loadModule($bootstrapper);
    }
}
