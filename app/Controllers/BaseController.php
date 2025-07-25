<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        // Set header agar halaman tidak di-cache (penting untuk logout)
        if (!is_cli()) {
            header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
            header('Cache-Control: post-check=0, pre-check=0', false);
            header('Pragma: no-cache');
        }

        // Ambil segment pertama dari path
        $uri = trim($request->getUri()->getPath(), '/');
        $firstSegment = explode('/', $uri)[0];

        // Daftar route publik yang tidak perlu login
        $publicRoutes = ['login', 'register', 'logout', 'auth'];

        if (!session()->get('isLoggedIn') && in_array($firstSegment, $publicRoutes)) {
            // Jika akses ke halaman publik, biarkan
            return;
        }

        if (!session()->get('isLoggedIn')) {
            // Jika belum login dan bukan halaman publik, redirect ke login
            header('Location: ' . base_url('login'));
            exit;
        }

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
    }
}
