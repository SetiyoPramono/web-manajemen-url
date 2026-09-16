<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Check if user is logged in and has the required role.
     *
     * @param array|null $arguments - Role required (e.g., ['admin'] or ['client'])
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // Check if user is logged in
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login')->with('error', 'Silakan login terlebih dahulu.');
        }

        // Check role if arguments provided
        if ($arguments !== null) {
            $userRole = $session->get('role');

            if (! in_array($userRole, $arguments, true)) {
                // Redirect to appropriate dashboard based on actual role
                if ($userRole === 'admin') {
                    return redirect()->to('/admin');
                }

                return redirect()->to('/client');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No action needed after the request
    }
}
